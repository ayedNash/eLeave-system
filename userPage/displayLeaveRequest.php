<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// called emp ID
$empID = $_SESSION['empID'];

// display leave application based on user requested.
if (isset($_GET['empID'])) {
    $empID = $_GET['empID'];

    // Fetch leave application details
    $sql2 = "SELECT LeaveApplicationID, employee.EmployeeID, personaldetails.PersonalName, LeaveTypeName, LeaveStartDate, LeaveEndDate, LeavePeriod, LeaveStatus
             FROM leaveapplication
             INNER JOIN employee ON leaveapplication.EmployeeID = employee.EmployeeID
             INNER JOIN leavetype ON leaveapplication.LeaveTypeID = leavetype.LeaveTypeID
             INNER JOIN personaldetails ON employee.PersonalDetailsID = personaldetails.PersonalDetailsID
             WHERE employee.EmployeeID = ?;";

    if ($stmt = $mysql_db->prepare($sql2)) {

        // Bind parameters
        $stmt->bind_param("i", $empID);

        // Execute statement
        $stmt->execute();

        // Get the result
        $data = $stmt->get_result();

        // Close statement
        $stmt->close();
    } else {
        echo "Error: Unable to prepare SQL statement.";
    }


    // Fetch personal name
    $sql2 = "SELECT PersonalName FROM personaldetails 
        Where PersonalDetailsID = ?";

    if ($stmt = $mysql_db->prepare($sql2)) {
        $stmt->bind_param("i", $empID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $personalName = $row['PersonalName'];
            } else {
                echo "Personal Details not found.";
                exit;
            }
        } else {
            echo "Error fetching personal details.";
            exit;
        }

        $stmt->close();
    }


}


// Fetch data from database Leave Application
// $sqls = "SELECT LeaveApplicationID, employee.EmployeeID, personaldetails.PersonalName, LeaveTypeName, LeaveStartDate, LeaveEndDate, LeavePeriod
//          FROM leaveapplication
//          INNER JOIN employee ON leaveapplication.EmployeeID = employee.EmployeeID
//          INNER JOIN leavetype ON leaveapplication.LeaveTypeID = leavetype.LeaveTypeID
//          INNER JOIN personaldetails ON employee.PersonalDetailsID = personaldetails.PersonalDetailsID
//          WHERE EmployeeID = ?;";
// $data = mysqli_query($mysql_db, $sqls);


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
    <link rel="stylesheet" href="../assets/css/nav2.css" media="screen" />
    <link rel="stylesheet" href="../assets/css/table2.css" media="screen" />
    <link rel="stylesheet" href="../assets/css/form2.css" media="screen" />
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
                <h1>Good Afternoon, <?php echo htmlspecialchars($personalName); ?></h1>
            </div>

            <!-- <div id="right">
                <button onclick="redirect()">Back</button>

            </div> -->
            <!-- Logout Button -->
            <form action="../logout.php" method="POST">
                <button type="submit" onclick="return confirm('Logout?');">Logout</button>
            </form>
        </header>

        <!-- Table form (retrieve value)  -->
        <!-- Button to Add Leave Request -->
        <div>
            <button name="leaveRequest"><a href="leaveRequest.php?empID=<?php echo htmlspecialchars($empID) ?>">Add Leave Request + </a></button>
        </div>

        <div id="Form-table">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Leave Type</th>
                        <th>Leave Start Date</th>
                        <th>Leave End Date</th>
                        <th>Leave Period</th>
                        <th>Leave Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter variable
                    // $data->data_seek(0); // Reset data pointer for reuse the $data variable
                    if (mysqli_num_rows($data) > 0) {

                        while ($row = mysqli_fetch_assoc($data)) {
                            ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo htmlspecialchars($row["LeaveTypeName"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LeaveStartDate"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LeaveEndDate"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LeavePeriod"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LeaveStatus"]); ?></td>
                                <td>
                                    <a href="viewLeaveRequest.php?leaveRequestID=<?php echo $row['LeaveApplicationID']; ?>">View</a>
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