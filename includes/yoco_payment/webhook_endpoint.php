<?php

// Your secret key for verifying authenticity
$yourSecretKey = 'sk_test_9087d48bQ4NZykb838b46948afd4';

// Function to handle incoming webhook events
function handleWebhookEvent()
{
    // Get the raw request body
    $requestBody = file_get_contents('php://input');

    // Parse JSON data from the request body
    $webhookData = json_decode($requestBody, true);

    // Log the raw request for debugging (optional)
    error_log("Received raw data: " . $requestBody);

    // Check if the secret key matches (for basic authenticity verification)
    if (isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION'] === 'Bearer ' . $yourSecretKey) {
        // Process the webhook data
        // Add your custom logic here based on the $webhookData
        // For example, you can check $webhookData['type'] to determine the event type

        // Respond with a 2xx status code to confirm processing
        http_response_code(200);
        echo 'Webhook event processed successfully';
    } else {
        // Secret key does not match, unauthorized request
        // Respond with a 403 Forbidden status code
        http_response_code(403);
        echo 'Unauthorized';
    }
}

// Handle the incoming webhook event
handleWebhookEvent();

?>
