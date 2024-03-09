<?php
// Database credentials
$hostname = "localhost";
$username = "root";
$password = "";
$database = "correio_sesi";

// Create MySQLi connection
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
