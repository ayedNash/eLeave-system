<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// Fetch data from database
$sqls = 'SELECT * FROM personaldetails';
$data = mysqli_query($mysql_db, $sqls);

// Deleting the values.
if (isset($_GET['id'])) {
    $personaldetailID = $_GET['id'];

    // Prepare the delete sql query.
    $sql2 = "DELETE FROM personaldetails WHERE PersonalDetailsID = ?";

    if ($stmt = $mysql_db->prepare($sql2)) {
        // Bind the parameters to the prepared statement
        $stmt->bind_param("i", $param_personaldetailID);

        // Set parameters
        $param_personaldetailID = $personaldetailID;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>
            alert('Personal details successfully deleted!');
            window.location.href = 'displayPersonaldetailPage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to delete the personal details.";
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
    <link rel="stylesheet" href="../assets/css/nav2.css" media="screen" />
    <link rel="stylesheet" href="../assets/css/table2.css" media="screen" />
    <link rel="stylesheet" href="../assets/css/form2.css" media="screen" />
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
                <a href="displayLeaveRequestPage.php"><img
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

        <div class="buttonAdd">
            <button name="createPersonal"><a href="../adminPage/personaldetailPage.php">Add Personal Detail +
                </a></button>
        </div>
        <div id="Form-table">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>IC Number</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Marital Status</th>
                        <th>Address</th>
                        <th>Postcode</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Phone Number</th>
                        <th>Race</th>
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
                                <td><?php echo htmlspecialchars($row['PersonalName']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalICNumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalGender']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalDOB']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalMaritalStatus']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalAddress']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalPostCode']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalCity']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalState']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalPhoneNumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['PersonalRace']); ?></td>
                                <td>
                                    <a href="update/editPersonaldetail.php?id=<?php echo $row['PersonalDetailsID']; ?>">Edit</a>
                                    |
                                    <a href="displayPersonaldetailPage.php?id=<?php echo $row['PersonalDetailsID']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
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