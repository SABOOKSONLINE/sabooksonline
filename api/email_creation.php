<?php

// Plesk API settings
$host = 'jabu.onerserv.co.za';
$login = 'sabookso';
$password = 'lOpF5s0cB~28&Q';
$apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

// Email account details
$email = 'papi@onerserv.com';
$password = '!#Emmanuel@1632';
$mailbox = true; // Set to true to create a mailbox

// Plesk API endpoint
$apiUrl = "https://$host:8443/api/v2/";

// Set the SOAP request headers
$headers = [
    "Authorization: Basic " . base64_encode($login . ":" . $apiKey),
    "Content-Type: application/json",
];

// Prepare the request data
$requestData = [
    'name' => $email,
    'password' => $password,
    'mailbox' => $mailbox,
];

// Perform the API request using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . "mail/accounts");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
$response = curl_exec($ch);
curl_close($ch);

// Process the API response
$responseData = json_decode($response, true);

if (isset($responseData['id'])) {
    echo "Email account created successfully!";
} else {
    echo "An error occurred: " . $responseData['message'];
}
