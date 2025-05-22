<?php
require_once __DIR__ . '/../models/BookModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';


class CheckoutController {
    private $bookModel;
    private $userModel;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new UserModel($conn);
        $this->bookModel = new BookModel($conn);
    }

    public function purchaseBook($bookId, $userId) {
        $book = $this->bookModel->getBookById($bookId);
        $user = $this->userModel->getUserByNameOrKey($userId);

        if ($book && $user) {
            $_SESSION['ADMIN_NAME'] = $user['ADMIN_NAME'];
            $_SESSION['ADMIN_EMAIL'] = $user['ADMIN_EMAIL'];
            $_SESSION['ADMIN_USERKEY'] = $user['ADMIN_USERKEY'];
            $_SESSION['ADMIN_PROFILE_IMAGE'] = $user['ADMIN_PROFILE_IMAGE'];

            $paymentForm = $this->generatePaymentForm($book);
            include __DIR__ . '/../views/payment/purchaseForm.php';
        } else {
            echo "Book or user not found.";
        }
    }

    public function generatePaymentForm($book) {
        $bookId = $book['ID'];
        $title = $book['TITLE'];
        $retailPrice = $book['RETAILPRICE'];

        $userName = $_SESSION['ADMIN_NAME'] ?? 'Anonymous';
        $userEmail = $_SESSION['ADMIN_EMAIL'] ?? 'noemail@example.com';
        $userKey = uniqid();

        $passphrase = 'SABooksOnline2021';
        $amount = number_format($retailPrice, 2, '.', '');

        $data = [
            'merchant_id' => '18172469',
            'merchant_key' => 'gwkk16pbxdd8m',
            'return_url' => 'https://11-july-2023.sabooksonline.co.za/payment/return',
            'cancel_url' => 'https://11-july-2023.sabooksonline.co.za/payment/cancel',
            'notify_url' => 'https://11-july-2023.sabooksonline.co.za/payment/notify',

            'name_first' => $userName,
            'name_last'  => $bookId,
            'email_address' => $userEmail,

            'm_payment_id' => $userKey,
            'amount' => $amount,
            'item_name' => $title,

            // Optional: for subscription-type purchase
            // 'subscription_type' => '2', 
        ];

        $signature = $this->generateSignature($data, $passphrase);
        $data['signature'] = $signature;

        $pfHost = false ? 'sandbox.payfast.co.za' : 'www.payfast.co.za'; // toggle testingMode here

        $htmlForm = '<form action="https://' . $pfHost . '/eng/process" method="post">';
        foreach ($data as $name => $value) {
            $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
        }
        $htmlForm .= '<input class="ud-btn btn-thm mt-2" type="submit" value="Pay With PayFast">';
        $htmlForm .= '<img src="https://my.sabooksonline.co.za/img/Payfast By Network_dark.svg" width="200px">';
        $htmlForm .= '</form>';

        return $htmlForm;
    }

    public function generateSignature($data, $passPhrase = null) {
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


    public function paymentNotify() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        exit;
    }

    $postData = $_POST;

    // Step 1: Extract and remove submitted signature
    $submittedSignature = $postData['signature'] ?? '';
    unset($postData['signature']);

    // Step 2: Generate expected signature
    $expectedSignature = $this->generateSignature($postData, 'SABooksOnline2021');

    // Step 3: Compare signatures
    if ($submittedSignature !== $expectedSignature) {
        error_log("PayFast signature mismatch. Expected: $expectedSignature, Got: $submittedSignature");
        http_response_code(400); // Bad Request
        exit("Invalid signature");
    }

    // Step 4: Check for complete payment
    if (isset($postData['payment_status']) && $postData['payment_status'] === 'COMPLETE') {
        $paymentId = $postData['m_payment_id'] ?? uniqid();
        $amount = $postData['amount_gross'] ?? '0.00';
        $bookId = $postData['custom_str1'] ?? '';
        $userEmail = $postData['email_address'] ?? '';

        // Step 5: Look up user
        $user = $this->userModel->getUserByEmail($userEmail);
        if (!$user) {
            error_log("User not found for email: $userEmail");
            http_response_code(404); // Not Found
            exit("User not found");
        }

        $userId = $user['ADMIN_ID'];

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
}


}
