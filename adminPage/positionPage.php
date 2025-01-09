<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// Define variables and initialize with empty values. 
$positionCategory = "";
$positionName_err = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate positionName 
    if (empty(trim($_POST['positionCategory']))) {
        $positionName_err = "Please enter a Position Name.";
    } else {
        $positionCategory = trim($_POST['positionCategory']);
    }

    if (empty($positionName_err)) {
        // Insert into database
        $sql = 'INSERT INTO position (PositionCategory) VALUES (?)';

        if ($stmt = $mysql_db->prepare($sql)) {
            // Bind paramaters to the prepared statement
            $stmt->bind_param("s", $param_positionName);

            // Set parameter 
            $param_positionName = $positionCategory;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // success message
                echo "<script>alert('Position successfully added!`');</script>";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close the statement
            $stmt->close();

        }

    }
}

// Fetch data from database
$sqls = 'SELECT * FROM position';
$data = mysqli_query($mysql_db, $sqls);

// Deleting the databases.
if (isset($_GET['id'])) {
    $positionID = $_GET['id'];

    // Prepare the delete sql query.
    $sql2 = "DELETE FROM position WHERE PositionID = ?";

    if ($stmt = $mysql_db->prepare($sql2)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("i", $param_positionID);

        // Set parameters
        $param_positionID = $positionID;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>
            alert('Position successfully deleted!');
            window.location.href = 'positionPage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to delete the position.";
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
    <title>Position</title>
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
                <a href="positionPage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Type</a>
            </li>
            <li>
                <a href="positionPage.php"><img
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

        <!-- Position form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Position</legend>

                    <div id="left">
                        <!-- Position name -->
                        <div class="gap">
                            <label for="positionCategory">Position Name</label>
                            <input required type="text" name="positionCategory" placeholder="Enter position name"
                                 />
                            <span id="positionCategory"></span>
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
        <div id="positionForm-table">
            <h1>Position Data</h1>
            <table class="data-table">
                <caption class="title">Position data</caption>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Position Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter variable
                    if (mysqli_num_rows($data) > 0) {

                        while ($positionRow = mysqli_fetch_assoc($data)) {
                            ?>
                            <tr>
                                <td><?php echo $counter++; # Display the counter and increment it. ?></td>
                                <td><?php echo htmlspecialchars($positionRow['PositionCategory']); ?></td>
                                <td>
                                    <a href="update/editPosition.php?id=<?php echo $positionRow['PositionID']; ?>">Edit</a> |
                                    <a href="positionPage.php?id=<?php echo $positionRow['PositionID']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this position?');">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='3'>No positions found.</td></tr>";
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