<?php
try {
    // Database configuration
    $host = 'localhost';        // Hostname (usually localhost)
    $dbname = 'libmanagedb';    // Database name
    $username = 'root';         // Database username
    $password = '';             // Database password (leave empty for XAMPP default)

    // Establishing the PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Setting error mode to exceptions for easier debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Display error message and stop script execution if connection fails
    die("Database connection failed: " . $e->getMessage());
}
?>
