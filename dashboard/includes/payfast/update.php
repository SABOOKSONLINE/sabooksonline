<?php
session_start();

include '../select_token.php';
include '../select_subscription.php';  


$amount = $_GET['amount'];
$plan = $_GET['type'];  
$token = $_GET['token'];  
  
echo $token;

/**
 * Generate PayFast API Signature
 *
 * @param string $merchantId
 * @param string $passphrase
 * @param string $version
 * @param string $timestamp
 * @return string
 */
function generatePayFastSignature($merchantId, $passphrase, $version, $timestamp, $amount, $plan) {
    // Create an array of parameters
    $params = array(
        'merchant-id' => $merchantId,
        'passphrase' => $passphrase,
        'version' => $version,
        'timestamp' => $timestamp,
        'amount' => $amount,
        'item_name' => $plan  
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

    // MD5 hash the string and convert to lowercase
    $signature = md5($paramString);

    return $signature;
}

// Your actual merchant details
$merchantId = '18172469';
$passphrase = 'SABooksOnline2021';
$version = 'v1';
$timestamp = gmdate('Y-m-d\TH:i:sP'); // Current timestamp in ISO-8601 format with timezone
//$token = '0e0f457e-8403-4ead-9f19-6a86a8c84137'; // Replace with the actual subscription token

// Additional parameters you want to update
$amount = $amount * 100; // Replace with the updated amount

// Generate the signature
$generatedSignature = generatePayFastSignature($merchantId, $passphrase, $version, $timestamp, $amount, $plan);

// PayFast API endpoint for updating a subscription  
$updateSubscriptionUrl = "https://api.payfast.co.za/subscriptions/{$token}/update";

// Set up cURL options for a PATCH request
$curlOptions = array(
    CURLOPT_URL => $updateSubscriptionUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'PATCH', // Use PATCH method
    CURLOPT_HTTPHEADER => array(
        "merchant-id: $merchantId",
        "version: $version",
        "timestamp: $timestamp", // Current timestamp in ISO-8601 format with timezone
        "signature: $generatedSignature",
        'Content-Type: application/json', // Set content type to JSON
    ),
    CURLOPT_POSTFIELDS => json_encode(array('amount' => $amount, 'item_name' => $plan)), // JSON-encoded data with updated information
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
    echo "API Response: ";
    var_dump($response);

    $_SESSION['payment'] = 'true'; 
    $_SESSION['subscription'] = $plan;  

    $type = $plan;             

    header("Location: ../../../includes/backend/scripts/subscription-activate-free?status=active&plan=".$type);  
}  

// Close cURL session
curl_close($curl);

?>  
     