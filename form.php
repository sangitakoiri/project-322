<?php
include("connection.php");

// Initialize variables
$reg = $rank = $name = $dob = $age = $doj = $service = $unit =  $ame = $ame_date = $lmc = $lmc_date = $duration = $due_date = $percent = $category1 = $category2 = $diseases = "";

// Function to calculate age
function calculateAge($dob) {
    $today = new DateTime();
    $birthDate = new DateTime($dob);
    $age = $today->diff($birthDate);
    return $age->format('%y Years %m Months %d Days');
}

// Function to calculate due date
function calculateDueDate($inputDate, $durationWeeks, $dueDateOption) {
    $due_date = new DateTime($inputDate);
    
    if ($dueDateOption === '2years') {
        $due_date->modify('+2 years');
    } else {
        $due_date->modify("+$durationWeeks weeks");
    }

    return $due_date->format('Y-m-d');
}

// Function to calculate service duration
function calculateServiceDuration($doj) {
    $today = new DateTime();
    $joiningDate = new DateTime($doj);
    $service = $today->diff($joiningDate);
    return $service->format('%y Years %m Months %d Days');
}

 
// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    if ($dob) {
        $age = calculateAge($dob);
    }

    $doj = isset($_POST['doj']) ? $_POST['doj'] : '';
    if ($doj) {
        $service = calculateServiceDuration($doj);
    }

    $lmc_date = isset($_POST['lmc_date']) ? $_POST['lmc_date'] : '';
    $duration = isset($_POST['duration']) ? intval($_POST['duration']) : 0;
    $dueDateOption = isset($_POST['due_date_option']) ? $_POST['due_date_option'] : 'weeks'; 

    if ($lmc_date) {
        $due_date = calculateDueDate($lmc_date, $duration, $dueDateOption);
    }

    if (isset($_POST['save'])) {
    $reg = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $rank = filter_input(INPUT_POST, 'rank', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
    
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_STRING);
    $doj = filter_input(INPUT_POST, 'doj', FILTER_SANITIZE_STRING);
    $service = filter_input(INPUT_POST, 'service', FILTER_SANITIZE_STRING);
    $unit = filter_input(INPUT_POST, 'unit', FILTER_SANITIZE_STRING);
    $ame = filter_input(INPUT_POST, 'ame', FILTER_SANITIZE_STRING);
    $ame_date = ($ame == 'Not Done') ? '0000-00-00' : filter_input(INPUT_POST, 'ame_date', FILTER_SANITIZE_STRING);
    $category1 = isset($_POST['category1']) ? implode(',', $_POST['category1']) : '';
    $lmc = filter_input(INPUT_POST, 'lmc', FILTER_SANITIZE_STRING);
    $lmc_date = ($lmc == 'No') ? NULL : filter_input(INPUT_POST, 'lmc_date', FILTER_SANITIZE_STRING);
    $duration = ($lmc == 'No') ? NULL : filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);
    $due_date = ($lmc == 'No') ? NULL : calculateDueDate($lmc_date, $duration, $dueDateOption);
    $percent = ($lmc == 'No') ? NULL : filter_input(INPUT_POST, 'percent', FILTER_VALIDATE_FLOAT);
    $category2 = isset($_POST['category2']) ? implode(',', $_POST['category2']) : '';
    $diseases = filter_input(INPUT_POST, 'diseases', FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("SELECT regt_no FROM management WHERE regt_no = ?");
    $stmt->bind_param("s", $reg);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Data Already Exists');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO management (regt_no, rank, name, dob, age, doj, service, unit, ame_details, ame_date, category, lmc, lmc_date, duration, due_date, percentage_disability, category_after_lmc, diseases) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssissss", $reg, $rank, $name, $dob, $age, $doj, $service, $unit, $ame, $ame_date, $category1, $lmc, $lmc_date, $duration, $due_date, $percent, $category2, $diseases);

        if ($stmt->execute()) {
            echo "<script>alert('Data saved successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    }

    $stmt->close();
}elseif (isset($_POST['searchdata'])) {
    $reg = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $stmt = $conn->prepare("SELECT * FROM management WHERE regt_no = ?");
    $stmt->bind_param("s", $reg);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $reg = $row['regt_no'];
        $rank = $row['rank'];
        $name = $row['name'];
        $dob = $row['dob'];
        $age = $row['age'];
        $doj = $row['doj'];
        $service = $row['service'];
        $unit = $row['unit']; // Make sure this is correctly fetched
        $ame = $row['ame_details'];
        $ame_date = $row['ame_date'];
        $category1 = explode(',', $row['category']);
        $lmc = $row['lmc'];
        $lmc_date = $row['lmc_date'];
        $duration = $row['duration'];
        $due_date = $row['due_date'];
        $percent = $row['percentage_disability'];
        $category2 = explode(',', $row['category_after_lmc']);
        $diseases = $row['diseases'];
    } else {
        echo "<script>alert('No record found');</script>";
    }
    $stmt->close();
}elseif (isset($_POST['modify'])) {
       $reg = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $rank = filter_input(INPUT_POST, 'rank', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
    
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_STRING);
    $doj = filter_input(INPUT_POST, 'doj', FILTER_SANITIZE_STRING);
    $service = filter_input(INPUT_POST, 'service', FILTER_SANITIZE_STRING);
    $unit = filter_input(INPUT_POST, 'unit', FILTER_SANITIZE_STRING);
    $ame = filter_input(INPUT_POST, 'ame', FILTER_SANITIZE_STRING);
    $ame_date = ($ame == 'Not Done') ? '0000-00-00' : filter_input(INPUT_POST, 'ame_date', FILTER_SANITIZE_STRING);
    $category1 = isset($_POST['category1']) ? implode(',', $_POST['category1']) : '';
    $lmc = filter_input(INPUT_POST, 'lmc', FILTER_SANITIZE_STRING);
    $lmc_date = ($lmc == 'No') ? NULL : filter_input(INPUT_POST, 'lmc_date', FILTER_SANITIZE_STRING);
    $duration = ($lmc == 'No') ? NULL : filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);
    $due_date = ($lmc == 'No') ? NULL : calculateDueDate($lmc_date, $duration, $dueDateOption);
    $percent = ($lmc == 'No') ? NULL : filter_input(INPUT_POST, 'percent', FILTER_VALIDATE_FLOAT);
    $category2 = isset($_POST['category2']) ? implode(',', $_POST['category2']) : '';
    $diseases = filter_input(INPUT_POST, 'diseases', FILTER_SANITIZE_STRING);


        $stmt = $conn->prepare("UPDATE management SET rank = ?, name = ?, dob = ?, age = ?, doj = ?, service = ?, unit = ?, ame_details = ?, ame_date = ?, category = ?, lmc = ?, lmc_date = ?, duration = ?, due_date = ?, percentage_disability = ?, category_after_lmc = ?, diseases = ? WHERE regt_no = ?");
        $stmt->bind_param("sssssssssssssissss", $rank, $name, $dob, $age, $doj, $service, $unit, $ame, $ame_date, $category1, $lmc, $lmc_date, $duration, $due_date, $percent, $category2, $diseases, $reg);

        if ($stmt->execute()) {
            echo "<script>alert('Data updated successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } elseif (isset($_POST['clear'])) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['delete'])) {
    $reg = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

    // Prepare to insert into emprecord
    $stmt = $conn->prepare("INSERT INTO emprecord (regt_no, rank, name, dob, age, doj, service, unit, ame_details, ame_date, category, lmc, lmc_date, duration, due_date, percentage_disability, category_after_lmc, diseases)
                            SELECT regt_no, rank, name, dob, age, doj, service, unit, ame_details, ame_date, category, lmc, lmc_date, duration, due_date, percentage_disability, category_after_lmc, diseases
                            FROM management WHERE regt_no = ?");
    $stmt->bind_param("s", $reg);
    
    if ($stmt->execute()) {
        // Proceed to delete the record from management
        $stmt = $conn->prepare("DELETE FROM management WHERE regt_no = ?");
        $stmt->bind_param("s", $reg);

        if ($stmt->execute()) {
            echo "<script>alert('Data deleted and stored in emprecord successfully');</script>";
        } else {
            echo "<script>alert('Error deleting data: " . $stmt->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error storing data in emprecord: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}


    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANNUAL MEDICAL EXAMINATION</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
      window.onload = function() {
    toggleLMCFields();
    toggleAMEFields();
    handleDueDateOptionChange();

    var lmc_date = document.getElementById("lmc_date").value;
    var duration = document.getElementById("duration").value;
    if (lmc_date && duration) {
        calculateDueDate();
    }

    document.getElementById("ame").addEventListener('change', toggleAMEFields);
    document.getElementById("lmc").addEventListener('change', toggleLMCFields);
    document.querySelector('input[name="due_date_option"]').addEventListener('change', handleDueDateOptionChange);
    document.getElementById("lmc_date").addEventListener('change', calculateDueDate);
    document.getElementById("duration").addEventListener('change', calculateDueDate);
    document.getElementById("doj").addEventListener('change', calculateServiceDuration);
};

function calculateAge() {
    var dob = document.getElementById("dob").value;
    var today = new Date();
    var birthDate = new Date(dob);

    var ageYears = today.getFullYear() - birthDate.getFullYear();
    var ageMonths = today.getMonth() - birthDate.getMonth();
    var ageDays = today.getDate() - birthDate.getDate();

    if (ageDays < 0) {
        ageMonths--;
        var prevMonth = new Date(today.getFullYear(), today.getMonth() - 1, 0);
        ageDays += prevMonth.getDate();
    }

    if (ageMonths < 0) {
        ageYears--;
        ageMonths += 12;
    }

    document.getElementById("age").value = `${ageYears} years, ${ageMonths} months, ${ageDays} days`;
}

   document.addEventListener('DOMContentLoaded', function() {
            function calculateDueDate() {
                const lmcDateInput = document.getElementById("lmc_date");
                const durationInput = document.getElementById("duration");
                const dueDateOption = document.querySelector('input[name="due_date_option"]:checked')?.value;

                const lmcDate = lmcDateInput.value;
                const duration = parseInt(durationInput.value, 10);

                console.log("LMC Date:", lmcDate);
                console.log("Duration:", duration);
                console.log("Due Date Option:", dueDateOption);

                if (!lmcDate || !dueDateOption) {
                    console.warn("LMC date or due date option is missing.");
                    return;
                }

                let dueDate = new Date(lmcDate);
                if (isNaN(dueDate.getTime())) {
                    console.warn("Invalid LMC date.");
                    return;
                }

                if (dueDateOption === '2years') {
                    dueDate.setFullYear(dueDate.getFullYear() + 2);
                    document.getElementById("hidden_duration_option").value = '2years';
                } else if (dueDateOption === 'weeks' && !isNaN(duration)) {
                    dueDate.setDate(dueDate.getDate() + (duration * 7));
                    document.getElementById("hidden_duration_option").value = '';
                } else {
                    console.warn("Invalid duration or due date option.");
                    return;
                }

                document.getElementById("due_date").value = dueDate.toISOString().split('T')[0];
            }

            function handleDueDateOptionChange() {
                const dueDateOption = document.querySelector('input[name="due_date_option"]:checked')?.value;
                const durationField = document.getElementById("duration");

                if (dueDateOption === '2years') {
                    durationField.value = '';
                    durationField.disabled = true;
                } else if (dueDateOption === 'weeks') {
                    durationField.disabled = false;
                }

                calculateDueDate();
            }

            // Event listeners for form inputs
            document.getElementById("lmc_date").addEventListener('change', calculateDueDate);
            document.getElementById("duration").addEventListener('input', calculateDueDate);
            document.querySelectorAll('input[name="due_date_option"]').forEach(el => {
                el.addEventListener('change', handleDueDateOptionChange);
            });

            // Initial call to handle default selection
            handleDueDateOptionChange();
        });
function toggleLMCFields() {
    var lmc = document.getElementById("lmc").value;
    document.getElementById("lmc-fields").style.display = (lmc === 'Yes') ? 'block' : 'none';
}

function toggleAMEFields() {
    var ameDetails = document.getElementById("ame").value;
    document.getElementById("ame-fields").style.display = (ameDetails === 'Done') ? 'block' : 'none';
}

function calculateServiceDuration() {
    var doj = document.getElementById("doj").value;
    var today = new Date();
    var joiningDate = new Date(doj);

    var years = today.getFullYear() - joiningDate.getFullYear();
    var months = today.getMonth() - joiningDate.getMonth();
    var days = today.getDate() - joiningDate.getDate();

    if (days < 0) {
        months--;
        var prevMonth = new Date(today.getFullYear(), today.getMonth() - 1, 0);
        days += prevMonth.getDate();
    }

    if (months < 0) {
        years--;
        months += 12;
    }

    document.getElementById("service").value = `${years} years, ${months} months, ${days} days`;
}

document.addEventListener('DOMContentLoaded', (event) => {
    // Function to handle Enter key press
    function handleEnterKey(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent the default form submission
            let formElements = Array.from(document.querySelectorAll('input, select, textarea')); // Select all form elements
            let index = formElements.indexOf(document.activeElement); // Find the current focused element
            if (index > -1 && index < formElements.length - 1) {
                formElements[index + 1].focus(); // Move focus to the next element
            }
        }
    }

    // Add event listener to the form for keydown events
    document.querySelector('form').addEventListener('keydown', handleEnterKey);
});


    </script>

     <style>
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
    
</head>
<body>
    <div id="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <h1 class="heading">ANNUAL MEDICAL EXAMINATION</h1>
    
            <label for="id">Reg. No:</label>
            <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($reg); ?>" required><br>

            <label>Rank</label>
                    <select name="rank" class="textfeild">
                        <option value="Select Rank" <?php echo ($rank == 'Select Rank') ? 'selected' : ''; ?>>Select Rank</option>
                        <option value="Trademan" <?php echo ($rank == 'Trademan') ? 'selected' : ''; ?>>Trademan</option>
                        <option value="ORS" <?php echo ($rank == 'ORS') ? 'selected' : ''; ?>>ORS</option>
                        <option value="SOS" <?php echo ($rank == 'SOS') ? 'selected' : ''; ?>>SOS</option>
                        <option value="Officer" <?php echo ($rank == 'Officer') ? 'selected' : ''; ?>>Officer</option>
                    </select><br>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>"><br>

            <label for="dob">Date of Birth:</label>
            <input class="datedata" type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" onchange="calculateAge()"><br>

            <label for="age">Age:</label>
            <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>" readonly><br>


            <label for="doj">Date of Joining:</label>
            <input class="datedata" type="date" id="doj" name="doj" value="<?php echo htmlspecialchars($doj); ?>"><br>

             <label for="service">Service Duration:</label>
            <input type="text" id="service" name="service" value="<?php echo htmlspecialchars($service); ?>" readonly><br>


            <label for="unit">Unit:</label>
<select name="unit" class="textfeild">
    <option value="Unit" <?php echo ($unit == 'Unit') ? 'selected' : ''; ?>>Select Unit</option>
    <option value="SHQ DBR" <?php echo ($unit == 'SHQ DBR') ? 'selected' : ''; ?>>SHQ DBR</option>
    <option value="Water Wing" <?php echo ($unit == 'Water Wing') ? 'selected' : ''; ?>>Water Wing</option>
    <option value="19 BN BSF" <?php echo ($unit == '19 BN BSF') ? 'selected' : ''; ?>>19 BN BSF</option>
    <option value="31 BN BSF" <?php echo ($unit == '31 BN BSF') ? 'selected' : ''; ?>>31 BN BSF</option>
    <option value="45 BN BSF" <?php echo ($unit == '45 BN BSF') ? 'selected' : ''; ?>>45 BN BSF</option>
    <option value="49 BN BSF" <?php echo ($unit == '49 BN BSF') ? 'selected' : ''; ?>>49 BN BSF</option>
</select><br>



           <label>AME Details</label>
<select name="ame" id="ame" class="textfeild" onchange="toggleAMEFields()">
    <option value="Select AME Details" <?php echo ($ame == 'Select AME Details') ? 'selected' : ''; ?>>Select AME Details</option>
    <option value="Done" <?php echo ($ame == 'Done') ? 'selected' : ''; ?>>Done</option>
    <option value="Not Done" <?php echo ($ame == 'Not Done') ? 'selected' : ''; ?>>Not Done</option>
</select><br>
            <div id="ame-fields" style="display: none;">
            <label for="ame_date">AME Date:</label>
            <input class="datedata" type="date" id="ame_date" name="ame_date" value="<?php echo ($ame_date != '0000-00-00') ? htmlspecialchars($ame_date) : ''; ?>"><br>

        
            <label>Details for Category</label>
            <?php
            $categories = ['S', 'H', 'A', 'P', 'E', 'G'];
            foreach ($categories as $index => $cat) {
            $selectedCategory1 = isset($category1[$index]) ? $category1[$index] : '';
            echo "<select class='text' name='category1[]' required>";
            echo "<option value='$cat'>$cat</option>";
            for ($i = 1; $i <= 5; $i++) {
            $selected = ($selectedCategory1 == "$cat$i") ? 'selected' : '';
            echo "<option value='$cat$i' $selected>$cat$i</option>";
            }
            echo "</select>";
            }
            ?>
            <br>
            </div>

            <label for="lmc">LMC:</label>
    <select id="lmc" name="lmc" onchange="toggleLMCFields()">
         <option value="Select LMC Details" <?php echo ($ame == 'Select LMC Details') ? 'selected' : ''; ?>>Select LMC Details</option>
        <option value="Yes" <?php if ($lmc == 'Yes') echo 'selected'; ?>>Yes</option>
        <option value="No" <?php if ($lmc == 'No') echo 'selected'; ?>>No</option>
    </select><br>

    <div id="lmc-fields" <?php if ($lmc == 'No') echo 'style="display:none;"'; ?>>
        <label for="lmc_date">LMC Date:</label>
        <input class="datedata" type="date" id="lmc_date" name="lmc_date" value="<?php echo htmlspecialchars($lmc_date); ?>" onchange="calculateDueDate()"><br>

        <label><input type="radio" name="due_date_option" value="weeks" checked onchange="handleDueDateOptionChange()"> Duration in Weeks</label>
<label><input type="radio" name="due_date_option" value="2years" onchange="handleDueDateOptionChange()"> Duration in 2 Years</label><br>

<label for="duration">Duration:</label>
<input type="number" id="duration" name="duration" value="<?php echo htmlspecialchars($duration); ?>" onchange="calculateDueDate()"><br>

<label for="due_date">Due Date:</label>
<input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>" readonly><br>
<input type="hidden" id="hidden_duration_option" name="hidden_duration_option" value="<?php echo htmlspecialchars($duration); ?>" >

    
        
                <label for="percent">Percentage of Disability:</label>
                <input type="number" id="percent" name="percent" value="<?php echo htmlspecialchars($percent); ?>"><br>

                <label>Details for Category (After LMC)</label>
                    <?php
                    foreach ($categories as $index => $cat) {
                        $selectedCategory2 = isset($category2[$index]) ? $category2[$index] : '';
                        echo "<select class='text' name='category2[]'>";
                        echo "<option value='$cat'>$cat</option>";
                        for ($i = 1; $i <= 5; $i++) {
                            $selected = ($selectedCategory2 == "$cat$i") ? 'selected' : '';
                            echo "<option value='$cat$i' $selected>$cat$i</option>";
                        }
                        echo "</select>";
                    }
                    ?><br>

                <label for="diseases">Diseases/Diagnosis:</label>
                <input type="text" id="diseases" name="diseases" value="<?php echo htmlspecialchars($diseases); ?>"><br>
            </div>
            
            <input type="submit" name="save" value="Save">
            <input type="submit" name="searchdata" value="Search">
            <input type="submit" name="modify" value="Modify">
            <input type="submit" name="clear" value="Clear">
            <input type="submit" name="delete" value="Delete"> 
            <a href="landing.php" class="back-button">List</a> 
            <a href="index.php" class="back-button">Home</a>
        </form>
    </div>
     
</html>