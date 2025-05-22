<?php
header('HTTP/1.0 200 OK');
flush();

require_once __DIR__ . '/../../controllers/CheckoutController.php';
require_once __DIR__ . '/../../Config/connection.php';

function generateSignature($data, $passPhrase = null) {
    $pfOutput = '';
    foreach ($data as $key => $val) {
        if ($val !== '') {
            $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
        }
    }
    $getString = substr($pfOutput, 0, -1);
    if ($passPhrase !== null) {
        $getString .= '&passphrase=' . urlencode(trim($passPhrase));
    }
    return md5($getString);
}



// $controller = new CheckoutController($conn);
$userModel = new UserModel($conn);
// $bookModel = new BookModel($conn);
// $controller->paymentNotify();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        exit;
    }

    $postData = $_POST;

    // Step 1: Extract and remove submitted signature
    $submittedSignature = $postData['signature'] ?? '';
    unset($postData['signature']);

    // Step 2: Generate expected signature
    $expectedSignature = generateSignature($postData, 'SABooksOnline2021');

    // Step 3: Compare signatures
    if ($submittedSignature !== $expectedSignature) {
        error_log("PayFast signature mismatch. Expected: $expectedSignature, Got: $submittedSignature");
        http_response_code(400); // Bad Request
        exit("Invalid signature");
    }

    // Step 4: Check for complete payment
    if (isset($postData['payment_status']) && $postData['payment_status'] === 'COMPLETE') {
        $paymentId = (int) $postData['m_payment_id'] ?? uniqid();
        $amount = (float) $postData['amount_gross'] ?? '0.00';
        $bookId = (int) $postData['custom_str1'] ?? '';
        $userEmail = $postData['email_address'] ?? '';

        // Step 5: Look up user
        $user = $userModel->getUserByEmail($userEmail);
        if (!$user) {
            error_log("User not found for email: $userEmail");
            http_response_code(404); // Not Found
            exit("User not found");
        }

        $userId = (int) $user['ADMIN_ID'];

        // Step 6: Insert into purchases
        $stmt = $this->conn->prepare("INSERT INTO purchases (user_id, book_id, payment_id, amount, payment_status) VALUES (?, ?, ?, ?, 'COMPLETE')");
        $stmt->bind_param("isss", $userId, $bookId, $paymentId, $amount);
        $success = $stmt->execute();

        if (!$success) {
            error_log("Database error: " . $stmt->error);
            http_response_code(500);
            exit("DB insert failed");
        }

        http_response_code(200);
        echo "Payment successful";
    } else {
        // Payment cancelled or failed
        http_response_code(200); // Still 200 so PayFast doesn't retry
        echo "Payment not complete";
    }
