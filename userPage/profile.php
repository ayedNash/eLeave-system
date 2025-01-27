<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

$empID = $_SESSION['empID'];

// Define variables and initialize with empty values
$personalDetailID = $personalName = $personalIC = $personalGender = $personalDOB = $personalMarital = $personalAddress = $personalPostCode = $personalCity = $personalState = $personalPhone = $personalProfilePic = $personalRace = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate personal details fields
    $personalDetailID = trim($_POST['personaldetailsID']);
    $personalName = trim($_POST['personalName']);
    $personalIC = trim($_POST['personalIC']);
    $personalGender = trim($_POST['personalGender']);
    $personalDOB = trim($_POST['personalDOB']);
    $personalMarital = trim($_POST['personalMaritalStatus']);
    $personalAddress = trim($_POST['personalAddress']);
    $personalPostCode = trim($_POST['personalPostCode']);
    $personalCity = trim($_POST['personalCity']);
    $personalState = trim($_POST['personalState']);
    $personalPhone = trim($_POST['personalPhone']);
    $personalProfilePic = trim($_POST['personalProfilePic']);
    $personalRace = trim($_POST['personalRace']);

    // Update values in personal details
    $sql = "UPDATE personaldetails 
            SET PersonalName = ?, PersonalICNumber = ?, PersonalGender = ?, PersonalDOB = ?, PersonalMaritalStatus = ?, PersonalAddress = ?, PersonalPostCode = ?, PersonalCity = ?, PersonalState = ?, PersonalPhoneNumber = ?, PersonalProfilePicPath = ?, PersonalRace = ?
            WHERE PersonalDetailsID = ?";

    if ($stmt = $mysql_db->prepare($sql)) {

        // Bind paramaters to the prepared statement
        $stmt->bind_param(
            "ssssssssssssi",
            $personalName,
            $personalIC,
            $personalGender,
            $personalDOB,
            $personalMarital,
            $personalAddress,
            $personalPostCode,
            $personalCity,
            $personalState,
            $personalPhone,
            $personalProfilePic,
            $personalRace,
            $personalDetailID

        );

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>alert('Personal Details successfully updated!`');
            window.location.href = 'profile.php';
             window.location.href = 'profile.php?empID=' + encodeURIComponent('$empID');
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();

    }
}

