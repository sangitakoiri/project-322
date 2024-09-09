<?php
include("connection.php");

// Initialize variables
$show_records = false; // Flag to control the display of records
$employee_count = 0; // To store the employee count

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['clear'])) {
        // Clear logic if needed
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page to clear data
        exit();
    } elseif (isset($_POST['display'])) {
        // Calculate the date exactly three years ago from today
        $date_three_years_ago = date('Y-m-d', strtotime('-3 years'));

        // Prepare SQL query to fetch records where ame_date is less than or equal to three years ago and not '0000-00-00'
        // and sort by ame_date and category
        $sql = "SELECT * FROM management WHERE ame_date <= ? AND ame_date != '0000-00-00' ORDER BY ame_date ASC, category ASC";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }

        // Bind parameters and execute the statement
        $stmt->bind_param("s", $date_three_years_ago);
        $stmt->execute();
        $result = $stmt->get_result();

        // Store results in an array
        $records = [];
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        $stmt->close();
        $conn->close();

        // Set flag to show records
        $show_records = true;
        $employee_count = count($records); // Count of employees with ame_date less than or equal to three years ago
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Management Form</title>
    <style>
        /* Basic reset for margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        h2 {
            color: #444;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin-right: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd; /* Added border */
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #eaeaea;
        }

        .no-records {
            text-align: center;
            color: #666;
            padding: 20px;
        }
        
        /* Advanced Styling for Back Button */
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
    <script type="text/javascript">
        // Function to show the pop-up with employee count
        function showEmployeeCount() {
            var employeeCount = <?php echo json_encode($employee_count); ?>;
            if (employeeCount > 0) {
                alert('Count of employees with ame_date three years ago or before (excluding 0000-00-00): ' + employeeCount);
            }
        }

        // Call function when the page loads
        window.onload = function() {
            if (<?php echo json_encode($show_records); ?>) {
                showEmployeeCount();
            }
        };
    </script>
</head>
<body>
    <form method="POST" action="">
        <input type="submit" name="display" value="Display">
        <input type="submit" name="clear" value="Clear">
        <a href="landing.php" class="back-button">Return to List</a>
        <a href="index.php" class="back-button">Return to Home</a>
    </form>

    <?php if ($show_records): ?>
        <h2>Records of Employees who have not completed their AME in the past Three Years</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
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
                <?php if (empty($records)) { ?>
                    <tr>
                        <td colspan="17" class="no-records">No records found</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($records as $record) { 
                        // Format dates here
                        $ameDate = new DateTime($record['ame_date']);
                        $displayAmeDate = $ameDate->format('d-m-Y');
                        
                        $lmcDate = new DateTime($record['lmc_date']);
                        $displayLmcDate = $lmcDate->format('d-m-Y');

                        $dueDate = new DateTime($record['due_date']);
                        $displayDueDate = $dueDate->format('d-m-Y');

                        $doj = new DateTime($record['doj']);
                        $displayDoj = $doj->format('d-m-Y');

                        $dob = new DateTime($record['dob']);
                        $displayDob = $dob->format('d-m-Y');
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['regt_no']); ?></td>
                            <td><?php echo htmlspecialchars($record['rank']); ?></td>
                            <td><?php echo htmlspecialchars($record['name']); ?></td>
                            <td><?php echo $displayDob; ?></td>
                            <td><?php echo htmlspecialchars($record['age']); ?></td>
                            <td><?php echo $displayDoj; ?></td>
                            <td><?php echo htmlspecialchars($record['service']); ?></td>
                            <td><?php echo htmlspecialchars($record['unit']); ?></td>
                            <td><?php echo htmlspecialchars($record['ame_details']); ?></td>
                            <td><?php echo $displayAmeDate; ?></td>
                            <td><?php echo htmlspecialchars($record['category']); ?></td>
                            <td><?php echo htmlspecialchars($record['lmc']); ?></td>
                            <td><?php echo $displayLmcDate; ?></td>
                            <td><?php echo htmlspecialchars($record['duration']); ?></td>
                            <td><?php echo $displayDueDate; ?></td>
                            <td><?php echo htmlspecialchars($record['percentage_disability']); ?></td>
                            <td><?php echo htmlspecialchars($record['category_after_lmc']); ?></td>
                            <td><?php echo htmlspecialchars($record['diseases']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
