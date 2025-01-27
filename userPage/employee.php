<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

$empID = $_SESSION['empID'];

// Define variables and initialize with empty values. 
$empID = $empName = $empRole = $empType = $empPosition = $empPassword = $empJoinDate = $empAnnLeave = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate employee details fields
    $empID = trim($_POST['empID']);
    $empRole = trim($_POST['roleName']);
    $empType = trim($_POST['employeetypeName']);
    $empPosition = trim($_POST['positionName']);
    $empPassword = trim($_POST['employeePassword']);
    $empJoinDate = trim($_POST['employeeJoinDate']);

    // Hash the password
    $hashedPassword = password_hash($empPassword, PASSWORD_BCRYPT);

    // Update values in employee details
    $sql = "UPDATE employee 
            SET EmployeeID = ?, RoleID = ?, EmployeeTypeID = ?, PositionID = ?, EmployeePassword = ?, EmployeeJoinDate = ?
            WHERE EmployeeID = ?";

    if ($stmt = $mysql_db->prepare($sql)) {

        // Bind paramaters to the prepared statement
        $stmt->bind_param(
            "siiisss",
            $empID,
            $empRole,
            $empType,
            $empPosition,
            $hashedPassword,
            $empJoinDate,
            $empID
        );

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>alert('Employee data successfully added!`');
              window.location.href = 'employee.php?empID=' + encodeURIComponent('$empID');
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


// Check if editing a employee
if (isset($_GET['empID'])) {
    $empID = $_GET['empID'];

    // Fetch employee details
    $sql2 = "SELECT EmployeeID, personaldetails.PersonalDetailsID, PersonalName, role.RoleID, RoleName, employeetype.EmployeeTypeID, EmployeeTypeName, position.PositionID, PositionCategory, EmployeePassword, EmployeeJoinDate    
         FROM employee
         LEFT JOIN personaldetails ON employee.PersonalDetailsID = personaldetails.PersonalDetailsID
         LEFT JOIN role ON employee.RoleID = role.RoleID
         LEFT JOIN employeetype ON employee.EmployeeTypeID = employeetype.EmployeeTypeID
         LEFT JOIN position ON employee.PositionID = position.PositionID
         WHERE EmployeeID = ? ";


    if ($stmt = $mysql_db->prepare($sql2)) {
        $stmt->bind_param("s", $empID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $empID = $row['EmployeeID'];
                $empPersonalID = $row['PersonalDetailsID'];
                $empName = $row['PersonalName'];
                $roleID = $row['RoleID'];
                $empRole = $row['RoleName'];
                $empTypeID = $row['EmployeeTypeID'];
                $empType = $row['EmployeeTypeName'];
                $positionID = $row['PositionID'];
                $empPosition = $row['PositionCategory'];
                $empPassword = $row['EmployeePassword'];
                $empJoinDate = $row['EmployeeJoinDate'];
            } else {
                echo "Employee Details not found.";
                exit;
            }
        } else {
            echo "Error fetching employee details.";
            exit;
        }

        $stmt->close();
    }
}

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
                <a href="#"><img src="https://img.icons8.com/material-rounded/24/home.png" />
                    Home</a>
            </li>
            <li>
                <a href="employee.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png" />Employees</a>
            </li>
            <li>
                <a href="profile.php?empID=<?php echo htmlspecialchars($empID) ?>">
                    <img src="https://img.icons8.com/material/24/conference-background-selected.png" />Profile</a>
            </li>
            <li>
                <a href="displayLeaveRequest.php?empID=<?php echo htmlspecialchars($empID) ?>">
                    <img src="https://img.icons8.com/material/24/conference-background-selected.png" />Leave Request</a>
            </li>
        </ul>
    </div>

    <div class="mainContentList">
        <header id="adminHeader">
            <div id="left">
                <h1>Good Afternoon, <?php echo htmlspecialchars($empName) . " (" .
                    htmlspecialchars($empRole) . ")"; ?></h1>
            </div>

            <!-- <div id="right">
                <button onclick="redirect()">Back</button>

            </div> -->
            <!-- Logout Button -->
            <form action="../logout.php" method="POST">
                <button type="submit" onclick="return confirm('Logout?');">Logout</button>
            </form>
        </header>

        <!-- Role form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Employee Form</legend>

                    <div id="left">
                        <!-- Emp ID -->
                        <div class="gap">
                            <label for="empID">Employee ID</label>
                            <input readonly type="text" name="empID" value="<?php echo htmlspecialchars($empID); ?>" />
                        </div>

                        <!-- Emp Name Readonly -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($empName); ?>" />
                        </div>

                        <!-- Emp Role FETCH roleID -->
                        <div class="gap">
                            <label for="roleName">Role</label>
                            <select name="roleName">
                                <option value="<?php echo htmlspecialchars($roleID); ?>" selected>
                                    <?php echo htmlspecialchars($empRole); ?>
                                </option>
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
                            <select name="employeetypeName">
                                <option value="<?php echo htmlspecialchars($empTypeID); ?>" selected>
                                    <?php echo htmlspecialchars($empType); ?>
                                </option>
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
                            <select name="positionName">
                                <option value="<?php echo htmlspecialchars($positionID); ?>" selected>
                                    <?php echo htmlspecialchars($empPosition); ?>
                                </option>
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
                            <input type="password" name="employeePassword"
                                value="<?php echo htmlspecialchars($empPassword); ?>" />
                        </div>

                        <!-- Emp Join Date -->
                        <div class="gap">
                            <label for="employeeJoinDate">Emp Join Date</label>
                            <input required type="date" name="employeeJoinDate"
                                value="<?php echo htmlspecialchars($empJoinDate); ?>" />
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