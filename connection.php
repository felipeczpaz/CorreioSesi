<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

function loadEnvironmentVariables(string $directory): void
{
    $dotenv = Dotenv::createImmutable($directory);
    $dotenv->load();
}

function getDatabaseCredentials(): array
{
    return [
        'hostname' => $_ENV['DB_HOST'],
        'username' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASS'],
        'database' => $_ENV['DB_NAME'],
    ];
}

function createDatabaseConnection(array $credentials): mysqli
{
    $mysqli = new mysqli(
        $credentials['hostname'],
        $credentials['username'],
        $credentials['password'],
        $credentials['database']
    );

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    return $mysqli;
}

// Load environment variables
loadEnvironmentVariables(__DIR__);

// Get database credentials
$credentials = getDatabaseCredentials();

// Create MySQLi connection
$mysqli = createDatabaseConnection($credentials);

?>
