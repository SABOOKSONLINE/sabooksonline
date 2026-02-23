<?php
require_once __DIR__ . "/../Config/connection.php";
require_once __DIR__ . "/../models/CartModel.php";
require_once __DIR__ . "/../controllers/CartController.php";
require_once __DIR__ . "/../views/auth/default_mailer.php";

if (session_status() === PHP_SESSION_NONE) session_start();

function createOrderAndNotify(int $userId, float $shippingFee = 0): string
{
    global $conn;
    $paymentMethod = 'payfast';

    if (!$userId) {
        return "User ID is required.";
    }

    $cartController = new CartController($conn);

    $address = $cartController->getDeliveryAddress($userId);
    $cartItems = $cartController->getCartCheckoutItems($userId);

    if (empty($cartItems)) {
        return "Cart is empty, cannot create order.";
    }

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += ($item['hc_price'] ?? 0) * $item['cart_item_count'];
    }
    $grandTotal = $subtotal + $shippingFee;

    $orderId = $cartController->createOrder($userId);
    if (!$orderId) {
        return "Failed to create order.";
    }

    $cartController->updateOrderTotals($orderId, $grandTotal, $shippingFee, $paymentMethod);

    // Build message from cart items and address (order details viewing removed)
    $message = "<h2>Order Details</h2>";
    $message .= "<strong>Total Amount:</strong> R" . number_format($grandTotal, 2) . "<br>";
    $message .= "<strong>Shipping Fee:</strong> R" . number_format($shippingFee, 2) . "<br>";
    $message .= "<strong>Payment Method:</strong> " . $paymentMethod . "<br>";
    $message .= "<strong>Delivery Address:</strong><br>";
    $message .= $address['full_name'] . "<br>";
    $message .= $address['street_address'] . " " . $address['street_address2'] . "<br>";
    $message .= $address['local_area'] . ", " . $address['zone'] . " " . $address['postal_code'] . "<br>";
    $message .= "<h3>Items:</h3><ul>";
    foreach ($cartItems as $item) {
        $unitPrice = $item['hc_price'] ?? 0;
        $totalPrice = $unitPrice * $item['cart_item_count'];
        $message .= "<li>{$item['cart_item_count']} x {$item['title']} by {$item['authors']} at R{$unitPrice} each (Total: R{$totalPrice})</li>";
    }
    $message .= "</ul>";

    sendEmail($address['email'], "Your Order Details", $message);
    sendEmail("pearl@sabooksonline.co.za", "New Order Placed", $message);

    return $message . "<p><em>Cart has been cleared automatically.</em></p>";
}
