<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

$empID = $_SESSION['empID'];


// Define variables and initialize with empty values. 
$employeetypeName = "";
$employeetypeName_err = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate employeetypeName 
    if (empty(trim($_POST['employeetypeName']))) {
        $employeetypeName_err = "Please enter a Employee Type Name.";
    } else {
        $employeetypeName = trim($_POST['employeetypeName']);
    }

    if (empty($employeetypeName_err)) {
        // Insert into database
        $sql = 'INSERT INTO employeetype (EmployeeTypeName) VALUES (?)';

        if ($stmt = $mysql_db->prepare($sql)) {
            // Bind paramaters to the prepared statement
            $stmt->bind_param("s", $param_employeetypeName);

            // Set parameter 
            $param_employeetypeName = $employeetypeName;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // success message
                echo "<script>alert('Employee Type successfully added!`');</script>";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close the statement
            $stmt->close();

        }

    }
}

// Fetch data from database
$sqls = 'SELECT * FROM employeetype';
$data = mysqli_query($mysql_db, $sqls);

// Deleting the databases.
if (isset($_GET['id'])) {
    $employeetypeID = $_GET['id'];

    // Prepare the delete sql query.
    $sql2 = "DELETE FROM employeetype WHERE EmployeeTypeID = ?";

    if ($stmt = $mysql_db->prepare($sql2)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("i", $param_employeetypeID);

        // Set parameters
        $param_employeetypeID = $employeetypeID;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>
            alert('Employee Type successfully deleted!');
            window.location.href = 'employeetypePage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to delete the employee type.";
    }
}

// Fetch employee records.
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
                $empRole = $row['RoleName'];

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
    <title>Employee Type</title>
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
                <a href="homeAdminPage.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material-rounded/24/home.png" alt="home" />
                    Home</a>
            </li>
            <li>
                <a href="displayEmployeePage.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employees</a>
            </li>
            <li>
                <a href="displayLeaveRequestPage.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Request</a>
            </li>
            <li>
                <a href="rolePage.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Role</a>
            </li>
            <li>
                <a href="positionPage.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Position</a>
            </li>
            <li>
                <a href="employeetypePage.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employee Type</a>
            </li>
            <li>
                <a href="leavetypePage.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Type</a>
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

        <!-- Employee Type form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Employee Type</legend>

                    <div id="left">
                        <!-- Employee Type name -->
                        <div class="gap">
                            <input required type="text" name="employeetypeName"
                                placeholder="Enter Employee Type Name" />
                            <span id="employeetypeName"></span>
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

        <!-- Table form (retrieve value)  -->
        <div id="Form-table">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Employee Type Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter variable
                    if (mysqli_num_rows($data) > 0) {

                        while ($employeetypeRow = mysqli_fetch_assoc($data)) {
                            ?>
                            <tr>
                                <td><?php echo $counter++; # Display the counter and increment it. ?></td>
                                <td><?php echo htmlspecialchars($employeetypeRow['EmployeeTypeName']); ?></td>
                                <td>
                                    <a
                                        href="update/editEmployeetype.php?id=<?php echo $employeetypeRow['EmployeeTypeID']; ?>">Edit</a>
                                    |
                                    <a href="employeetypePage.php?id=<?php echo $employeetypeRow['EmployeeTypeID']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this employee type?');">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='3'>No employee types found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="greet.js">

        function redirect() {
            window.location.href = 'AdminEmployeeList.html'; // Replace with your target URL
        }
    </script>
</body>

</html>