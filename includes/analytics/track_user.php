<?php

//include '../database_connections/sabooks.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);

 // Function to insert a new record into the database
 function logPageVisit($conn, $page_url, $user_agent, $referer, $duration, $user_country, $user_city, $user_province, $user_ip) {
    $current_time = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO page_visits (page_url, user_agent, referer, visit_time, duration, user_country, user_city, user_province, user_ip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Bind values to the prepared statement
    $stmt->bind_param("sssssssss", $page_url, $user_agent, $referer, $current_time, $duration, $user_country, $user_city, $user_province, $user_ip);

    // Execute the prepared statement
    $stmt->execute();
}

// Get user agent (device information)
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Get referring page (referer) if it's set
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Unknown';


// Get the current page URL
$page_url = $_SERVER['REQUEST_URI'];

// Calculate the duration of the visit (in seconds)
$start_time = $_SESSION['visit_start_time'] ?? time();
$duration = time() - $start_time;

// Get the user's IP address
include 'track_ip.php';

$user_ip = $ip;  

// Log the visit
logPageVisit($conn, $page_url, $user_agent, $referer, $duration, $user_country, $user_city, $user_province, $user_ip);

// Store the current time for the next page visit
$_SESSION['visit_start_time'] = time();


?>