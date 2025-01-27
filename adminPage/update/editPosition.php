<?php

// Initialize sessions
session_start();

// Include config file
require_once "../../database/config.php";

// Define variables and initialize with empty values. 
$positionID = $positionCategory = "";
$positionName_err = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate positionName 
    if (empty(trim($_POST['positionCategory']))) {
        $positionName_err = "Please enter a Position Name.";
    } else {
        $positionCategory = trim($_POST['positionCategory']);
    }

    // Check for PositionID (hidden field)
    if (!empty($_POST['PositionID'])) {
        $positionID = $_POST['PositionID'];
    } else {
        echo "Invalid Position ID.";
        exit();
    }

    // IF no errors, proceed with updating the databases.
    if (empty($positionName_err)) {
        $sql1 = "UPDATE position SET PositionCategory = ? WHERE PositionID = ?";

        if ($stmt = $mysql_db->prepare($sql1)) {
            // Bind the parameters to the prepared statement
            $stmt->bind_param("si", $param_positionName, $param_positionID);

            // Set parameters
            $param_positionID = $positionID;
            $param_positionName = $positionCategory;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // success message
                echo "<script>alert('Position successfully updated!');</script>";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close the statement
            $stmt->close();
        }
    }
}

// Check if editing a position
if (isset($_GET['id'])) {
    $positionID = $_GET['id'];

    // Fetch position details
    $sql2 = "SELECT * FROM position Where PositionID = ?";
    if ($stmt = $mysql_db->prepare($sql2)) {
        $stmt->bind_param("i", $param_positionID);
        $param_positionID = $positionID;

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $positionCategory = $row['PositionCategory'];
            } else {
                echo "Position not found.";
                exit;
            }
        } else {
            echo "Error fetching position details.";
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
    <title>User Dashboard</title>
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

    <div class="mainContentList">
        <header id="adminHeader">
            <div id="left">
                <h1>Good Afternoon, Admin</h1>
            </div>

            <!-- Logout Button -->
            <form action="../logout.php" method="POST">
                <button type="submit" onclick="return confirm('Logout?');">Logout</button>
            </form>
        </header>

        <!-- Position form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Update Position</legend>

                    <div id="left">

                        <!-- Hidden PositionID -->
                        <input type="hidden" name="PositionID" value="<?php echo htmlspecialchars($positionID); ?>" />

                        <!-- Position name -->
                        <div class="gap">
                            <label for="positionCategory">Position Name</label>
                            <input type="text" name="positionCategory" placeholder="Enter position name"
                                value="<?php echo htmlspecialchars($positionCategory) ?>" />
                            <span id="positionCategory"></span>
                        </div>

                        <!-- submit button -->
                        <div class="gap">
                            <input name="submit" type="submit" value="Update" />
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

    </div>

</body>

</html>