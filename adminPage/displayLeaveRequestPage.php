<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// Fetch data from database Leave Application
$sqls = "SELECT LeaveApplicationID, employee.EmployeeID, personaldetails.PersonalName, LeaveTypeName, LeaveStartDate, LeaveEndDate, LeavePeriod
         FROM leaveapplication
         INNER JOIN employee ON leaveapplication.EmployeeID = employee.EmployeeID
         INNER JOIN leavetype ON leaveapplication.LeaveTypeID = leavetype.LeaveTypeID
         INNER JOIN personaldetails ON employee.PersonalDetailsID = personaldetails.PersonalDetailsID;";
$data = mysqli_query($mysql_db, $sqls);

// LEFT JOIN keyword returns all records from the left table (table1), and the matching records from the right table (table2). The result is 0 records from the right side, if there is no match

// Deleting the values.
// if (isset($_GET['id'])) {
//     $employeeID = $_GET['id'];

//     // Prepare the delete sql query.
//     $sql2 = "DELETE FROM employee WHERE EmployeeID = ? ";

//     if ($stmt = $mysql_db->prepare($sql2)) {
//         // Bind the parameters to the prepared statement
//         $stmt->bind_param("i", $employeeID);

//         // Attempt to execute the prepared statement
//         if ($stmt->execute()) {
//             // success message
//             echo "<script>
//             alert('Employee successfully deleted!');
//             window.location.href = 'displayEmployeePage.php';
//             </script>";
//         } else {
//             echo "Something went wrong. Please try again later.";
//         }

//         // Close the statement
//         $stmt->close();
//     } else {
//         echo "Failed to delete the employee.";
//     }
// }

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
                <a href="displayEmployeePage.php"><img
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

        <!-- Table form (retrieve value)  -->

        <div>
        </div>
        <div id="personaldetailForm-table">
            <h1>Employee Data</h1>
            <table class="data-table">
                <caption class="title">Leave Request</caption>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Leave Start Date</th>
                        <th>Leave End Date</th>
                        <th>Leave Period</th>
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
                                <td><?php echo htmlspecialchars($row["EmployeeID"]); ?></td>
                                <td><?php echo htmlspecialchars($row["PersonalName"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LeaveTypeName"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LeaveStartDate"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LeaveEndDate"]); ?></td>
                                <td><?php echo htmlspecialchars($row["LeavePeriod"]); ?></td>
                                <td>
                                    <a href="update/viewLeaveRequest.php?id=<?php echo $row['LeaveApplicationID']; ?>">View</a>
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