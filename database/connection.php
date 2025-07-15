<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$serverName = "localhost";
$username = "root";
$primaryDb = "sabookso_db";

$username2nd = "root";
$secondaryDb = "sabookso_plesk_acc";

$password = "root";

// Enable exceptions for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // create connection to primaryDb
    $conn = new mysqli($serverName, $username, $password, $primaryDb);
    $conn->set_charset("utf8mb4");

    // Create connection to secondaryDB
    $mysqli = new mysqli($serverName, $username2nd, $password, $secondaryDb);
    $mysqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Centralized error logging
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database Connection Error: " . $e->getMessage());
}
