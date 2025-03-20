<?php
session_start();

// Replace 'your_api_key' with your actual Yoco API key
$api_key = 'sk_test_960bfde0VBrLlpK098e4ffeb53e1';

// Yoco API endpoint for creating a checkout session
$yoco_endpoint = 'https://payments.yoco.com/api/checkouts';

// Your product details
$amount = $_SESSION['yoco_amount']; // Amount in cents
$currency = 'ZAR';

// Your server's URL to handle webhook notifications
$notifyUrl = 'https://my.sabooksonline.co.za/includes/yoco_payment/payment_successful.php';

// Create a new checkout session
$data = [
    'amount' => $amount * 100,
    'currency' => $currency,
    // Include success and failure URLs
    'successUrl' => 'https://my.sabooksonline.co.za/includes/yoco_payment/payment_result.php',
    'failureUrl' => 'https://my.sabooksonline.co.za/failure.php',
    // Include the notify URL
    'notifyUrl' => $notifyUrl,
];

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key,
];

$ch = curl_init($yoco_endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check HTTP response code
if ($httpCode !== 200) {
    echo 'HTTP Error: ' . $httpCode;
    exit;
}

curl_close($ch);

// Decode the response
$result = json_decode($response, true);

// Check if the checkout session was successfully created
if ($result && isset($result['redirectUrl'])) {
    // Redirect the user to the Yoco payment page
    $redirectUrl = $result['redirectUrl'];
    header("Location: $redirectUrl");
    exit;
} else {
    // Handle errors
    if ($result && isset($result['error'])) {
        echo 'Error creating checkout session: ' . $result['error'];
    } else {
        echo 'Unknown error';
    }

    // Output additional debug information
    echo '<pre>';
    print_r($result);
    echo '</pre>';
}
?>
