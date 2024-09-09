<?php
require 'vendor/autoload.php'; // Ensure Composer's autoload file is included

use PhpOffice\PhpSpreadsheet\IOFactory;

// Start session to store status and last upload time
session_start();

// Database configuration
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "employee1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$records = [];
$fileStatus = '';
$columns = [];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']['name'])) {
    $file = $_FILES['file'];
    $fileStatus = '';

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $fileStatus = 'Upload error: ' . $file['error'];
    } elseif ($file['size'] > 10485760) { // 10MB limit
        $fileStatus = 'File size exceeds limit';
    } elseif (!in_array($file['type'], ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])) {
        $fileStatus = 'Invalid file type';
    } else {
        // Process the file
        try {
            $spreadsheet = IOFactory::load($file['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            if (empty($data)) {
                throw new Exception("No data found in the file.");
            }

            $columns = array_shift($data);

            // Check if 'regt_no' is a column
            if (!in_array('regt_no', $columns)) {
                throw new Exception("Column 'regt_no' not found in the file.");
            }

            // Prepare SQL for updating and inserting
            $updateAssignments = [];
            $sql = "INSERT INTO management (" . implode(", ", array_map(fn($col) => "`$col`", $columns)) . ") VALUES (" . implode(", ", array_fill(0, count($columns), '?')) . ")";

            foreach ($columns as $column) {
                $updateAssignments[] = "`$column` = VALUES(`$column`)";
            }

            $sql .= " ON DUPLICATE KEY UPDATE " . implode(", ", $updateAssignments);
            error_log("SQL Query: " . $sql); // Debugging line

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $success = true;
            $regtNoIndex = array_search('regt_no', $columns);

            foreach ($data as $rowIndex => $row) {
                // Trim all cell values to remove extra spaces
                $row = array_map('trim', $row);

                // Skip completely empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Check if regt_no is empty or not set
                if (empty($row[$regtNoIndex])) {
                    // Log the row content for debugging
                    error_log("Debug Info: Row " . ($rowIndex + 2) . " - regt_no value: '" . (isset($row[$regtNoIndex]) ? $row[$regtNoIndex] : 'NULL') . "'");
                    $success = false;
                    $fileStatus = "Failed - Missing value for regt_no in row " . ($rowIndex + 2) . ".";
                    break;
                }

                // Prepare and bind parameters
                $types = str_repeat('s', count($row));
                $stmt->bind_param($types, ...$row);

                if (!$stmt->execute()) {
                    $success = false;
                    $fileStatus = "Failed - Error processing row " . ($rowIndex + 2) . ": " . $stmt->error;
                    break;
                }
            }

            if ($success) {
                $fileStatus = 'File processed successfully.';
                $_SESSION['last_upload_time'] = time(); // Store upload time in session
            }
        } catch (Exception $e) {
            $fileStatus = 'Failed - ' . $e->getMessage();
        }
    }
    $_SESSION['file_status'] = $fileStatus;
}

// Handle clear status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_status'])) {
    unset($_SESSION['file_status']);
}

// Handle display all records with sorting
if (isset($_POST['display'])) {
    $stmt = $conn->prepare("SELECT * FROM management ORDER BY `ame_date` ASC, `category` ASC");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $records = $result->fetch_all(MYSQLI_ASSOC);

    // Format dates for each record
    foreach ($records as &$record) {
        if (!empty($record['ame_date'])) {
            $record['ame_date'] = (new DateTime($record['ame_date']))->format('d/m/Y');
        }
        if (!empty($record['lmc_date'])) {
            $record['lmc_date'] = (new DateTime($record['lmc_date']))->format('d/m/Y');
        }
        if (!empty($record['due_date'])) {
            $record['due_date'] = (new DateTime($record['due_date']))->format('d/m/Y');
        }
        if (!empty($record['doj'])) {
            $record['doj'] = (new DateTime($record['doj']))->format('d/m/Y');
        }
        if (!empty($record['dob'])) {
            $record['dob'] = (new DateTime($record['dob']))->format('d/m/Y');
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel Files</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100vw;
            background: #f4f4f9;
            padding: 20px;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="file"] {
            padding: 10px;
            border: 2px solid #007bff;
            border-radius: 5px;
            background-color: #f9f9f9;
            flex: 1;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            white-space: nowrap;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .table-wrapper {
            flex: 1;
            overflow: auto;
            max-height: 500px; /* Adjust this value to set the maximum height of the table */
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px; /* Adjust this value to ensure the table has enough space for all columns */
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table thead th {
            position: sticky;
            top: 0;
            background-color: #007bff;
            color: #ffffff;
            z-index: 10; /* Ensure the heading stays on top of the table body */
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            background-color: #fafafa;
            word-wrap: break-word;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e2e2e2;
        }

        .status-message {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #ffffff;
            color: #333;
        }

        .status-message.success {
            border-color: #28a745;
            color: #28a745;
        }

        .status-message.error {
            border-color: #dc3545;
            color: #dc3545;
        }
        
        .back-button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            color: #fff;
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            background: linear-gradient(45deg, #0056b3, #003d7a);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .back-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Excel Files</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="file">
            <input type="submit" value="Upload">
            <a href="landing.php" class="back-button">Return to List</a>
            <a href="index.php" class="back-button">Return to Home</a>
        </form>
        <form action="" method="post">
            <input type="submit" name="display" value="Display All Records">
            <input type="submit" name="clear_status" value="Clear Status">
        </form>

        <?php if (isset($_SESSION['file_status'])): ?>
            <div class="status-message <?= strpos($_SESSION['file_status'], 'Failed') === false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($_SESSION['file_status']) ?>
            </div>
            <?php unset($_SESSION['file_status']); ?>
        <?php endif; ?>

        <div class="table-wrapper">
            <?php if (!empty($records)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th> 
                            <th>Regt No</th>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Age</th>
                            <th>Date of Joining</th>
                            <th>Service</th>
                            <th>Unit</th>
                            <th>AME Details</th>
                            <th>AME Date</th>
                            <th>Category</th>
                            <th>LMC</th>
                            <th>LMC Date</th>
                            <th>Duration</th>
                            <th>Due Date</th>
                            <th>Percentage Disability</th>
                            <th>Category After LMC</th>
                            <th>Diseases</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $serialNo = 1; // Initialize serial number
                        foreach ($records as $row): ?>
                            <tr>
                                <td><?php echo $serialNo++; ?></td> <!-- Display Serial No -->
                                <?php foreach ($row as $cell): ?>
                                    <td><?php echo htmlspecialchars($cell); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($records)): ?>
                            <tr>
                                <td colspan="18">No records found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
