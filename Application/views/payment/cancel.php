<?php
session_start();
require_once __DIR__ . '/../../Config/connection.php';
require_once __DIR__ . '/../../models/CartModel.php';

// Check if there's a pending order from Yoco payment
$orderId = $_SESSION['pending_order_id'] ?? null;

if ($orderId && !empty($_SESSION['ADMIN_ID'])) {
    // Update order status to failed
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'failed' WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->close();
    
    // Send cancellation email notification
    $cartModel = new CartModel($conn);
    $orderDetails = $cartModel->getOrderDetails($orderId, $_SESSION['ADMIN_ID']);
    if ($orderDetails) {
        require_once __DIR__ . '/../../../Admin/Helpers/mail_alert.php';
        
        // Get delivery address for email
        $address = $cartModel->getDeliveryAddress($_SESSION['ADMIN_ID']);
        $email = $address['email'] ?? $_SESSION['ADMIN_EMAIL'] ?? '';
        
        // Determine base URL for images
        $httpHost = $_SERVER['HTTP_HOST'] ?? '';
        $isLocalEmail = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
                       strpos($httpHost, 'localhost') !== false ||
                       strpos($httpHost, '127.0.0.1') !== false;
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $baseUrl = $isLocalEmail ? "$protocol://$httpHost" : 'https://www.sabooksonline.co.za';
        
        // Build cancellation email message
        $message = "<h2>Payment Cancelled</h2>";
        $message .= "<p>Your payment was cancelled. Your order has not been processed.</p>";
        $message .= "<strong>Order Number:</strong> " . ($orderDetails['order_number'] ?? $orderDetails['id']) . "<br>";
        $message .= "<strong>Total Amount:</strong> R" . number_format($orderDetails['total_amount'] ?? 0, 2) . "<br>";
        $message .= "<strong>Payment Status:</strong> Cancelled<br>";
        $message .= "<strong>Payment Method:</strong> Yoco<br>";
        
        // Add order items with images
        if (!empty($orderDetails['items'])) {
            $message .= "<h3>Order Items:</h3>";
            $message .= "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
            foreach ($orderDetails['items'] as $item) {
                $qty = $item['quantity'] ?? 1;
                $price = $item['unit_price'] ?? 0;
                $total = $price * $qty;
                $title = htmlspecialchars($item['title'] ?? 'Unknown Book');
                $author = htmlspecialchars($item['author'] ?? 'Unknown Author');
                
                $cover = $item['cover'] ?? '';
                $coverPath = $item['cover_path'] ?? '/cms-data/book-covers/';
                $imageUrl = !empty($cover) ? $baseUrl . $coverPath . $cover : $baseUrl . '/cms-data/book-covers/default-book.png';
                
                $message .= "<tr style='border-bottom: 1px solid #eee; padding: 10px 0;'>";
                $message .= "<td style='padding: 10px; width: 80px;'>";
                $message .= "<img src='" . htmlspecialchars($imageUrl) . "' alt='" . htmlspecialchars($title) . "' style='width: 60px; height: auto; border-radius: 4px;' />";
                $message .= "</td>";
                $message .= "<td style='padding: 10px;'>";
                $message .= "<strong>" . $title . "</strong><br>";
                $message .= "<small style='color: #666;'>by " . $author . "</small><br>";
                $message .= "<small>Quantity: {$qty} x R" . number_format($price, 2) . " = R" . number_format($total, 2) . "</small>";
                $message .= "</td>";
                $message .= "</tr>";
            }
            $message .= "</table>";
        }
        
        $message .= "<p>You can try again anytime. If you need assistance, please contact support.</p>";
        
        // Send email to customer
        if (!empty($email)) {
            sendEmail($email, "Payment Cancelled - Order #" . ($orderDetails['order_number'] ?? $orderDetails['id']), $message);
        }
        
        // Send email to pearl
        sendEmail("pearl@sabooksonline.co.za", "Payment Cancelled - Yoco Order #" . ($orderDetails['order_number'] ?? $orderDetails['id']), $message);
    }
    
    // Clear pending order ID from session
    unset($_SESSION['pending_order_id']);
}

header('Location: /home');
exit;
