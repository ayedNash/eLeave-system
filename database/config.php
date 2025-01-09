<?php

/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'eleavedb');


try {
    /* Attempt to connect to MySQL database */
    $mysql_db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // echo "Connected";

} catch (\Exception $e) {
    echo "Could not connect !";
}

// Check connection
if ($mysql_db === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
} 

?>