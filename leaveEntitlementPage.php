<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// Define variables and initialize with empty values. 
$leaveType = $empType = $leaveDay = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate Leave Entitlement
    $leaveType = trim($_POST['leavetypeName']);
    $empType = trim($_POST['employeetypeName']);
    $leaveDay = trim($_POST['leaveDay']);

    // Insert into leave entitlement table.
    $sql = "INSERT INTO leaveentitlement
            (LeaveTypeID, EmployeeTypeID, LeaveEntitlementDay) 
            VALUES (?, ?, ?)";

    if ($stmt = $mysql_db->prepare($sql)) {

        // Bind paramaters to the prepared statement
        $stmt->bind_param(
            "iis",
            $leaveType,
            $empType,
            $leaveDay
        );

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>alert('Leave Entitlement data successfully added!`');
            window.location.href = 'leaveEntitlementPage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();

    }
}

// Fetch data from leave Entitlement
$leaveEntitlementSql = "SELECT LeaveEntitlementID, LeaveTypeName, EmployeeTypeName,           LeaveEntitlementDay
                        FROM leaveentitlement
                        INNER JOIN leavetype ON leaveentitlement.LeaveTypeID = leavetype.LeaveTypeID
                        INNER JOIN employeetype ON leaveentitlement.EmployeeTypeID = employeetype.EmployeeTypeID";

$leaveEntitlementData = mysqli_query($mysql_db, $leaveEntitlementSql);

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

    // Prepare the delete sql query.
    $sql2 = "DELETE FROM leaveentitlement WHERE LeaveEntitlementID = ?";

    if ($stmt = $mysql_db->prepare($sql2)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("i", $LeaveEntitlementID);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>
            alert('Leave Entitlement successfully deleted!');
            window.location.href = 'leaveEntitlementPage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to delete the leave type.";
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
                        <!-- Leave Name FETCH leavetypeID -->
                        <div class="gap">
                            <label for="leavetypeName">Employee Name</label>
                            <select name="leavetypeName">
                                <option value="" disabled selected>Select Leave Name</option>
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


                        <!-- Leave Day -->
                        <div class="gap">
                            <label for="leaveDay">Leave Day</label>
                            <input required type="text" name="leaveDay" placeholder="Enter leave day " />
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

        <!-- Leave type form (retrieve value)  -->
        <div id="roleForm-table">
            <h1>Leave Type Data</h1>
            <table class="data-table">
                <caption class="title">Leave Type Entitlement Data</caption>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Leave Type Name</th>
                        <th>Employee Type</th>
                        <th>Leave Day</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter variable
                    # $data->data_seek(0); // Reset data pointer for reuse the $data variable
                    if (mysqli_num_rows($leaveEntitlementData) > 0) {

                        while ($leaveentitlementRow = mysqli_fetch_assoc($leaveEntitlementData)) {
                            ?>
                            <tr>
                                <td><?php echo $counter++; # Display the counter and increment it. ?></td>
                                <td><?php echo htmlspecialchars($leaveentitlementRow['LeaveTypeName']); ?></td>
                                <td><?php echo htmlspecialchars($leaveentitlementRow['EmployeeTypeName']); ?></td>
                                <td><?php echo htmlspecialchars($leaveentitlementRow['LeaveEntitlementDay']); ?></td>
                                <td>
                                    <a
                                        href="update/editLeaveEntitlement.php?id=<?php echo $leaveentitlementRow['LeaveEntitlementID']; ?>">Edit</a>
                                    |
                                    <a href="leaveEntitlementPage.php?id=<?php echo $leaveentitlementRow['LeaveEntitlementID']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this role?');">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='3'>No roles found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>