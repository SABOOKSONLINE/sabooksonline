<?php
/**
 * Payment Helper Functions
 * 
 * Reusable functions for handling payment processing, verification, and notifications.
 * Each function performs a single, well-defined task.
 */

/**
 * Detects if the current request is from localhost
 * 
 * @return bool True if running on localhost, false otherwise
 */
function isLocalhost(): bool {
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    return in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
           strpos($httpHost, 'localhost') !== false ||
           strpos($httpHost, '127.0.0.1') !== false ||
           strpos($httpHost, 'localhost:') !== false ||
           strpos($httpHost, '127.0.0.1:') !== false;
}

/**
 * Gets the checkout ID from GET parameters or session
 * 
 * @return string|null The checkout ID or null if not found
 */
function getCheckoutId(): ?string {
    $fromGetCheckoutId = $_GET['checkoutId'] ?? null;
    $fromGetId = $_GET['id'] ?? null;
    $fromSession = $_SESSION['yoco_checkout_id'] ?? null;
    
    error_log("getCheckoutId() called");
    error_log("From GET[checkoutId]: " . ($fromGetCheckoutId ?? 'NULL'));
    error_log("From GET[id]: " . ($fromGetId ?? 'NULL'));
    error_log("From Session[yoco_checkout_id]: " . ($fromSession ?? 'NULL'));
    
    $checkoutId = $fromGetCheckoutId ?? $fromGetId ?? $fromSession;
    error_log("Returning checkout ID: " . ($checkoutId ?? 'NULL'));
    
    return $checkoutId;
}

/**
 * Gets the pending order ID from session
 * 
 * @return int|null The order ID or null if not found
 */
function getPendingOrderId(): ?int {
    $orderId = $_SESSION['pending_order_id'] ?? null;
    error_log("getPendingOrderId() called");
    error_log("Session pending_order_id: " . ($orderId ?? 'NULL'));
    error_log("Session ID: " . session_id());
    error_log("Session started: " . (session_status() === PHP_SESSION_ACTIVE ? 'YES' : 'NO'));
    
    if ($orderId) {
        $orderIdInt = (int)$orderId;
        error_log("Returning order ID: $orderIdInt");
        return $orderIdInt;
    } else {
        error_log("No order ID found in session");
        return null;
    }
}

/**
 * Gets the Yoco secret key from environment variables
 * 
 * @return string The Yoco secret key or empty string if not found
 */
function getYocoSecretKey(): string {
    return getenv('YOCO_SECRET_KEY') ?: $_ENV['YOCO_SECRET_KEY'] ?? $_SERVER['YOCO_SECRET_KEY'] ?? '';
}

/**
 * Logs debug information if running on localhost
 * 
 * @param string $message The debug message to log
 * @param array $context Additional context data to include
 * @return void
 */
function logDebug(string $message, array $context = []): void {
    if (isLocalhost()) {
        $contextStr = !empty($context) ? ' - ' . json_encode($context) : '';
        error_log("Payment Debug: $message$contextStr");
    }
}

/**
 * Verifies payment status with Yoco API
 * 
 * @param string $checkoutId The Yoco checkout ID
 * @param bool $isLocal Whether running on localhost
 * @return array Returns ['success' => bool, 'data' => array|null, 'error' => string|null]
 */
function verifyYocoPayment(string $checkoutId, bool $isLocal): array {
    $yocoSecretKey = getYocoSecretKey();
    
    if (empty($yocoSecretKey)) {
        logDebug("YOCO_SECRET_KEY is not set - cannot verify payment");
        return ['success' => false, 'data' => null, 'error' => 'Missing Yoco secret key'];
    }
    
    $ch = curl_init("https://payments.yoco.com/api/checkouts/$checkoutId");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $yocoSecretKey,
        'Content-Type: application/json'
    ]);
    
    // SSL configuration
    if ($isLocal) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    } else {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }
    
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    logDebug("Yoco Payment Verification", [
        'checkout_id' => $checkoutId,
        'http_code' => $httpCode,
        'curl_error' => $curlError ?: 'None',
        'response_preview' => substr($response, 0, 500)
    ]);
    
    if ($curlError) {
        return ['success' => false, 'data' => null, 'error' => "cURL Error: $curlError"];
    }
    
    if ($httpCode !== 200) {
        return ['success' => false, 'data' => null, 'error' => "HTTP Code: $httpCode"];
    }
    
    $checkoutData = json_decode($response, true);
    
    logDebug("Yoco Checkout Data", [
        'status' => $checkoutData['status'] ?? 'not set',
        'keys' => array_keys($checkoutData ?? [])
    ]);
    
    return ['success' => true, 'data' => $checkoutData, 'error' => null];
}