// Check if editing a personal details
if (isset($_GET['empID'])) {
    $personalDetailID = $_GET['empID'];

    // Fetch personal details
    $sql2 = "SELECT * FROM personaldetails 
             Where PersonalDetailsID = ?";

    if ($stmt = $mysql_db->prepare($sql2)) {
        $stmt->bind_param("i", $personalDetailID);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $personalName = $row['PersonalName'];
                $personalIC = $row['PersonalICNumber'];
                $personalGender = $row['PersonalGender'];
                $personalDOB = $row['PersonalDOB'];
                $personalMarital = $row['PersonalMaritalStatus'];
                $personalAddress = $row['PersonalAddress'];
                $personalPostCode = $row['PersonalPostCode'];
                $personalCity = $row['PersonalCity'];
                $personalState = $row['PersonalState'];
                $personalPhone = $row['PersonalPhoneNumber'];
                $personalProfilePic = $row['PersonalProfilePicPath'];
                $personalRace = $row['PersonalRace'];
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
                <a href="#"><img src="https://img.icons8.com/material-rounded/24/home.png" />
                    Home</a>
            </li>
            <li>
                <a href="employee.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Employees" />Employees</a>
                        src="https://img.icons8.com/material/24/conference-background-selected.png" />Employees</a>
            </li>
            <li>
                <a href="profile.php?empID=<?php echo htmlspecialchars($empID) ?>">
                    <img src="https://img.icons8.com/material/24/conference-background-selected.png" />Profile</a>
            </li>
            <li>
                <a href="profile.php?empID=<?php echo htmlspecialchars($empID) ?>"><img
                        src="https://img.icons8.com/material/24/conference-background-selected.png"
                        alt="Profile" />Profile</a>
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

        <!-- Role form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Personal Details</legend>

                    <div id="left">

                        <!-- Hidden Personal Details ID -->
                        <input type="hidden" name="personaldetailsID"
                            value="<?php echo htmlspecialchars($personalDetailID); ?>" />

                        <!-- Personal Name -->
                        <div class="gap">
                            <label for="personalName">Name</label>
                            <input type="text" name="personalName" placeholder="Enter your name"
                                value="<?php echo htmlspecialchars($personalName); ?>" />
                        </div>

                        <!-- Personal IC Number -->
                        <div class="gap">
                            <label for="personalIC">IC Number</label>
                            <input type="text" name="personalIC" placeholder="Enter IC number"
                                value="<?php echo htmlspecialchars($personalIC); ?>" />
                        </div>

                        <!-- Personal Gender -->
                        <div class="gap">
                            <label>Gender</label>
                            <input type="radio" name="personalGender"
                                value="<?php echo htmlspecialchars($personalGender); ?>" />Male
                            <input type="radio" name="personalGender"
                                value="<?php echo htmlspecialchars($personalGender); ?>" />Female
                        </div>

                        <!-- Personal DOB -->
                        <div class="gap">
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="personalDOB"
                                value="<?php echo htmlspecialchars($personalDOB); ?>" />
                        </div>

                        <!-- Personal Marital Status -->
                        <div class="gap">
                            <label for="personalMaritalStatus">Marital Status</label>
                            <select name="personalMaritalStatus">
                                <option value="<?php echo htmlspecialchars($personalMarital); ?>" selected>
                                    <?php echo htmlspecialchars($personalMarital); ?>
                                </option>
                                <!-- Marital Option -->
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>

                        <!-- Personal Address -->
                        <div class="gap">
                            <label for="address">Address</label>
                            <input type="text" name="personalAddress" placeholder="Enter address"
                                value="<?php echo htmlspecialchars($personalAddress); ?>" />
                        </div>

                        <!-- Personal Postcode -->
                        <div class="gap">
                            <label for="postcode">Postcode</label>
                            <input type="text" name="personalPostCode" placeholder="Enter postcode"
                                value="<?php echo htmlspecialchars($personalPostCode); ?>" />
                        </div>

                        <!-- Personal City -->
                        <div class="gap">
                            <label for="city">City</label>
                            <input type="text" name="personalCity" placeholder="Enter city"
                                value="<?php echo htmlspecialchars($personalCity); ?>" />
                        </div>

                        <!-- Personal State -->
                        <div class="gap">
                            <label for="state">State</label>
                            <select name="personalState">
                                <option value="<?php echo htmlspecialchars($personalState); ?>" selected>
                                    <?php echo htmlspecialchars($personalState); ?>
                                </option>
                                <!-- State Option -->
                                <option value="Johor">Johor</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Labuan">Labuan</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Penang">Penang</option>
                                <option value="Perak">Perak</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Putrajaya">Putrajaya</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Terengganu">Terengganu</option>
                            </select>
                        </div>

                        <!-- Personal Phone Number -->
                        <div class="gap">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" name="personalPhone" placeholder="Enter phone number"
                                value="<?php echo htmlspecialchars($personalPhone); ?>" />
                        </div>

                        <!-- Personal Profile Pic -->
                        <div class="gap">
                            <label for="profilePic">Profile Picture</label>
                            <input type="file" name="personalProfilePic"
                                value="<?php echo htmlspecialchars($personalProfilePic); ?>" />
                        </div>

                        <!-- Personal Race -->
                        <div class="gap">
                            <label for="race">Race</label>
                            <select name="personalRace">
                                <option value="<?php echo htmlspecialchars($personalRace); ?>" selected>
                                    <?php echo htmlspecialchars($personalRace); ?>
                                </option>
                                <!-- Race Option -->
                                <option value="Malay">Malay</option>
                                <option value="Malay">Indians</option>
                                <option value="Malay">Chinese</option>
                            </select>
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