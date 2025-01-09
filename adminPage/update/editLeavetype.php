<?php

// Initialize sessions
session_start();

// Include config file
require_once "../../database/config.php";

// Define variables and initialize with empty values. 
$leavetypeID = $leavetypeName = "";
$leavetypeName_err = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate leavetypeName 
    if (empty(trim($_POST['leavetypeName']))) {
        $leavetypeName_err = "Please enter a Leave Type Name.";
    } else {
        $leavetypeName = trim($_POST['leavetypeName']);
    }

    // Check for leavetypeID (hidden field)
    if (!empty($_POST['leavetypeID'])) {
        $leavetypeID = $_POST['leavetypeID'];
    } else {
        echo "Invalid Leave type ID.";
        exit();
    }

    // IF no errors, proceed with updating the databases.
    if (empty($leavetypeName_err)) {
        $sql1 = "UPDATE leavetype 
                 SET LeaveTypeName = ? 
                 WHERE LeaveTypeID = ?";

        if ($stmt = $mysql_db->prepare($sql1)) {
            // Bind the parameters to the prepared statement
            $stmt->bind_param("si", $param_leavetypeName, $param_leavetypeID);

            // Set parameters
            $param_leavetypeID = $leavetypeID;
            $param_leavetypeName = $leavetypeName;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // success message
                echo "<script>alert('Leave Type successfully updated!');
                      window.location.href = '../leavetypePage.php';  
                      </script>";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close the statement
            $stmt->close();
        }
    }
}

// Check if editing a role
if (isset($_GET['id'])) {
    $leavetypeID = $_GET['id'];

    // Fetch role details
    $sql2 = "SELECT * FROM leavetype Where LeaveTypeID = ?";
    if ($stmt = $mysql_db->prepare($sql2)) {
        $stmt->bind_param("i", $param_leavetypeID);
        $param_leavetypeID = $leavetypeID;

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $leavetypeName = $row['LeaveTypeName'];
            } else {
                echo "Leave Type not found.";
                exit;
            }
        } else {
            echo "Error fetching leave type details.";
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
                <a href="/adminPage/displayPersonaldetailPage.php"><img src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employees</a>
            </li>
            <li>
                <a href="#"><img src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Leave Request</a>
            </li>
            <li>
                <a href="/adminPage/rolePage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Role</a>
            </li>
            <li>
                <a href="/adminPage/positionPage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Position</a>
            </li>
            <li>
                <a href="/adminPage/employeetypePage.php"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employee Type</a>
            </li>
            <li>
                <a href="#"><img src="https://img.icons8.com/material/24/conference-background-selected.png"
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

            <div id="right">
                <button onclick="redirect()">Back</button>
            </div>
        </header>

        <!-- Role form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Update Role</legend>

                    <div id="left">

                        <!-- Hidden LeaveTypeID -->
                        <input type="hidden" name="leavetypeID" value="<?php echo htmlspecialchars($leavetypeID); ?>" />

                        <!-- leave type name -->
                        <div class="gap">
                            <label for="leavetypeName">Leave Type Name</label>
                            <input required type="text" name="leavetypeName" placeholder="Enter leave type name"
                                value="<?php echo htmlspecialchars($leavetypeName) ?>" />
                            <span id="leavetypeName"></span>
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