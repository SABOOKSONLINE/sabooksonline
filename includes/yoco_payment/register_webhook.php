<?php
$secretKey = '<your-secret-key>'; // Replace with your actual secret key

$webhookData = [
    "name" => "my-webhook",
    "url" => "https://my-application/my/webhook/url"
];

$webhookUrl = 'https://payments.yoco.com/api/webhooks';

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $secretKey
];

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}

curl_close($ch);

// Output the response
echo $response;
?>
