<?php
session_start();
require_once __DIR__ . '/../../Config/connection.php';
require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../controllers/CartController.php';

// Detect localhost for testing (must be defined before use)
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
           strpos($httpHost, 'localhost') !== false ||
           strpos($httpHost, '127.0.0.1') !== false ||
           strpos($httpHost, 'localhost:') !== false ||
           strpos($httpHost, '127.0.0.1:') !== false;

// Check if this is a Yoco payment return
// Yoco may send checkoutId in URL or we get it from session
$checkoutId = $_GET['checkoutId'] ?? $_GET['id'] ?? $_SESSION['yoco_checkout_id'] ?? null;
$orderId = $_SESSION['pending_order_id'] ?? null;

// Debug: Log all GET parameters for troubleshooting
if ($isLocal) {
    error_log("Payment Return - All GET params: " . json_encode($_GET));
    error_log("Payment Return - CheckoutId from GET: " . ($_GET['checkoutId'] ?? 'not set'));
    error_log("Payment Return - CheckoutId from GET[id]: " . ($_GET['id'] ?? 'not set'));
    error_log("Payment Return - CheckoutId from Session: " . ($_SESSION['yoco_checkout_id'] ?? 'not set'));
    error_log("Payment Return - OrderId from Session: " . ($orderId ?? 'not set'));
}

