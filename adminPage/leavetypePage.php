<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// Define variables and initialize with empty values. 
$leavetypeName = "";
$leavetypeName_err = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate leavetype 
    if (empty(trim($_POST['leavetypeName']))) {
        $leavetypeName_err = "Please enter a Leave Type Name.";
    } else {
        $leavetypeName = trim($_POST['leavetypeName']);
    }

    if (empty($leavetypeName_err)) {
        // Insert into database
        $sql = 'INSERT INTO leavetype (LeaveTypeName) VALUES (?)';

        if ($stmt = $mysql_db->prepare($sql)) {
            // Bind paramaters to the prepared statement
            $stmt->bind_param("s", $param_leavetypeName);

            // Set parameter 
            $param_leavetypeName = $leavetypeName;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // success message
                echo "<script>alert('Leave Type successfully added!');</script>";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close the statement
            $stmt->close();

        }

    }
}

// Fetch data from database
$sqls = 'SELECT * FROM leavetype';
$data = mysqli_query($mysql_db, $sqls);

// Deleting the databases.
if (isset($_GET['id'])) {
    $leavetypeID = $_GET['id'];

    // Prepare the delete sql query.
    $sql2 = "DELETE FROM leavetype WHERE LeaveTypeID = ?";

    if ($stmt = $mysql_db->prepare($sql2)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("i", $param_leavetypeID);

        // Set parameters
        $param_leavetypeID = $leavetypeID;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>
            alert('Leave Tyoe successfully deleted!');
            window.location.href = 'leavetypePage.php';
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
                <a href="#"><img src="https://img.icons8.com/material-rounded/24/home.png"
                        alt="home" />
                    Home</a>
            </li>
            <li>
                <a href="displayPersonaldetailPage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employees</a>
            </li>
            <li>
                <a href="#"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
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
                <a href="#"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
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
                        <!-- Leave Type name -->
                        <div class="gap">
                            <label for="leavetype">Leave Type Name</label>
                            <input required type="text" name="leavetypeName" placeholder="Enter leave type name" />
                            <span id="leavetypeName"></span>
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
                <caption class="title">Leave Type data</caption>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Leave Type Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter variable
                    # $data->data_seek(0); // Reset data pointer for reuse the $data variable
                    if (mysqli_num_rows($data) > 0) {

                        while ($leavetypeRow = mysqli_fetch_assoc($data)) {
                            ?>
                            <tr>
                                <td><?php echo $counter++; # Display the counter and increment it. ?></td>
                                <td><?php echo htmlspecialchars($leavetypeRow['LeaveTypeName']); ?></td>
                                <td>
                                    <a href="update/editLeavetype.php?id=<?php echo $leavetypeRow['LeaveTypeID']; ?>">Edit</a> |
                                    <a href="leavetypePage.php?id=<?php echo $leavetypeRow['LeaveTypeID']; ?>"
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

    <script src="greet.js">

        function redirect() {
            window.location.href = 'AdminEmployeeList.html'; // Replace with your target URL
        }
    </script>
</body>

</html>