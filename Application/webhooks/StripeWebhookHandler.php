<?php
require_once __DIR__ . '/../../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_YourSecretKey');
$endpoint_secret = 'whsec_YourWebhookSecret';

$payload = file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

try {
    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

    if ($event->type == 'checkout.session.completed') {
        $session = $event->data->object;
        file_put_contents(__DIR__ . "/../../logs/paid.log", json_encode($session));
        // Mark book as sold / send email / etc.
    }

    http_response_code(200);
} catch (Exception $e) {
    http_response_code(400);
    echo 'Webhook error: ' . $e->getMessage();
}
