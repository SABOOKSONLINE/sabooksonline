<?php
require 'vendor/autoload.php';

// Replace with your Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_YourSecretKeyHere');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted data
    $contentId = $_POST['contentId'];
    $cover = $_POST['cover'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $publisher = $_POST['publisher'];
    $description = $_POST['description'];
    $retailPrice = $_POST['retailPrice'];

    // Convert retail price to cents (assuming ZAR)
    $amount = $retailPrice * 100;

    // Create Stripe checkout session
    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'zar',
                    'product_data' => [
                        'name' => $title,
                        'description' => $description,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'https://11-july-2025.sabooksonline.co.za/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'https://11-july-2025.sabooksonline.co.za/cancel',
        ]);

        // Redirect to Stripe checkout
        header("Location: " . $checkout_session->url);
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
