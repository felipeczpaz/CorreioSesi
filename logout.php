<?php

function clearSession()
{
    // Clear session variables
    $_SESSION = array();

    // Expire the session cookie by setting its expiration time in the past
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // Destroy the session
    session_destroy();
}

function redirectToPage(string $page)
{
    header("Location: $page");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start the session (if not already started)
    session_start();

    clearSession();

    // Redirect to the login page or any other appropriate action
    redirectToPage('index.php'); // Change 'index.php' to your actual login page
} else {
    redirectToPage('index.php'); // Change 'index.php' to your actual login page
}
?>
