<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../../database_connections/sabooks.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Values for the new row
$plan = "Premium";
$books = "Unlimited";
$priced = "Unlimited";
$services = "Unlimited";
$events = "Unlimited";
$website = "With payment gateway";
$emails = 10;
$analytics = "Yes";
$api = "Yes";
$push = "Yes";
$price = 500;

// SQL to insert data
$sql = "INSERT INTO subscriptions (PLAN, BOOKS, PRICED, SERVICES, EVENTS, WEBSITE, EMAILS, ANALYTICS, API, PUSH, PRICE)
        VALUES ('$plan', '$books', '$priced', '$services', '$events', '$website', '$emails', '$analytics', '$api', '$push', '$price')";

if ($conn->query($sql) === TRUE) {
    echo "New record inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
