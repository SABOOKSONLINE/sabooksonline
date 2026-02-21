<?php
require_once __DIR__ . '/../../load_env.php';

// Validate secret hash (recommended)
$signature = $_SERVER['HTTP_VERIF_HASH'] ?? '';
$rawData = file_get_contents('php://input');

if (!$signature || $signature !== (getenv('FLUTTERWAVE_WEBHOOK_HASH') ?: '')) {
    http_response_code(403);
    exit('Invalid signature');
}

$data = json_decode($rawData, true);

if ($data['status'] === 'successful') {
    $txRef = $data['tx_ref'];
    $email = $data['customer']['email'];
    $amount = $data['retailPrice'];
    $publisher = $data['publisher'];
    $title = $data['title'];
    $bookCover = $data['bookCover'];
    $rofile = $data['profile'];

    // ✅ Mark book as paid in your DB using tx_ref or email
    // ✅ Send confirmation email, etc.
    http_response_code(200);
    echo "OK";
} else {
    http_response_code(400);
    echo "Payment not successful";
}
