<?php

// Initialize sessions
session_start();

// Include config file
require_once "../../database/config.php";

// Define variables and initialize with empty values. 
$leaveEntitlementID = $leaveType = $empType = $leaveDay = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate Leave Entitlement
    $leaveType = trim($_POST['leavetypeName']);
    $empType = trim($_POST['employeetypeName']);
    $leaveDay = trim($_POST['leaveDay']);
    $leaveEntitlementID = trim($_POST['leaveEntitlementID']);

    // Insert into leave entitlement table.
    $sql = "UPDATE leaveentitlement
            SET LeaveTypeID = ?, EmployeeTypeID = ? , LeaveEntitlementDay = ? 
            WHERE LeaveEntitlementID = ?";

    if ($stmt = $mysql_db->prepare($sql)) {

        // Bind paramaters to the prepared statement
        $stmt->bind_param(
            "iisi",
            $leaveType,
            $empType,
            $leaveDay,
            $leaveEntitlementID
        );

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>alert('Leave Entitlement data successfully updated!`');
            window.location.href = 'leaveEntitlementPage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();

    }
}


// Fetch employee type values.
$empTypeSql = "SELECT *   
               FROM employeetype;";
$empTypeData = mysqli_query($mysql_db, $empTypeSql);

// Fetch leave type values.
$leaveTypeSql = "SELECT *   
               FROM leavetype;";
$leaveTypeData = mysqli_query($mysql_db, $leaveTypeSql);

// Deleting the databases.
if (isset($_GET['id'])) {
    $LeaveEntitlementID = $_GET['id'];

    // Fetch data from leave Entitlement
    $leaveEntitlementSql = "SELECT *
                            FROM leaveentitlement
                            INNER JOIN leavetype ON leaveentitlement.LeaveTypeID = leavetype.LeaveTypeID
                            INNER JOIN employeetype ON leaveentitlement.EmployeeTypeID = employeetype.EmployeeTypeID
                            WHERE LeaveEntitlementID = ? ";


    if ($stmt = $mysql_db->prepare($leaveEntitlementSql)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("i", $LeaveEntitlementID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $leaveEntitlementID = $row['LeaveEntitlementID'];
                $leavetypeID = $row['LeaveTypeID'];
                $leaveTypeName = $row['LeaveTypeName'];
                $empTypeID = $row['EmployeeTypeID'];
                $empTypeName = $row['EmployeeTypeName'];
                $leaveDay = $row['LeaveEntitlementDay'];
            } else {
                echo "Leave Entitlement not found.";
                exit;
            }
        } else {
            echo "Failed to delete the leave entitlement.";
        }

        // Close the statement
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
    <link rel="stylesheet" href="../../assets/css/nav.css" media="screen" />
    <link rel="stylesheet" href="../../assets/css/table.css" media="screen" />
    <link rel="stylesheet" href="../../assets/css/form.css" media="screen" />
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

        <!-- Leave Type form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Leave Type</legend>

                    <div id="left">

                        <!-- Leave En ID -->
                        <div class="gap">
                            <input type="hidden" name="leaveEntitlementID"
                                value="<?php echo htmlspecialchars($leaveEntitlementID); ?>" />
                        </div>

                        <!-- Leave Name FETCH leavetypeID -->
                        <div class="gap">
                            <label for="leavetypeName">Leave Type</label>
                            <select name="leavetypeName">
                                <option value="<?php echo htmlspecialchars($leavetypeID); ?>" selected>
                                    <?php echo htmlspecialchars($leaveTypeName); ?>
                                </option>
                                <!-- Emp Name Option -->
                                <?php
                                while ($row = mysqli_fetch_array($leaveTypeData)) {
                                    ?>
                                    <option value="<?php echo htmlspecialchars($row['LeaveTypeID']); ?>">
                                        <?php echo htmlspecialchars($row['LeaveTypeName']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Emp Type FETCH employeeTypeID -->
                        <div class="gap">
                            <label for="employeetypeID">Employee Type</label>
                            <select name="employeetypeName">
                                <option value="<?php echo htmlspecialchars($empTypeID); ?>" selected>
                                    <?php echo htmlspecialchars($empTypeName); ?>
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

                        <!-- Leave Day -->
                        <div class="gap">
                            <label for="leaveDay">Leave Day</label>
                            <input required type="text" name="leaveDay"
                                value="<?php echo htmlspecialchars($leaveDay); ?>" />
                        </div>

                        <!-- submit button -->
                        <div class="gap">
                            <input name="submit" type="submit" value="submit" />
                            <span id="ic"></span>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</body>

</html>