// For localhost testing: if we have orderId but no checkoutId, we can still update status
// This handles cases where Yoco doesn't send checkoutId in redirect
if ($orderId) {
    // If no checkoutId, try to update directly for localhost testing
    if (!$checkoutId && $isLocal) {
        error_log("  LOCALHOST: No checkoutId found, but orderId exists. Will update payment status directly.");
        // Update payment status to 'paid' directly for localhost testing
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', updated_at = NOW() WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $orderId);
            $updateResult = $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            
            error_log("  Direct Update Result: " . ($updateResult ? 'Success' : 'Failed'));
            error_log("  Affected Rows: $affectedRows");
            
            if ($updateResult && $affectedRows > 0) {
                // Get order details for email
                $cartModel = new CartModel($conn);
                $orderDetails = $cartModel->getOrderDetails($orderId, $_SESSION['ADMIN_ID']);
                if ($orderDetails) {
                    require_once __DIR__ . '/../../../Admin/Helpers/mail_alert.php';
                    
                    $address = $cartModel->getDeliveryAddress($_SESSION['ADMIN_ID']);
                    $email = $address['email'] ?? $_SESSION['ADMIN_EMAIL'] ?? '';
                    
                    // Determine base URL for images
                    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
                    $isLocalEmail = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
                                   strpos($httpHost, 'localhost') !== false ||
                                   strpos($httpHost, '127.0.0.1') !== false;
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                    $baseUrl = $isLocalEmail ? "$protocol://$httpHost" : 'https://www.sabooksonline.co.za';
                    
                    $message = "<h2>Order Confirmed</h2>";
                    $message .= "<strong>Order Number:</strong> " . ($orderDetails['order_number'] ?? $orderDetails['id']) . "<br>";
                    $message .= "<strong>Total Amount:</strong> R" . number_format($orderDetails['total_amount'] ?? 0, 2) . "<br>";
                    $message .= "<strong>Payment Status:</strong> Paid<br>";
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
                    
                    if (!empty($email)) {
                        sendEmail($email, "Order Confirmed", $message);
                    }
                    sendEmail("pearl@sabooksonline.co.za", "New Order Payment Confirmed - Yoco", $message);
                }
                
                unset($_SESSION['pending_order_id']);
                unset($_SESSION['yoco_checkout_id']);
                // Continue to success page
            }
        }
    }
    
    // If we have checkoutId, verify with Yoco API
    if ($checkoutId) {
    // This is a Yoco payment return - verify payment
    $yocoSecretKey = getenv('YOCO_SECRET_KEY') ?: 'sk_live_0e215527YB2LEB798e04dd09d32e';
    
    // Verify payment with Yoco API
    $ch = curl_init("https://payments.yoco.com/api/checkouts/$checkoutId");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $yocoSecretKey,
        'Content-Type: application/json'
    ]);
    
    // SSL configuration - properly handle both localhost and production
    if ($isLocal) {
        // Disable SSL verification for local development
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    } else {
        // Enable SSL verification for production (security requirement)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }
    
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    
    // Debug logging for localhost testing
    if ($isLocal) {
        error_log("Yoco Payment Return - Localhost Debug:");
        error_log("  Checkout ID: $checkoutId");
        error_log("  Order ID: $orderId");
        error_log("  HTTP Code: $httpCode");
        error_log("  cURL Error: " . ($curlError ?: 'None'));
        error_log("  Response: " . substr($response, 0, 500));
    }
    
    if (!$curlError && $httpCode === 200) {
        $checkoutData = json_decode($response, true);
        
        // Debug logging
        if ($isLocal) {
            error_log("  Checkout Data: " . json_encode($checkoutData));
            error_log("  Payment Status: " . ($checkoutData['status'] ?? 'not set'));
            error_log("  All Checkout Data Keys: " . implode(', ', array_keys($checkoutData ?? [])));
        }
        
        // Check for successful payment - Yoco can return different status values
        $paymentSuccessful = false;
        $yocoStatus = null;
        
        if (isset($checkoutData['status'])) {
            $yocoStatus = strtolower($checkoutData['status']);
            // Yoco can return: 'successful', 'completed', 'paid', 'succeeded', etc.
            $paymentSuccessful = in_array($yocoStatus, ['successful', 'completed', 'paid', 'succeeded']);
            
            if ($isLocal) {
                error_log("  Status check: '$yocoStatus' -> " . ($paymentSuccessful ? 'SUCCESS' : 'NOT SUCCESS'));
            }
        }
        
        // Also check if we're on success URL (means payment likely succeeded)
        // This is a fallback for sandbox testing
        $isSuccessUrl = strpos($_SERVER['REQUEST_URI'] ?? '', '/payment/return') !== false && 
                       isset($_GET['checkoutId']) && 
                       !isset($_GET['error']);
        
        if ($isLocal && !$paymentSuccessful && $isSuccessUrl) {
            error_log("  WARNING: Payment status not 'successful' but on success URL. Checkout data:");
            error_log("  " . json_encode($checkoutData));
            error_log("  For testing: Will attempt to update if checkoutId exists");
        }
        
        // For localhost testing: if we have checkoutId and are on success URL, treat as success
        if ($isLocal && !$paymentSuccessful && $isSuccessUrl && $checkoutId) {
            error_log("  LOCALHOST TEST MODE: Treating as successful payment for testing");
            $paymentSuccessful = true;
        }
        
        if ($paymentSuccessful) {
            // Payment successful - update order status
            $cartController = new CartController($conn);
            $cartModel = new CartModel($conn);
            
            // Update order payment status to 'paid'
            $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', updated_at = NOW() WHERE id = ?");
            if (!$stmt) {
                error_log("  Prepare failed: " . $conn->error);
            } else {
                $stmt->bind_param("i", $orderId);
                $updateResult = $stmt->execute();
                
                if (!$updateResult) {
                    error_log("  Execute failed: " . $stmt->error);
                }
                
                $affectedRows = $stmt->affected_rows;
                $stmt->close();
                
                // Debug logging for localhost
                if ($isLocal) {
                    error_log("  Order Update Result: " . ($updateResult ? 'Success' : 'Failed'));
                    error_log("  Affected Rows: $affectedRows");
                    error_log("  Order ID Updated: $orderId");
                    error_log("  SQL Error: " . ($conn->error ?: 'None'));
                    
                    // Verify immediately after update
                    $verifyStmt = $conn->prepare("SELECT payment_status FROM orders WHERE id = ?");
                    $verifyStmt->bind_param("i", $orderId);
                    $verifyStmt->execute();
                    $verifyResult = $verifyStmt->get_result();
                    $verifyData = $verifyResult->fetch_assoc();
                    $verifyStmt->close();
                    error_log("  Verification - Current Status: " . ($verifyData['payment_status'] ?? 'not found'));
                }
            }
            
            // Get order details for email notification
            $orderDetails = $cartModel->getOrderDetails($orderId, $_SESSION['ADMIN_ID']);
            if ($orderDetails) {
                // Use Admin mailer which has the correct signature
                require_once __DIR__ . '/../../../Admin/Helpers/mail_alert.php';
                
                // Get delivery address
                $address = $cartModel->getDeliveryAddress($_SESSION['ADMIN_ID']);
                $email = $address['email'] ?? $_SESSION['ADMIN_EMAIL'] ?? '';
                
                // Determine base URL for images (localhost vs production)
                $httpHost = $_SERVER['HTTP_HOST'] ?? '';
                $isLocalEmail = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
                               strpos($httpHost, 'localhost') !== false ||
                               strpos($httpHost, '127.0.0.1') !== false;
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $baseUrl = $isLocalEmail ? "$protocol://$httpHost" : 'https://www.sabooksonline.co.za';
                
                // Build email message with order details
                $message = "<h2>Order Confirmed</h2>";
                $message .= "<strong>Order Number:</strong> " . ($orderDetails['order_number'] ?? $orderDetails['id']) . "<br>";
                $message .= "<strong>Total Amount:</strong> R" . number_format($orderDetails['total_amount'] ?? 0, 2) . "<br>";
                $message .= "<strong>Shipping Fee:</strong> R" . number_format($orderDetails['shipping_fee'] ?? 0, 2) . "<br>";
                $message .= "<strong>Payment Status:</strong> Paid<br>";
                $message .= "<strong>Payment Method:</strong> Yoco<br>";
                
                // Add delivery address if available
                if (!empty($address)) {
                    $message .= "<strong>Delivery Address:</strong><br>";
                    $message .= htmlspecialchars($address['full_name'] ?? '') . "<br>";
                    $message .= htmlspecialchars($address['street_address'] ?? '') . " " . htmlspecialchars($address['street_address2'] ?? '') . "<br>";
                    $message .= htmlspecialchars($address['local_area'] ?? '') . ", " . htmlspecialchars($address['zone'] ?? '') . " " . htmlspecialchars($address['postal_code'] ?? '') . "<br>";
                }
                
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
                        
                        // Build book image URL
                        $cover = $item['cover'] ?? '';
                        $coverPath = $item['cover_path'] ?? '/cms-data/book-covers/';
                        $imageUrl = '';
                        if (!empty($cover)) {
                            $imageUrl = $baseUrl . $coverPath . $cover;
                        } else {
                            // Fallback to default book image
                            $imageUrl = $baseUrl . '/cms-data/book-covers/default-book.png';
                        }
                        
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
                
                // Send email to customer
                if (!empty($email)) {
                    sendEmail($email, "Order Confirmed", $message);
                }
                
                // Send email to pearl
                sendEmail("pearl@sabooksonline.co.za", "New Order Payment Confirmed - Yoco", $message);
            }
            
            // Clear pending order ID from session
            unset($_SESSION['pending_order_id']);
        } else {
            // Payment failed or not successful - update order status to 'failed'
            $stmt = $conn->prepare("UPDATE orders SET payment_status = 'failed' WHERE id = ?");
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $stmt->close();
            
            // Send failure email notification
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
                
                // Build failure email message
                $message = "<h2>Payment Failed</h2>";
                $message .= "<p>Unfortunately, your payment for the following order could not be processed.</p>";
                $message .= "<strong>Order Number:</strong> " . ($orderDetails['order_number'] ?? $orderDetails['id']) . "<br>";
                $message .= "<strong>Total Amount:</strong> R" . number_format($orderDetails['total_amount'] ?? 0, 2) . "<br>";
                $message .= "<strong>Payment Status:</strong> Failed<br>";
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
                
                $message .= "<p>Please try again or contact support if you continue to experience issues.</p>";
                $message .= "<p>If you were charged, please contact us immediately.</p>";
                
                // Send email to customer
                if (!empty($email)) {
                    sendEmail($email, "Payment Failed - Order #" . ($orderDetails['order_number'] ?? $orderDetails['id']), $message);
                }
                
                // Send email to pearl
                sendEmail("pearl@sabooksonline.co.za", "Payment Failed - Yoco Order #" . ($orderDetails['order_number'] ?? $orderDetails['id']), $message);
            }
            
            unset($_SESSION['pending_order_id']);
            
            // Redirect to cancel page
            header('Location: /payment/cancel');
            exit;
        }
    } else {
        // API verification failed - update order status to 'failed' for safety
        error_log("Yoco payment verification failed - HTTP Code: $httpCode, Error: $curlError");
        if ($isLocal) {
            error_log("  Full Response: " . $response);
            error_log("  This might be a sandbox/test issue. Check your YOCO_SECRET_KEY environment variable.");
        }
        if ($orderId) {
            $stmt = $conn->prepare("UPDATE orders SET payment_status = 'failed' WHERE id = ?");
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $stmt->close();
            
            // Send failure email notification
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
                
                // Build failure email message
                $message = "<h2>Payment Verification Failed</h2>";
                $message .= "<p>We encountered an issue verifying your payment. Please contact support to confirm your order status.</p>";
                $message .= "<strong>Order Number:</strong> " . ($orderDetails['order_number'] ?? $orderDetails['id']) . "<br>";
                $message .= "<strong>Total Amount:</strong> R" . number_format($orderDetails['total_amount'] ?? 0, 2) . "<br>";
                $message .= "<strong>Payment Status:</strong> Verification Failed<br>";
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
                
                $message .= "<p>If you were charged, please contact us immediately with your order number.</p>";
                
                // Send email to customer
                if (!empty($email)) {
                    sendEmail($email, "Payment Verification Issue - Order #" . ($orderDetails['order_number'] ?? $orderDetails['id']), $message);
                }
                
                // Send email to pearl
                sendEmail("pearl@sabooksonline.co.za", "Payment Verification Failed - Yoco Order #" . ($orderDetails['order_number'] ?? $orderDetails['id']), $message);
            }
            
            unset($_SESSION['pending_order_id']);
            unset($_SESSION['yoco_checkout_id']);
        }
        // Redirect to cancel page
        header('Location: /payment/cancel');
        exit;
    }
    } // End of if ($checkoutId) block
} else {
    // No orderId in session - this might be a direct visit or PayFast return
    // This is a PayFast return - existing behavior
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful</title>

<style>
    body {
        margin: 0;
        padding: 0;
        background: rgba(0,0,0,0.75);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-family: Arial, sans-serif;
    }

    .modal-box {
        background: #ffffff;
        padding: 40px 30px;
        border-radius: 16px;
        width: 380px;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .success-check {
        width: 90px;
        height: 90px;
        background: #28a745;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: popIn 0.4s ease;
    }

    @keyframes popIn {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-check svg {
        width: 55px;
        height: 55px;
        fill: #fff;
    }

    h2 {
        margin: 0;
        font-size: 26px;
        font-weight: bold;
    }

    p {
        margin-top: 10px;
        color: #333;
        font-size: 15px;
    }

    #continueBtn {
        margin-top: 25px;
        padding: 12px 20px;
        background: #28a745;
        border: none;
        color: #fff;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: 0.2s;
    }

    #continueBtn:hover {
        background: #218838;
    }
</style>
</head>

<body>

<div class="modal-box">
    <div class="success-check">
        <!-- SVG TICK -->
        <svg viewBox="0 0 24 24">
            <path d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.4-1.4z"></path>
        </svg>
    </div>

    <h2>Thank You!</h2>
    <p>Your purchase has been completed successfully.</p>
    <?php 
    // Show debug info for localhost testing
    if ($isLocal && isset($orderId)): 
        // Verify the order was actually updated
        $verifyStmt = $conn->prepare("SELECT payment_status FROM orders WHERE id = ?");
        $verifyStmt->bind_param("i", $orderId);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();
        $orderStatus = $verifyResult->fetch_assoc();
        $verifyStmt->close();
    ?>
        <div style="background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 15px 0; font-size: 13px;">
            <strong>🧪 Localhost Test Mode:</strong><br>
            Order ID: <?= $orderId ?><br>
            Checkout ID: <?= htmlspecialchars($checkoutId ?? 'not set') ?><br>
            Payment Status: <strong style="color: <?= ($orderStatus['payment_status'] ?? '') === 'paid' ? '#28a745' : '#dc3545' ?>">
                <?= strtoupper($orderStatus['payment_status'] ?? 'unknown') ?>
            </strong><br>
            <?php if (($orderStatus['payment_status'] ?? '') === 'paid'): ?>
                ✅ <strong>SUCCESS!</strong> Order payment status has been updated to PAID.
            <?php else: ?>
                ⚠️ Payment status is: <?= htmlspecialchars($orderStatus['payment_status'] ?? 'not set') ?><br>
                <?php if ($checkoutId): ?>
                    <button onclick="manualUpdatePayment()" style="margin-top: 10px; padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        🔄 Manually Update to Paid (Test)
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <script>
        function manualUpdatePayment() {
            if (confirm('Manually update order #<?= $orderId ?> payment status to PAID? (Test only)')) {
                fetch('/payment/manual-update-status', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({order_id: <?= $orderId ?>, status: 'paid'})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Payment status updated to PAID!');
                        location.reload();
                    } else {
                        alert('Failed: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(err => {
                    alert('Error: ' + err.message);
                });
            }
        }
        </script>
    <?php endif; ?>
    <button id="continueBtn">Continue</button>
</div>

<script>
document.getElementById("continueBtn").onclick = function() {
    window.location.href = "/dashboards/bookshelf";
};
</script>

</body>
</html>
