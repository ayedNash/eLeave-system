<?php

// Initialize sessions
session_start();

// Include config file
require_once "../database/config.php";

// Define variables and initialize with empty values
$personalName = $personalIC = $personalGender = $personalDOB = $personalMarital = $personalAddress = $personalPostCode = $personalCity = $personalState = $personalPhone = $personalProfilePic = $personalRace = "";

// Process submitted form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate personal details fields
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

    // Insert into database
    $sql = "INSERT INTO personaldetails 
            (PersonalName, PersonalICNumber, PersonalGender, PersonalDOB, PersonalMaritalStatus, PersonalAddress, PersonalPostCode, PersonalCity, PersonalState, PersonalPhoneNumber, PersonalProfilePicPath, PersonalRace) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysql_db->prepare($sql)) {

        // Bind paramaters to the prepared statement
        $stmt->bind_param(
            "ssssssssssss",
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
            $personalRace
        );

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // success message
            echo "<script>alert('Personal Details successfully added!`');
            window.location.href = 'employeePage.php';
            </script>";
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();

    }
}

// Fetch data from database 
$sqls = 'SELECT * FROM personaldetails';
$data = mysqli_query($mysql_db, $sqls);

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
                <a href="displayPersonaldetailPage.php"><img
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

        <!-- Role form -->
        <div id="signup">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <fieldset>
                    <legend>Personal Details</legend>

                    <div id="left">
                        <!-- Personal Name -->
                        <div class="gap">
                            <label for="personalName">Name</label>
                            <input required type="text" name="personalName" placeholder="Enter your name" />
                        </div>

                        <!-- Personal IC Number -->
                        <div class="gap">
                            <label for="personalIC">IC Number</label>
                            <input required type="text" name="personalIC" placeholder="Enter IC number" />
                        </div>

                        <!-- Personal Gender -->
                        <div class="gap">
                            <label>Gender</label>
                            <input type="radio" name="personalGender" value="male" required />Male
                            <input type="radio" name="personalGender" value="female" required />Female
                        </div>

                        <!-- Personal DOB -->
                        <div class="gap">
                            <label for="dob">Date of Birth</label>
                            <input required type="date" name="personalDOB" />
                        </div>

                        <!-- Personal Marital Status -->
                        <div class="gap">
                            <label for="personalMaritalStatus">Marital Status</label>
                            <select name="personalMaritalStatus" required>
                                <option value="" disabled selected>Select Marital Status</option>
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
                            <input required type="text" name="personalAddress" placeholder="Enter address" />
                        </div>

                        <!-- Personal Postcode -->
                        <div class="gap">
                            <label for="postcode">Postcode</label>
                            <input required type="text" name="personalPostCode" placeholder="Enter postcode" />
                        </div>

                        <!-- Personal City -->
                        <div class="gap">
                            <label for="city">City</label>
                            <input required type="text" name="personalCity" placeholder="Enter city" />
                        </div>

                        <!-- Personal State -->
                        <div class="gap">
                            <label for="state">State</label>
                            <select name="personalState" required>
                                <option value="" disabled selected>Select State</option>
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
                            <input required type="text" name="personalPhone" placeholder="Enter phone number" />
                        </div>

                        <!-- Personal Profile Pic -->
                        <div class="gap">
                            <label for="profilePic">Profile Picture</label>
                            <input type="file" name="personalProfilePic" />
                        </div>

                        <!-- Personal Race -->
                        <div class="gap">
                            <label for="race">Race</label>
                            <select name="personalRace" required>
                                <option value="" disabled selected>Select Race</option>
                                <!-- Race Option -->
                                <option value="Malay">Malay</option>
                                <option value="Malay">Indians</option>
                                <option value="Malay">Chinese</option>
                            </select>
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
    </div>

    <script src="greet.js">

        function redirect() {
            window.location.href = 'AdminEmployeeList.html'; // Replace with your target URL
        }
    </script>
</body>

</html>