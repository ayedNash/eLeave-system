<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// Define variables and initialize with empty values. 
$empID = $empName = $empRole = $empType = $empPosition = $empPassword = $empJoinDate = $empAnnLeave = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate personal details fields
    $empID = trim($_POST['empID']);
    $empRole = trim($_POST['roleName']);
    $empType = trim($_POST['employeetypeName']);
    $empPosition = trim($_POST['positionName']);
    $empName = trim($_POST['personalName']);
    $empPassword = trim($_POST['employeePassword']);
    $empJoinDate = trim($_POST['employeeJoinDate']);
    $empAnnLeave = trim($_POST['employeeAnnLvBal']);

    // Hash the password
    $hashedPassword = password_hash($empPassword, PASSWORD_BCRYPT);
    
    // Insert into employee table.
    $sql = "INSERT INTO employee
            (EmployeeID, RoleID, EmployeeTypeID, PositionID, PersonalDetailsID, EmployeePassword, EmployeeJoinDate, EmployeeAnnLeaveBalance) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $mysql_db->prepare($sql)) {

        // Bind paramaters to the prepared statement
        $stmt->bind_param(
            "siiiissi",
            $empID,
            $empRole,
            $empType,
            $empPosition,
            $empName, 
            $hashedPassword,
            $empJoinDate,
            $empAnnLeave
        );

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>alert('Employee data  successfully added!`');
            window.location.href = 'displayEmployeePage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();

    }

}

// Fetch personal details values.
$personalSql = "SELECT *   
         FROM personaldetails;";
$personalData = mysqli_query($mysql_db, $personalSql);

// Fetch role values.
$roleSql = "SELECT *   
         FROM role;";
$roleData = mysqli_query($mysql_db, $roleSql);

// Fetch employee type values.
$empTypeSql = "SELECT *   
         FROM employeetype;";
$empTypeData = mysqli_query($mysql_db, $empTypeSql);

// Fetch position values.
$positionSql = "SELECT *   
         FROM position;";
$positionData = mysqli_query($mysql_db, $positionSql);


// Close the connection
$mysql_db->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>eLeave</title>
    <style>
        button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #0026ff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #04222a;
        }
    </style>
    <link rel="stylesheet" href="../assets/css/nav.css" media="screen" />
    <link rel="stylesheet" href="../assets/css/table.css" media="screen" />
    <link rel="stylesheet" href="../assets/css/form.css" media="screen" />
</head>

<body>
    <div class="nav">
        <h2>nav</h2>
        <ul>
            <li>
                <a href="#"><img src="https://img.icons8.com/material-rounded/24/home.png" alt="home" />
                    Home</a>
            </li>
            <li>
                <a href="displayPersonaldetailPage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employees</a>
            </li>
            <li>
                <a href="#"><img src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Request</a>
            </li>
            <li>
                <a href="rolePage.php"><img src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Role</a>
            </li>
            <li>
                <a href="positionPage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Position</a>
            </li>
            <li>
                <a href="employeetypePage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employee Type</a>
            </li>
            <li>
                <a href="leavetypePage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Type</a>
            </li>
            <li>
                <a href="#"><img src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Entitlement</a>
            </li>
        </ul>
    </div>

    <div class="mainContentList">
        <header id="adminHeader">
            <div id="left">
                <h1>Good Afternoon, Admin</h1>
            </div>

            <!-- <div id="right">
                <button onclick="redirect()">Back</button>

            </div> -->
            <!-- Logout Button -->
            <form action="../logout.php" method="POST">
                <button type="submit" onclick="return confirm('Logout?');">Logout</button>
            </form>
        </header>

        <!-- Employee form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Employee Form</legend>

                    <div id="left">
                        <!-- Emp ID -->
                        <div class="gap">
                            <label for="empID">Employee ID</label>
                            <input required type="text" name="empID" placeholder="Enter Employee ID" />
                        </div>

                        <!-- Emp Name FETCH personalID -->
                        <div class="gap">
                            <label for="personalName">Employee Name</label>
                            <select name="personalName" required>
                                <option value="" disabled selected>Select Employee Name</option>
                                <!-- Emp Name Option -->
                                <?php
                                while ($row = mysqli_fetch_array($personalData)) {
                                    ?>
                                    <option value="<?php echo htmlspecialchars($row['PersonalDetailsID']); ?>">
                                        <?php echo htmlspecialchars($row['PersonalName']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Emp Role FETCH roleID -->
                        <div class="gap">
                            <label for="roleName">Role</label>
                            <select name="roleName" required>
                                <option value="" disabled selected>Select Role</option>
                                <!-- Role Name Option -->
                                <?php
                                while ($row = mysqli_fetch_array($roleData)) {
                                    ?>
                                    <option value="<?php echo htmlspecialchars($row['RoleID']); ?>">
                                        <?php echo htmlspecialchars($row['RoleName']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Emp Type FETCH employeeTypeID -->
                        <div class="gap">
                            <label for="employeetypeID">Employee Type</label>
                            <select name="employeetypeName" required>
                                <option value="" disabled selected>Select Employee Type</option>
                                <!-- Emp Type Option -->
                                <?php
                                while ($row = mysqli_fetch_array($empTypeData)) {
                                    ?>
                                    <option value="<?php echo htmlspecialchars($row['EmployeeTypeID']); ?>">
                                        <?php echo htmlspecialchars($row['EmployeeTypeName']); ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <!-- Emp Position FETCH positionID -->
                        <div class="gap">
                            <label for="positionID">Employee Position</label>
                            <select name="positionName" required>
                                <option value="" disabled selected>Select Employee Position</option>
                                <!-- Emp Type Option -->
                                <?php
                                while ($row = mysqli_fetch_array($positionData)) {
                                    ?>
                                    <option value="<?php echo htmlspecialchars($row['PositionID']); ?>">
                                        <?php echo htmlspecialchars($row['PositionCategory']); ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <!-- Emp Password -->
                        <div class="gap">
                            <label for="employeePass">Employee Password</label>
                            <input required type="password" name="employeePassword"
                                placeholder="Enter Employee Password" />
                        </div>

                        <!-- Emp Join Date -->
                        <div class="gap">
                            <label for="employeeJoinDate">Emp Join Date</label>
                            <input required type="date" name="employeeJoinDate" placeholder=" Employee Join Date" />
                        </div>

                        <!-- Emp Ann Leave Balance -->
                        <div class="gap">
                            <label for="employeeAnnLvBal">Employee Annual Leave Balance</label>
                            <input required type="text" name="employeeAnnLvBal"
                                placeholder=" Employee Annual Leave Balance" />
                        </div>

                        <!-- submit button -->
                        <div class="gap">
                            <input name="submit" type="submit" value="submit" />
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>