<?php
// webhook_handler.php

// Replace 'your_api_key' with your actual Yoco API key
$api_key = 'pk_test_ed3c54a6gOol69qa7f45';

// Get the raw payload from Yoco
$rawPayload = file_get_contents("php://input");

// Validate the Yoco signature for security
$headers = getallheaders();
if (!isset($headers['X-Yoco-Signature'])) {
    // Signature not found, log and exit
    error_log('Yoco webhook signature not found.', 0);
    http_response_code(403);
    echo 'Bad Signature';
    exit;
}

$signature = $headers['X-Yoco-Signature'];
$expectedSignature = hash_hmac('sha256', $rawPayload, $api_key);

// Debugging Information
error_log('Computed Signature: ' . $expectedSignature, 0);
error_log('Received Signature: ' . $signature, 0);
error_log('Received Headers: ' . json_encode($headers), 0);
error_log('Received Payload: ' . $rawPayload, 0);

if ($signature !== $expectedSignature) {
    // Invalid signature, log and exit
    error_log('Invalid Yoco webhook signature.', 0);
    http_response_code(403);
    echo 'Bad Signature';
    exit;
}

// Decode the payload
$payload = json_decode($rawPayload, true);

// Check if the payload contains necessary information
if ($payload && isset($payload['type'])) {
    if ($payload['type'] === 'payment.succeeded') {
        // Extract relevant information from the webhook event payload
        $paymentId = $payload['payload']['id'];
        $amountPaid = $payload['payload']['amount'];
        $currency = $payload['payload']['currency'];
        // ... extract other details

        // Verify payment status and update your database or perform other actions
        // Example: Update database with payment details
        // ...

        // Log success or take additional actions
        error_log('Payment verification successful for payment ID ' . $paymentId, 0);
        echo 'Payment Successful';      
    } else {
        // Log that the webhook event is not for a successful payment
        error_log('Webhook event is not for a successful payment. Type: ' . $payload['type'], 0);
        echo 'Bad Payment Type';
    }
} else {
    // Log invalid or incomplete payload
    error_log('Invalid or incomplete webhook event payload: ' . $rawPayload, 0);
    echo 'Invalid Payload';
}

// Respond to Yoco with a 200 OK status
http_response_code(200);
?>
