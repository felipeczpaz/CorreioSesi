<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start the session (if not already started)
    session_start();
    
    // Clear session variables
    $_SESSION = array();
    
    // Expire the session cookie by setting its expiration time in the past
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy the session
    session_destroy();
    
    // Redirect to the login page or any other appropriate action
    header('Location: index.php'); // Change 'login.php' to your actual login page
    exit();
} else {
    header('Location: index.php'); // Change 'login.php' to your actual login page
    exit();
}
?>
