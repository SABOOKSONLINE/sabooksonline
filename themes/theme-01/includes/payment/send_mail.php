
<?php

// Set error reporting and display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// API SCRIPT FOR FETCHING DATA FROM SABOOKS ONLINE

// Load the API key from a secure location
$fileContent = file_get_contents('../api_key.txt');
$apiKey = trim(file_get_contents('../api_key.txt'));

// Check if the API key is empty
if (empty($apiKey)) {
    die('API key is empty. Make sure to set it in api_key.txt.');
}

// Include the API fetch script
include '../api_fetch.php';

// Variable to store email addresses
$allEmails = '';

// Loop through the data
for ($i = 0; $i < count($title); $i++) {
    // Concatenate email addresses
    $allEmails .= $email[$i] . ', ';
}

// Remove trailing comma and space
$allEmails = rtrim($allEmails, ', ');

// Email headers
$headers = 'From: no-reply@example.com';

// Email content
$message = 'There is a new order on your website!';

// Send email
if (mail($allEmails, $subject_2, $message, $headers)) {
    echo "Message sent!";
} else {
    echo 'Could not send email to ' . $allEmails;
}
?>

