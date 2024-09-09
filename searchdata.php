<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search and Filter Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* Remove scrollbars from the body */
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            box-sizing: border-box;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 2500px; /* Adjust the maximum width as needed */
            height: 100%;
            max-height: 100vh; /* Ensure card does not exceed viewport height */
            display: flex;
            flex-direction: column;
        }

        .card-body {
            padding: 20px;
            flex: 1; /* Allow card-body to take up remaining space */
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-bottom: 2px solid #0056b3;
            border-radius: 10px 10px 0 0;
            padding: 15px;
            width: 100%; /* Ensure header fits full width */
        }

        .table-container {
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: auto; /* Enable horizontal scrolling */
            max-height: calc(100vh - 200px); /* Adjust based on card-header and padding */
            max-width: 1350px;  /* Ensure table-container takes full width */
        }

        .table {
            border-radius: 10px;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #dee2e6;
            table-layout: auto; /* Adjust table layout based on content */
        }

        .table thead th {
            background-color: #007bff;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        .table tbody td, .table thead th {
            text-align: center;
            padding: 8px;
            border: 1px solid #dee2e6;
        }

        .back-button, .clear-button, .print-button {
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
            width: 170px;
            height: 40px;
            margin-left: 10px; /* Space between buttons */
        }

        .btn-group {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
        }

        .form-controls {
            display: flex;
            align-items: center;
        }

        .form-controls .form-select,
        .form-controls .form-control {
            margin-right: 10px; /* Space between form controls */
        }

        @media print {
            /* Print styles */
            body {
                background-color: white;
                overflow: visible; /* Ensure content is visible */
                margin: 0; /* Remove margin for print */
            }

            .container, .card {
                width: auto;
                height: auto;
                box-shadow: none;
                border: none;
                padding: 0;
                margin: 0;
            }

            .card-header, .form-controls, .btn-group {
                display: none; /* Hide title, search fields, and buttons */
            }

            .table-container {
                max-height: none; /* Remove max-height restriction for printing */
                overflow: visible;
            }

            .table {
                border: 1px solid #000; /* Add border for better print view */
                width: 100%;
                page-break-inside: auto; /* Avoid breaking inside the table */
            }

            .table thead th {
                background-color: #000;
                color: #fff;
            }

            @page {
                size: landscape; /* Set page size to landscape */
                margin: 1in; /* Set margins as needed */
            }

            .table-container {
                page-break-after: always; /* Force page break after each table */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Annual Medical Examination</h4>
                    </div>
                    <div class="card-body">
                        <form id="search-form" action="" method="post">
                            <div class="form-controls mb-4">
                                <!-- AME Status Select -->
                                <select class="form-select" name="ame-filter">
                                    <option value="">Select AME Status</option>
                                    <option value="done">Done</option>
                                    <option value="not done">Not Done</option>
                                </select>

                                <!-- LMC Status Select -->
                                <select class="form-select" name="lmc-filter">
                                    <option value="">Select LMC Status</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>

                                <!-- Buttons -->
                                <div class="btn-group ms-auto">
                                    <button type="submit" name="filter-btn" class="back-button">Search Data</button>
                                    <a href="landing.php" class="back-button">Return to List</a>
                                    <a href="index.php" class="back-button">Return to Home</a>
                                    <button type="reset" class="back-button" onclick="clearTable()">Clear</button>
                                    <button type="button" class="print-button" onclick="printTable()">Print</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-container">
                            <table class="table table-striped table-bordered" id="records-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Regt_No</th>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">DOB</th>
                                        <th scope="col">Age</th>
                                        <th scope="col">DOJ</th>
                                        <th scope="col">Service</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">AME Details</th>
                                        <th scope="col">Date of AME</th>
                                        <th scope="col">Details for Category</th>
                                        <th scope="col">LMC</th>
                                        <th scope="col">Date of LMC</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Percentage of Disability</th>
                                        <th scope="col">Details for Category</th>
                                        <th scope="col">Diseases/Diagnosis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                include("connection.php");

                                if (isset($_POST['filter-btn'])) {
                                    $searchValue = mysqli_real_escape_string($conn, $_POST['search-value']);
                                    $ameFilter = mysqli_real_escape_string($conn, $_POST['ame-filter']);
                                    $lmcFilter = mysqli_real_escape_string($conn, $_POST['lmc-filter']);
                                    
                                    // Initialize query variable
                                    $query = "SELECT * FROM management WHERE 1";

                                    // Add search filter
                                    if (!empty($searchValue)) {
                                        $query .= " AND (ame_details LIKE '%$searchValue%' OR category LIKE '%$searchValue%')";
                                    }

                                    // Add AME filter
                                    if (!empty($ameFilter)) {
                                        $query .= " AND ame_details = '$ameFilter'";
                                        // Order by AME date (ascending) and Category (descending)
                                        $query .= " ORDER BY ame_date ASC, category DESC";
                                    } 
                                    // Add LMC filter
                                    elseif (!empty($lmcFilter)) {
                                        $query .= " AND lmc = '$lmcFilter'";
                                        // Order by Due Date (ascending) and Category After LMC (descending)
                                        $query .= " ORDER BY due_date ASC, category_after_lmc DESC";
                                    } 
                                    // Default ordering if no specific filter is applied
                                    else {
                                        $query .= " ORDER BY ame_date ASC, category ASC";
                                    }

                                    $query_run = $conn->query($query);

                                    if ($query_run && $query_run->num_rows > 0) {
                                        $count = 1;
                                        while ($row = $query_run->fetch_assoc()) {
                                            // Format the date for display if needed
                                            $formatted_dob = date('d-m-Y', strtotime($row['dob']));
                                            $formatted_doj = date('d-m-Y', strtotime($row['doj']));
                                            $formatted_ame_date = date('d-m-Y', strtotime($row['ame_date']));
                                            $formatted_lmc_date = date('d-m-Y', strtotime($row['lmc_date']));
                                            $formatted_due_date = date('d-m-Y', strtotime($row['due_date']));

                                            echo "<tr>
                                                <td>{$count}</td>
                                                <td>{$row['regt_no']}</td>
                                                <td>{$row['rank']}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$formatted_dob}</td>
                                                <td>{$row['age']}</td>
                                                <td>{$formatted_doj}</td>
                                                <td>{$row['service']}</td>
                                                <td>{$row['unit']}</td>
                                                <td>{$row['ame_details']}</td>
                                                <td>{$formatted_ame_date}</td>
                                                <td>{$row['category']}</td>
                                                <td>{$row['lmc']}</td>
                                                <td>{$formatted_lmc_date}</td>
                                                <td>{$row['duration']}</td>
                                                <td>{$formatted_due_date}</td>
                                                <td>{$row['percentage_disability']}</td>
                                                <td>{$row['category_after_lmc']}</td>
                                                <td>{$row['diseases']}</td>
                                            </tr>";
                                            $count++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='18'>No records found.</td></tr>";
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearTable() {
            // Clear table rows except the header
            const tableBody = document.querySelector('#records-table tbody');
            while (tableBody.rows.length > 0) {
                tableBody.deleteRow(0);
            }
        }

        function printTable() {
            // Open print dialog
            window.print();
        }
    </script>
</body>
</html>
