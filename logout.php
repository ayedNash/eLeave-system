<?php 
    session_start();
    
    // Destroy all the sessions
    if(session_destroy())
    {
        // Redirecting to login page
        header("Location: index.php");
    } 
?>