/**
 * Checks if payment status indicates success
 * 
 * @param array $checkoutData The checkout data from Yoco API
 * @return bool True if payment is successful, false otherwise
 */
function isPaymentSuccessful(array $checkoutData): bool {
    if (!isset($checkoutData['status'])) {
        return false;
    }
    
    $status = strtolower($checkoutData['status']);
    $successStatuses = ['successful', 'completed', 'paid', 'succeeded'];
    
    $isSuccess = in_array($status, $successStatuses);
    
    logDebug("Payment Status Check", [
        'status' => $status,
        'is_success' => $isSuccess
    ]);
    
    return $isSuccess;
}

/**
 * Updates order payment status in database
 * 
 * @param mysqli $conn Database connection
 * @param int $orderId The order ID
 * @param string $status Payment status ('paid' or 'failed')
 * @return bool True if update successful, false otherwise
 */
function updateOrderPaymentStatus(mysqli $conn, int $orderId, string $status): bool {
    error_log("--- DATABASE UPDATE ATTEMPT ---");
    error_log("Order ID: $orderId");
    error_log("New Status: $status");
    error_log("Timestamp: " . date('Y-m-d H:i:s'));
    
    $allowedStatuses = ['paid', 'failed', 'pending', 'refunded'];
    if (!in_array($status, $allowedStatuses)) {
        error_log("❌ Invalid payment status: $status (allowed: " . implode(', ', $allowedStatuses) . ")");
        logDebug("Invalid payment status: $status");
        return false;
    }
    
    // Check if order exists before update
    $checkStmt = $conn->prepare("SELECT id, payment_status, user_id FROM orders WHERE id = ?");
    $checkStmt->bind_param("i", $orderId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $orderExists = $checkResult->fetch_assoc();
    $checkStmt->close();
    
    if (!$orderExists) {
        error_log("❌ Order #$orderId does not exist in database");
        return false;
    }
    
    error_log("Order exists - Current status: " . ($orderExists['payment_status'] ?? 'NULL'));
    error_log("Order user_id: " . ($orderExists['user_id'] ?? 'NULL'));
    
    $stmt = $conn->prepare("UPDATE orders SET payment_status = ?, updated_at = NOW() WHERE id = ?");
    if (!$stmt) {
        error_log("❌ Prepare statement failed: " . $conn->error);
        error_log("MySQL Error: " . mysqli_error($conn));
        logDebug("Prepare failed: " . $conn->error);
        return false;
    }
    
    error_log("✅ Prepare statement successful");
    $stmt->bind_param("si", $status, $orderId);
    error_log("Executing UPDATE query...");
    
    $result = $stmt->execute();
    
    if (!$result) {
        error_log("❌ Execute failed: " . $stmt->error);
        error_log("MySQL Error: " . mysqli_error($conn));
        logDebug("Execute failed: " . $stmt->error);
    } else {
        $affectedRows = $stmt->affected_rows;
        error_log("✅ Execute successful");
        error_log("Affected rows: $affectedRows");
        
        if ($affectedRows === 0) {
            error_log("⚠️ WARNING: Update executed but 0 rows affected (order may already have this status)");
        } else {
            error_log("✅ SUCCESS: $affectedRows row(s) updated");
        }
        
        logDebug("Order payment status updated", [
            'order_id' => $orderId,
            'status' => $status,
            'affected_rows' => $affectedRows
        ]);
    }
    
    $stmt->close();
    error_log("--- DATABASE UPDATE COMPLETE ---");
    return $result;
}

/**
 * Gets the base URL for images (localhost or production)
 * 
 * @return string The base URL
 */
function getBaseUrl(): string {
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = isLocalhost();
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    
    return $isLocal ? "$protocol://$httpHost" : 'https://www.sabooksonline.co.za';
}

/**
 * Builds book image URL
 * 
 * @param array $item Order item data
 * @param string $baseUrl Base URL for images
 * @return string The full image URL
 */
function buildBookImageUrl(array $item, string $baseUrl): string {
    $cover = $item['cover'] ?? '';
    $coverPath = $item['cover_path'] ?? '/cms-data/book-covers/';
    
    if (!empty($cover)) {
        return $baseUrl . $coverPath . $cover;
    }
    
    return $baseUrl . '/cms-data/book-covers/default-book.png';
}

/**
 * Builds HTML table row for order item in email
 * 
 * @param array $item Order item data
 * @param string $baseUrl Base URL for images
 * @return string HTML table row
 */
function buildOrderItemEmailRow(array $item, string $baseUrl): string {
    $qty = $item['quantity'] ?? 1;
    $price = $item['unit_price'] ?? 0;
    $total = $price * $qty;
    $title = htmlspecialchars($item['title'] ?? 'Unknown Book');
    $author = htmlspecialchars($item['author'] ?? 'Unknown Author');
    $imageUrl = buildBookImageUrl($item, $baseUrl);
    
    $html = "<tr style='border-bottom: 1px solid #eee; padding: 10px 0;'>";
    $html .= "<td style='padding: 10px; width: 80px;'>";
    $html .= "<img src='" . htmlspecialchars($imageUrl) . "' alt='" . htmlspecialchars($title) . "' style='width: 60px; height: auto; border-radius: 4px;' />";
    $html .= "</td>";
    $html .= "<td style='padding: 10px;'>";
    $html .= "<strong>" . $title . "</strong><br>";
    $html .= "<small style='color: #666;'>by " . $author . "</small><br>";
    $html .= "<small>Quantity: {$qty} x R" . number_format($price, 2) . " = R" . number_format($total, 2) . "</small>";
    $html .= "</td>";
    $html .= "</tr>";
    
    return $html;
}

/**
 * Builds email message for successful payment
 * 
 * @param array $orderDetails Order details
 * @param array $address Delivery address
 * @param string $baseUrl Base URL for images
 * @return string HTML email message
 */
function buildSuccessEmailMessage(array $orderDetails, array $address, string $baseUrl): string {
    $orderNumber = $orderDetails['order_number'] ?? $orderDetails['id'];
    $totalAmount = $orderDetails['total_amount'] ?? 0;
    $shippingFee = $orderDetails['shipping_fee'] ?? 0;
    
    $message = "<h2>Order Confirmed</h2>";
    $message .= "<strong>Order Number:</strong> $orderNumber<br>";
    $message .= "<strong>Total Amount:</strong> R" . number_format($totalAmount, 2) . "<br>";
    $message .= "<strong>Shipping Fee:</strong> R" . number_format($shippingFee, 2) . "<br>";
    $message .= "<strong>Payment Status:</strong> Paid<br>";
    $message .= "<strong>Payment Method:</strong> Yoco<br>";
    
    // Add delivery address
    if (!empty($address)) {
        $message .= "<strong>Delivery Address:</strong><br>";
        $message .= htmlspecialchars($address['full_name'] ?? '') . "<br>";
        $message .= htmlspecialchars($address['street_address'] ?? '') . " " . htmlspecialchars($address['street_address2'] ?? '') . "<br>";
        $message .= htmlspecialchars($address['local_area'] ?? '') . ", " . htmlspecialchars($address['zone'] ?? '') . " " . htmlspecialchars($address['postal_code'] ?? '') . "<br>";
    }
    
    // Add order items
    if (!empty($orderDetails['items'])) {
        $message .= "<h3>Order Items:</h3>";
        $message .= "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
        foreach ($orderDetails['items'] as $item) {
            $message .= buildOrderItemEmailRow($item, $baseUrl);
        }
        $message .= "</table>";
    }
    
    return $message;
}

/**
 * Builds email message for failed payment
 * 
 * @param array $orderDetails Order details
 * @param string $baseUrl Base URL for images
 * @param string $failureType Type of failure ('failed' or 'verification_failed')
 * @return string HTML email message
 */
function buildFailureEmailMessage(array $orderDetails, string $baseUrl, string $failureType = 'failed'): string {
    $orderNumber = $orderDetails['order_number'] ?? $orderDetails['id'];
    $totalAmount = $orderDetails['total_amount'] ?? 0;
    
    $title = $failureType === 'verification_failed' 
        ? "<h2>Payment Verification Failed</h2>"
        : "<h2>Payment Failed</h2>";
    
    $message = $title;
    
    if ($failureType === 'verification_failed') {
        $message .= "<p>We encountered an issue verifying your payment. Please contact support to confirm your order status.</p>";
        $message .= "<strong>Payment Status:</strong> Verification Failed<br>";
    } else {
        $message .= "<p>Unfortunately, your payment for the following order could not be processed.</p>";
        $message .= "<strong>Payment Status:</strong> Failed<br>";
    }
    
    $message .= "<strong>Order Number:</strong> $orderNumber<br>";
    $message .= "<strong>Total Amount:</strong> R" . number_format($totalAmount, 2) . "<br>";
    $message .= "<strong>Payment Method:</strong> Yoco<br>";
    
    // Add order items
    if (!empty($orderDetails['items'])) {
        $message .= "<h3>Order Items:</h3>";
        $message .= "<table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>";
        foreach ($orderDetails['items'] as $item) {
            $message .= buildOrderItemEmailRow($item, $baseUrl);
        }
        $message .= "</table>";
    }
    
    if ($failureType === 'verification_failed') {
        $message .= "<p>If you were charged, please contact us immediately with your order number.</p>";
    } else {
        $message .= "<p>Please try again or contact support if you continue to experience issues.</p>";
        $message .= "<p>If you were charged, please contact us immediately.</p>";
    }
    
    return $message;
}

/**
 * Sends payment notification emails
 * 
 * @param array $orderDetails Order details
 * @param array $address Delivery address
 * @param string $emailType Type of email ('success' or 'failure')
 * @param string $failureType Type of failure if applicable ('failed' or 'verification_failed')
 * @return void
 */
function sendPaymentNotificationEmails(array $orderDetails, array $address, string $emailType, string $failureType = 'failed'): void {
    require_once __DIR__ . '/../../Admin/Helpers/mail_alert.php';
    
    $baseUrl = getBaseUrl();
    $email = $address['email'] ?? $_SESSION['ADMIN_EMAIL'] ?? '';
    $orderNumber = $orderDetails['order_number'] ?? $orderDetails['id'];
    
    if ($emailType === 'success') {
        $subject = "Order Confirmed";
        $message = buildSuccessEmailMessage($orderDetails, $address, $baseUrl);
    } else {
        $subject = $failureType === 'verification_failed' 
            ? "Payment Verification Issue - Order #$orderNumber"
            : "Payment Failed - Order #$orderNumber";
        $message = buildFailureEmailMessage($orderDetails, $baseUrl, $failureType);
    }
    
    // Send to customer
    if (!empty($email)) {
        sendEmail($email, $subject, $message);
    }
    
    // Send to admin
    $adminSubject = $emailType === 'success' 
        ? "New Order Payment Confirmed - Yoco"
        : ($failureType === 'verification_failed' 
            ? "Payment Verification Failed - Yoco Order #$orderNumber"
            : "Payment Failed - Yoco Order #$orderNumber");
    sendEmail("pearl@sabooksonline.co.za", $adminSubject, $message);
}

/**
 * Clears payment-related session variables
 * 
 * @param bool $clearCheckoutId Whether to clear yoco_checkout_id (default: false)
 * @return void
 */
function clearPaymentSession(bool $clearCheckoutId = false): void {
    unset($_SESSION['pending_order_id']);
    if ($clearCheckoutId) {
        unset($_SESSION['yoco_checkout_id']);
    }
}
