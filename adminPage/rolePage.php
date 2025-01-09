<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// Define variables and initialize with empty values. 
$roleName = "";
$roleName_err = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate roleName 
    if (empty(trim($_POST['roleName']))) {
        $roleName_err = "Please enter a Role Name.";
    } else {
        $roleName = trim($_POST['roleName']);
    }

    if (empty($roleName_err)) {
        // Insert into database
        $sql = 'INSERT INTO role (RoleName) VALUES (?)';

        if ($stmt = $mysql_db->prepare($sql)) {
            // Bind paramaters to the prepared statement
            $stmt->bind_param("s", $param_roleName);

            // Set parameter 
            $param_roleName = $roleName;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // success message
                echo "<script>alert('Role successfully added!`');</script>";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close the statement
            $stmt->close();

        }

    }
}

// Fetch data from database
$sqls = 'SELECT * FROM role';
$data = mysqli_query($mysql_db, $sqls);

// Deleting the databases.
if (isset($_GET['id'])) {
    $roleID = $_GET['id'];

    // Prepare the delete sql query.
    $sql2 = "DELETE FROM role WHERE RoleID = ?";

    if ($stmt = $mysql_db->prepare($sql2)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("i", $param_roleID);

        // Set parameters
        $param_roleID = $roleID;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>
            alert('Role successfully deleted!');
            window.location.href = 'rolePage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to delete the role.";
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
                <a href="displayEmployeePage.php"><img
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
                <?php
                // Fetch a single role record
                if (mysqli_num_rows($data) > 0) {

                    while ($roleRow = mysqli_fetch_assoc($data)) // Fetch all rows have in the table.
                        if ($roleRow['RoleID'] == 1) {
                            // Display the RoleName based on called role id.
                            $roleName = htmlspecialchars($roleRow['RoleName']);
                        }
                }

                ?>
                <h1>Good Afternoon, <?php echo $roleName ?></h1>
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
                    <legend>Role</legend>

                    <div id="left">
                        <!-- role name -->
                        <div class="gap">
                            <label for="roleName">Role Name</label>
                            <input required type="text" name="roleName" placeholder="Enter role name" />
                            <span id="roleName"></span>
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
        <div id="roleForm-table">
            <h1>Role Data</h1>
            <table class="data-table">
                <caption class="title">Role data</caption>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Role Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter variable
                    $data->data_seek(0); // Reset data pointer for reuse the $data variable
                    if (mysqli_num_rows($data) > 0) {

                        while ($roleRow = mysqli_fetch_assoc($data)) {
                            ?>
                            <tr>
                                <td><?php echo $counter++; # Display the counter and increment it. ?></td>
                                <td><?php echo htmlspecialchars($roleRow['RoleName']); ?></td>
                                <td>
                                    <a href="update/editRole.php?id=<?php echo $roleRow['RoleID']; ?>">Edit</a> |
                                    <a href="rolePage.php?id=<?php echo $roleRow['RoleID']; ?>"
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