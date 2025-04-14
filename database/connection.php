<?php
$serverName = "localhost";
$username = "sabooks_library";
$password = "1m0g7mR3$";

$primaryDb = "Sibusisomanqa_update_3";
$secondaryDb = "Sibusisomanqa_website_plesk";

// Enable exceptions for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // create connection to primaryDb
    $conn = new mysqli($serverName, $username, $password, $primaryDb);
    $conn->set_charset("utf8mb4");

    // Create connection to secondaryDB
    $mysqli = new mysqli($serverName, $username, $password, $secondaryDb);
    $mysqli->set_charset("utf8mb4");
    echo "Connected Successful!";
} catch (mysqli_sql_exception $e) {

    // Centralized error logging
    error_log("Database Connection Error: " . $e->getMessage());
    die("A database error occurred. Please try again later.");
}
