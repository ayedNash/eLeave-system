<?php

// Initialize sessions
session_start();

// Include config file
require_once "../../database/config.php";

$empID = $_SESSION['empID'];

// Process submitted approved or reject button.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // validate leave application id, approve or reject button clicked.
    $leaveApplicationID = trim($_POST['leaveapplicationID']);

    if (isset($_POST['approve'])) {
        $LeaveStatus = 'Approved';
    } elseif (isset($_POST['reject'])) {
        $LeaveStatus = 'Rejected';
    }

    // Update the leave status in the table
    if (isset($LeaveStatus)) {
        $sql = "UPDATE leaveapplication 
                SET LeaveStatus = ? 
                WHERE LeaveApplicationID = ?";

        if ($stmt = $mysql_db->prepare($sql)) {
            $stmt->bind_param("si", $LeaveStatus, $leaveApplicationID);

            if ($stmt->execute()) {
                echo "<script>
                      alert('Leave status updated to $LeaveStatus successfully!');
                      window.location.href = '../displayLeaveRequestPage.php'; // Redirect to the leave request page
                      </script>";
            } else {
                echo "Error updating leave status.";
            }

            $stmt->close();
        }
    }
}

// display leave application based on user requested.
if (isset($_GET['leaveRequestID'])) {
    $leaveApplicationID = $_GET['leaveRequestID'];

    // Fetch personal name
    $sql2 = "SELECT LeaveApplicationID, employee.EmployeeID, personaldetails.PersonalName, LeaveTypeName, LeaveStartDate, LeaveEndDate, LeavePeriod, LeaveStatus, LeaveReason, LeaveAttachment
             FROM leaveapplication
             INNER JOIN employee ON leaveapplication.EmployeeID = employee.EmployeeID
             INNER JOIN leavetype ON leaveapplication.LeaveTypeID = leavetype.LeaveTypeID
             INNER JOIN personaldetails ON employee.PersonalDetailsID = personaldetails.PersonalDetailsID
             WHERE LeaveApplicationID = ?;";

    if ($stmt = $mysql_db->prepare($sql2)) {
        $stmt->bind_param("i", $leaveApplicationID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $leaveApplicationID = $row['LeaveApplicationID'];
                $employeeID = $row['EmployeeID'];
                $personalName = $row['PersonalName'];
                $leaveTypeName = $row['LeaveTypeName'];
                $leaveStart = $row['LeaveStartDate'];
                $leaveEnd = $row['LeaveEndDate'];
                $leavePeriod = $row['LeavePeriod'];
                $leaveStatus = $row['LeaveStatus'];
                $leaveReason = $row['LeaveReason'];
                $leaveAttachment = $row['LeaveAttachment'];

            } else {
                echo "Leave Application not found.";
                exit;
            }
        } else {
            echo "Error fetching leave application.";
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
    <link rel="stylesheet" href="../../assets/css/nav.css" media="screen" />
    <link rel="stylesheet" href="../../assets/css/table.css" media="screen" />
    <link rel="stylesheet" href="../../assets/css/form.css" media="screen" />
</head>

    <div class="nav">
        <h2>nav</h2>
        <ul>
            <li>
                <a href="#"><img src="https://img.icons8.com/material-rounded/24/home.png" alt="home" />
                    Home</a>
            </li>
            <li>
                <a href="../displayEmployeePage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employees</a>
            </li>
            <li>
                <a href="../displayLeaveRequestPage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Request</a>
            </li>
            <li>
                <a href="../rolePage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Role</a>
            </li>
            <li>
                <a href="../positionPage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Position</a>
            </li>
            <li>
                <a href="../employeetypePage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employee Type</a>
            </li>
            <li>
                <a href="../leavetypePage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Type</a>
            </li>
        </ul>
    </div>
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

        <!-- Role form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Leave Request </legend>

                    <div id="left">
                        <!-- Leave Application ID  -->
                        <input type="hidden" name="leaveapplicationID"
                            value="<?php echo htmlspecialchars($leaveApplicationID); ?>" />

                        <!-- Em[loyee ID  -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($employeeID); ?>" />
                        </div>
                        <!-- Em[loyee Name  -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($personalName); ?>" />
                        </div>
                        <!-- Leave Type -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($leaveTypeName); ?>" />
                        </div>

                        <!-- Leave Start Date -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($leaveStart); ?>" />
                        </div>

                        <!-- Leave Start Date -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($leaveEnd); ?>" />
                        </div>

                        <!-- Leave Status -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($leaveStatus); ?>" />
                        </div>

                        <!-- Leave Status -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($leaveReason); ?>" />
                        </div>

                        <!-- Leave Period -->
                        <div class="gap">
                            <input readonly type="text" name="personalName"
                                value="<?php echo htmlspecialchars($leavePeriod); ?>" />
                        </div>

                        <!-- Approved button -->
                        <div class="gap">
                            <button type="submit" name="approve"
                                onclick="return confirm('Are you sure you want to approve this request?');">Approve</button>
                        </div>
                        <!-- Rejected button -->
                        <div class="gap">
                            <button type="submit" name="reject"
                                onclick="return confirm('Are you sure you want to reject this request?');">Reject</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    </body>

</html>