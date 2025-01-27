<?php

// Initialize sessions
session_start();

// // Check if the user is already logged in, if yes than redirect to dashboard page
// if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
//     header("location: welcome.php");
//     exit;
// }

// Include config file
require_once "database/config.php"; // include("database/config.php");

// // Define variables and initialize with empty values
$empID = '';
$password = '';
$empID_err = '';
$password_err = '';

// Process submitted form data 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if username is empty
    if (empty(trim($_POST['empID']))) {
        $empID_err = 'Please enter Employee ID.';
    } else {
        $empID = trim($_POST['empID']);
    }

    // Check if password is empty
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter Password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if (empty($empID_err) && empty($password_err)) {

        // Query a select statement
        $sql = 'SELECT EmployeeID, EmployeePassword, RoleID 
                FROM employee 
                WHERE EmployeeID = ?';

        if ($stmt = $mysql_db->prepare($sql)) {

            // Set parmeter 
            $param_empID = $empID;

            // Bind param to statement
            $stmt->bind_param('s', $param_empID);

            // Attempt to execute 
            if ($stmt->execute()) {

                // Store result
                $stmt->store_result();

                // Check if empID exists. Verify user exists then verify
                if ($stmt->num_rows == 1) {
                    // Bind result into variables
                    $stmt->bind_result($EmployeeID, $hashed_password, $RoleID);  // $stmt->bind_result($id, $username, $hashed_password);

                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) { // password_verify($password, $hashed_password

                            // Start a new session
                            session_start();

                            // Store data in session
                            $_SESSION['loggedin'] = true;
                            $_SESSION['empID'] = $EmployeeID;
                            $_SESSION['role'] = $RoleID;

                            // Redirect based on employee RoleID
                            if ($RoleID == 1) {
                                header("location: adminPage/homeAdminPage.php?empID=" . urlencode($EmployeeID));
                            } else if ($RoleID == 2) {
                                header("location: userPage/employee.php?empID=" . urlencode($EmployeeID));
                            } else {
                                echo "Invalid role type. Please contact the administrator.";
                            }

                        } else {
                            // Display an error for password mismatch
                            $password_err = 'Invalid password';
                        }
                    } 
                } else {
                        $empID_err = 'Employee ID does not exist';

                    }
                } else {
                    echo "Oops! Something went wrong please try again";
                }

                // Close statement
                $stmt->close();
            }

            // Close connection
            $mysql_db->close();
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Link to the external css file part -->
    <link href="assets/css/login.css" rel="stylesheet" />

    <!-- Get fonts from google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <title>Log In</title>
    <!-- add icon link -->
    <link rel="icon" href="assets/public/images/shortcut-script-app.png" type="SNAIcon" />
    <!-- using boxicons Icon. -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <!-- Login Form  -->
        <div class="form-box signIn">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <!-- <h1>Login Here</h1> -->

                <!-- Employee ID -->
                <div class="input-box <?php (!empty($empID_err)) ? 'has_error' : ''; ?>">
                    <input type="text" name="empID" id="empID" value="<?php echo $empID ?>"
                        placeholder="Your Employee ID" required />
                    <i class="bx bxs-user"></i>
                    <span class="help-block" style="color: red;"><?php echo $empID_err; ?></span>
                </div>

                <!-- Employee Password -->
                <div class="input-box <?php (!empty($password_err)) ? 'has_error' : ''; ?>">
                    <input type="password" name="password" id="password" value="<?php echo $password ?>"
                        placeholder="Your Password" required />
                    <i class="bx bxs-lock-alt"></i>
                    <span class="help-block" style="color: red;"><?php echo $password_err; ?></span>
                </div>

                <!-- <div class="forgot-link">
            <a href="#">Forgot password?</a>
            <i class="bx bxs-lock-alt"></i>
          </div> -->

                <!-- Login Button -->
                <input type="submit" class="btn" value="Login">
            </form>
        </div>

        <div class="toggle-box">
            <!-- toggle-left -->
            <div class="toggle-panel toggle-left">
                <h1>e-Leave System</h1>
                <!-- <p>Don't have an account?</p>
          <button class="btn signUp-btn">Sign Up</button> -->
            </div>
        </div>
    </div>

    <!-- <script src="assets\js\signIn.js"></script> -->
</body>

</html>