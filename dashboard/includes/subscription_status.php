<?php

error_reporting(0);
ini_set('display_errors', 0);



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
//$token = '0e0f457e-8403-4ead-9f19-6a86a8c84137'; // Replace with the actual subscription token    

// Generate the signature
$generatedSignature = generatePayFastSignature($merchantId, $passphrase, $version, $timestamp);

// PayFast API endpoint for fetching a subscription
$fetchSubscriptionUrl = "https://api.payfast.co.za/subscriptions/{$token}/fetch";

// Set up cURL options
$curlOptions = array(
    CURLOPT_URL => $fetchSubscriptionUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "merchant-id: $merchantId",
        "version: $version",
        "timestamp: $timestamp", // Current timestamp in ISO-8601 format with timezone
        "signature: $generatedSignature",
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
    // Parse JSON response
    $responseData = json_decode($response, true);

    // Store each value in its own variable
   // $code = $responseData['code'];
    $status = $responseData['status'];
    $data = $responseData['data'];

    // Extract data values
    //$payfast_code = $data['response']['code'];
    $payfast_response = $data['response']['status'];
    $payfast_amount = $data['response']['amount'] / 100;
    $payfast_cycles = $data['response']['cycles'];
    $payfast_next = $data['response']['run_date'];
    $payfast_cycles_complete = $data['response']['cycles_complete'];
    $payfast_frequency = $data['response']['frequency'];
    $payfast_reason = $data['response']['status_reason'];
    $payfast_status = $data['response']['status_text'];
    $payfast_token = $data['response']['token'];
    
    // Add more variables as needed...

   /* echo '<p class="font-13"> <b>PayFast Amount:</b> R'.$payfast_amount.'</p>
    <p class="font-13"> <b>PayFast Next Payment:</b> '.$payfast_next.';</p>
    <p class="font-13"> <b>PayFast Payments Made:</b> '.$payfast_cycles_complete.'</p>
    <p class="font-13"> <b>PayFast Status:</b> '.$payfast_status.'</p>
    <p class="font-13"> <b>PayFast Token:</b> '.$payfast_token.'</p>';    */ 

}

// Close cURL session        
curl_close($curl);

?>
   