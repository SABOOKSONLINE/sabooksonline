<?php

// Replace these values with your actual merchant details and subscription ID
$merchantId = '10000100';
$subscriptionId = '69b57482-832f-4d0a-a88c-14ad76f41d69';
$version = 'v1';
$timestamp = '2016-04-01T12:00:01';
$signature = '840654b40a8b312e54650e1613696b44';

// PayFast API endpoint for pausing a subscription
$pauseSubscriptionUrl = "https://api.payfast.co.za/subscriptions/{$subscriptionId}/pause";

// Set up cURL options
$curlOptions = array(
    CURLOPT_URL => $pauseSubscriptionUrl,
    CURLOPT_RETURNTRANSFER => true,     
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_HTTPHEADER => array(
        "merchant-id: $merchantId",
        "version: $version",
        "timestamp: $timestamp",
        "signature: $signature",
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
}

// Close cURL session
curl_close($curl);

// Output the response from the API
echo $response;

?>
