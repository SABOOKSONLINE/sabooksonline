<?php
header('HTTP/1.0 200 OK');
flush();

// notify.php - receives PayFast payment notification (IPN)

require_once __DIR__ . '/../../Config/connection.php'; // Your DB connection file
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/BookModel.php';

// Set up DB and models
$userModel = new UserModel($conn);
$bookModel = new BookModel($conn);

// === Only process POST requests ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = $_POST;

    // 1. Extract and remove the signature
    $submittedSignature = $postData['signature'] ?? '';
    unset($postData['signature']);

    // 2. Generate your own signature
    function generateSignature(array $data, string $passPhrase): string {
        ksort($data);
        $queryString = [];
        foreach ($data as $key => $value) {
            if ($value !== '') {
                $queryString[] = $key . '=' . urlencode($value);
            }
        }
        $query = implode('&', $queryString);
        if ($passPhrase !== '') {
            $query .= '&passphrase=' . urlencode($passPhrase);
        }
        return md5($query);
    }

    $expectedSignature = generateSignature($postData, 'SABooksOnline2021');

    // 3. Check if the signature is valid
    if ($submittedSignature !== $expectedSignature) {
        http_response_code(400);
        die("Invalid signature");
    }

    // 4. If payment is complete, process it
    if (isset($postData['payment_status']) && $postData['payment_status'] === 'COMPLETE') {
        $paymentId = $postData['m_payment_id'] ?? uniqid();
        $amount = $postData['amount_gross'] ?? 0;
        $bookId = $postData['custom_str1'] ?? null;
        $userEmail = $postData['email_address'] ?? null;

        if ($bookId && $userEmail) {
            $user = $userModel->getUserByEmail($userEmail);
            if (!$user) {
                http_response_code(404);
                die("User not found");
            }

            $userId = $user['ADMIN_ID'];

            // Insert the purchase
            $stmt = $conn->prepare("INSERT INTO purchases (user_id, book_id, payment_id, amount, payment_status) VALUES (?, ?, ?, ?, 'COMPLETE')");
            $stmt->bind_param("isss", $userId, $bookId, $paymentId, $amount);
            $stmt->execute();
            http_response_code(200);
            echo "Payment recorded successfully.";
        } else {
            http_response_code(400);
            die("Missing book ID or user email");
        }
    } else {
        http_response_code(200);
        echo "Payment not completed.";
    }
} else {
    http_response_code(405);
    die("Method not allowed");
}
