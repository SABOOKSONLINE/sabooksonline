<?php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect or exit if the user is not authenticated
if (!isset($_SESSION['ADMIN_USERKEY'])) {
    exit("You are not authorized to access this page.");
}

// Include database connection
require_once 'connection.php';

$userkey = $_SESSION['ADMIN_USERKEY'];

// Prepare the SQL query to get Plesk account
$query = "SELECT * FROM plesk_accounts WHERE USERKEY = ?";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    exit("Failed to prepare statement: " . $mysqli->error);
}

$stmt->bind_param("s", $userkey);
$stmt->execute();
$result = $stmt->get_result();

$websitedata = false;

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $customerPassword = $row['PASSWORD'];
    $userid = $_SESSION['ADMIN_ID'];
    $customerUsername = strtolower(str_replace(' ', '_', $_SESSION['ADMIN_NAME'])) . '_' . $userid;

    // Connect to user's database
    $con = new mysqli('localhost', $customerUsername, $customerPassword, $customerUsername);

    if ($con->connect_error) {
        exit("Connection failed: " . $con->connect_error);
    }

    $websitedata = true;
}

$stmt->close();
?>
