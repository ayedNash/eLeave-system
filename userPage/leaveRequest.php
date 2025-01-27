<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

$empID = $_SESSION['empID'];

// Define variables and initialize with empty values. 
$empID = $leaveType = $leaveStart = $leaveEnd = $leaveReason = $leaveAttachment = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate personal details fields
    $empID = trim($_POST['empID']);
    $leaveType = trim($_POST['leavetype']);
    $leaveStart = trim($_POST['startdate']);
    $leaveEnd = trim($_POST['enddate']);
    $leaveReason = trim($_POST['leavereason']);
    $leaveAttachment = trim($_POST['leaveattachment']);

    // Insert into leave application table.
    $sql = "INSERT INTO leaveapplication
            (EmployeeID, LeaveTypeID, LeaveStartDate, LeaveEndDate, LeaveReason, LeaveAttachment) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysql_db->prepare($sql)) {

        // Bind paramaters to the prepared statement
        $stmt->bind_param(
            "sissss",
            $empID,
            $leaveType,
            $leaveStart,
            $leaveEnd,
            $leaveReason,
            $leaveAttachment
        );

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>alert('Leave Request successfully added!`');
            window.location.href = 'displayLeaveRequest.php?empID=' + encodeURIComponent('$empID');
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();

    }

}

// To called employee details.X
if (isset($_GET['empID'])) {
    $empID = $_GET['empID'];

    // Fetch employee details
    $sql2 = "SELECT EmployeeID, personaldetails.PersonalDetailsID, PersonalName   
         FROM employee
         LEFT JOIN personaldetails ON employee.PersonalDetailsID = personaldetails.PersonalDetailsID
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

// Fetch leave type values.
$leaveTypeSql = "SELECT *   
         FROM leavetype;";
$leavetypeData = mysqli_query($mysql_db, $leaveTypeSql);


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
        <h2>e-Leave</h2>
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
                <h1>Good Afternoon, <?php echo htmlspecialchars($empName); ?></h1>
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
                    <legend>leave Request Form</legend>

                    <!-- Emp ID -->
                        <input readonly type="hidden" name="empID" value="<?php echo htmlspecialchars($empID); ?>" />


                    <div id="left">
                        <!-- Leave Type  -->
                        <div class="gap">
                            <label for="leavetype">Leave Type</label>
                            <select name="leavetype" required>
                                <option value="" disabled selected>Select Leave</option>
                                <!-- Leave type Option -->
                                <?php
                                while ($row = mysqli_fetch_array($leavetypeData)) {
                                    ?>
                                    <option value="<?php echo htmlspecialchars($row['LeaveTypeID']); ?>">
                                        <?php echo htmlspecialchars($row['LeaveTypeName']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Leave Start Date -->
                        <div class="gap">
                            <label for="startdate">Start Date</label>
                            <input required type="date" name="startdate" placeholder="Start Date" />
                        </div>

                        <!-- Leave End Date -->
                        <div class="gap">
                            <label for="enddate">End Date</label>
                            <input required type="date" name="enddate" placeholder="End Date" />
                        </div>

                        <!-- Leave Reason -->
                        <div class="gap">
                            <label for="leavereason">Leave Reason</label>
                            <input type="text" name="leavereason" placeholder="Enter Leave Password" />
                        </div>

                        <!-- Leave Attachment -->
                        <div class="gap">
                            <label for="leaveattachment">Leave Attachment</label>
                            <input type="file" name="leaveattachment" placeholder="Enter Leave Attachment" />
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