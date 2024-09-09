<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search and Filter Records by Regt_No</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
            padding: 15px;
            max-width: 1900px;
            margin-left: auto;
            margin-right: auto;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            border-bottom: 2px solid #0056b3;
            border-radius: 10px 10px 0 0;
            padding: 15px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .form-group input {
            border-radius: 5px;
            border: 2px solid #ced4da;
            padding: 10px;
            border-color: #feb47b;
            width: calc(100% - 120px);
            display: inline-block;
        }
        .form-group button, .form-group a {
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            border: none;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-left: 10px;
            display: inline-block;
        }
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #003d7a);
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        .clear-button {
            background: linear-gradient(45deg, #28a745, #218838);
        }
        .clear-button:hover {
            background: linear-gradient(45deg, #218838, #1e7e34);
        }
        .clear-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        .back-button {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }
        .back-button:hover {
            background: linear-gradient(45deg, #0056b3, #003d7a);
        }
        .back-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        .table-container {
            overflow: auto;
            max-height: 500px;
            margin-top: 20px;
        }
        .table {
            border-radius: 10px;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #dee2e6;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 1;
            border: 1px solid #dee2e6;
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
        .table tbody td {
            font-size: 0.9rem;
        }
        .table th {
            font-size: 1rem;
        }
        .card-body {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Annual Medical Examination</h4>
                    </div>
                    <div class="card-body">
                        <form id="filter-form" action="" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search & Filter by Regt_No" name="filter-value">
                                <button type="submit" name="filter-btn" class="btn-primary">Search Data</button>
                                <button type="button" class="clear-button" onclick="clearForm()">Clear</button>
                                <a href="landing.php" class="back-button">Return to List</a>
                                <a href="index.php" class="back-button">Return to Home</a>
                            </div>
                        </form>
                        <div class="table-container">
                            <table id="results-table" class="table table-striped table-bordered">
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
                                    // Database connection
                                    $conn = new mysqli('127.0.0.1', 'root', '', 'employee1');
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    if (isset($_POST['filter-btn'])) {
                                        // Retrieve and sanitize input
                                        $value_filter = mysqli_real_escape_string($conn, $_POST['filter-value']);
                                        
                                        // Construct the SQL query to search by regt_no using prepared statements
                                        $stmt = $conn->prepare("SELECT * FROM emprecord WHERE regt_no = ? ORDER BY ame_date ASC, category ASC");
                                        $stmt->bind_param("s", $value_filter);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            $count = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                // Format the dates for each record
                                                $ameDate = new DateTime($row['ame_date']);
                                                $displayAmeDate = $ameDate->format('d-m-Y');
                                                
                                                $lmcDate = new DateTime($row['lmc_date']);
                                                $displayLmcDate = $lmcDate->format('d-m-Y');

                                                $dueDate = new DateTime($row['due_date']);
                                                $displayDueDate = $dueDate->format('d-m-Y');

                                                $doj = new DateTime($row['doj']);
                                                $displayDoj = $doj->format('d-m-Y');

                                                $dob = new DateTime($row['dob']);
                                                $displayDob = $dob->format('d-m-Y');
                                                ?>
                                                <tr>
                                                    <th scope="row"><?php echo $count++; ?></th>
                                                    <td><?php echo htmlspecialchars($row['regt_no']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['rank']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                    <td><?php echo $displayDob; ?></td>
                                                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                                                    <td><?php echo $displayDoj; ?></td>
                                                    <td><?php echo htmlspecialchars($row['service']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['unit']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['ame_details']); ?></td>
                                                    <td><?php echo $displayAmeDate; ?></td>
                                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['lmc']); ?></td>
                                                    <td><?php echo $displayLmcDate; ?></td>
                                                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                                                    <td><?php echo $displayDueDate; ?></td>
                                                    <td><?php echo htmlspecialchars($row['percentage_disability']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['category_after_lmc']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['diseases']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="17">No Record Found</td>
                                            </tr>
                                            <?php
                                        }
                                        $stmt->close();
                                    }
                                    $conn->close();
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
        function clearForm() {
            document.getElementById('filter-form').reset();
            document.getElementById('results-table').getElementsByTagName('tbody')[0].innerHTML = ''; // Clear table body
        }
    </script>
</body>
</html>
