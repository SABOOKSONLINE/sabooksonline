<?php

include '../select_token.php';

$amount = $_GET['amount'];
$plan = $_GET['type'];  
$token = $_GET['token'];    

/**
 * Generate PayFast API Signature
 *
 * @param string $merchantId
 * @param string $passphrase
 * @param string $version
 * @param string $timestamp
 * @return string
 */
function generatePayFastSignature($merchantId, $passphrase, $version, $timestamp) {
    // Create an array of parameters
    $params = array(
        'merchant-id' => $merchantId,
        'passphrase' => $passphrase,
        'version' => $version,
        'timestamp' => $timestamp,
    );

    // Sort the parameters alphabetically   
    ksort($params);

    // Create the parameter string by concatenating non-empty, sorted variables with '&'
    $paramString = '';
    foreach ($params as $key => $value) {
        if (!empty($value)) {
            $paramString .= urlencode($key) . '=' . urlencode($value) . '&';
        }
    }

    // Remove the trailing '&'
    $paramString = rtrim($paramString, '&');

    // MD5 hash the string
    $signature = md5($paramString);

    return $signature;
}

// Your actual merchant details
$merchantId = '18172469';
$passphrase = 'SABooksOnline2021';
$version = 'v1';
$timestamp = gmdate('Y-m-d\TH:i:sP'); // Current timestamp in ISO-8601 format with timezone
//$token = '86894896-5154-4714-972c-903df8d9d317'; // Replace with the actual subscription token  

// Generate the signature
$generatedSignature = generatePayFastSignature($merchantId, $passphrase, $version, $timestamp);

// PayFast API endpoint for canceling a subscription
$cancelSubscriptionUrl = "https://api.payfast.co.za/subscriptions/{$token}/cancel";

// Set up cURL options
$curlOptions = array(
    CURLOPT_URL => $cancelSubscriptionUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'PUT', // Use PUT method
    CURLOPT_HTTPHEADER => array(
        "merchant-id: $merchantId",
        "version: $version",
        "timestamp: $timestamp", // Current timestamp in ISO-8601 format with timezone
        "signature: $generatedSignature",
        'Content-Length: 0', // Add Content-Length header
    ),
);

// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt_array($curl, $curlOptions);

// Execute cURL session and get the response
$response = curl_exec($curl);

// Check for cURL errors
if (curl_errno($curl)) {
    echo 'Curl error: ' . curl_error($curl);
} else {
    // Output the response from the API
    // Output the response from the API
    echo "API Response: ";
    var_dump($response);

    $_SESSION['payment'] = 'true'; 
    $_SESSION['subscription'] = 'Free';  

    $type = 'Free';               

    header("Location: ../../../includes/backend/scripts/subscription-activate-free");    
}

// Close cURL session
curl_close($curl);

?>       
            