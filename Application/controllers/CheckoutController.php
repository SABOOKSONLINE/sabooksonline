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
        $this->userModel = new userModel($conn);
        $this->bookModel = new BookModel($conn);
    }

   public function purchaseBook($bookId, $userId) {
    if (empty($bookId) || empty($userId)) {
        die("Invalid book or user ID.");
    }

    $book = $this->bookModel->getBookById($bookId);
    $user = $this->userModel->getUserByNameOrKey($userId);

    if (!$book) {
        die("Book not found.");
    }
    if (!$user) {
        die("User not found.");
    }

    $this->generatePaymentForm($book, $user);    
    // include __DIR__ . '/../views/payment/purchaseForm.php';
}


    public function generatePaymentForm($book, $user) {
    if (!$book || !$user) {
        die("Invalid book or user data.");
    }

    $bookId = $book['ID'] ?? '';
    $cover = $book['COVER'] ?? '';
    $title = $book['TITLE'] ?? 'Untitled Book';
    $publisher = $book['PUBLISHER'] ?? 'Unknown';
    $description = $book['DESCRIPTION'] ?? 'No description.';
    $retailPrice = $book['RETAILPRICE'] ?? 0;

    $userName = $user['ADMIN_NAME'] ?? 'Customer';
    $userEmail = $user['ADMIN_EMAIL'] ?? '';

    if (empty($bookId) || empty($userEmail)) {
        die("Missing book ID or user email.");
    }

    $coverUrl = "https://sabooksonline.co.za/cms-data/book-covers/" . $cover;
    $adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'] ?? '';
    $profile = strpos($adminProfileImage, 'googleusercontent.com') !== false
        ? $adminProfileImage
        : "https://sabooksonline.co.za/cms-data/profile-images/" . $adminProfileImage;

    $data = [
        'merchant_id'     => '18172469',
        'merchant_key'    => 'gwkk16pbxdd8m',
        'return_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/return',
        'cancel_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/cancel',
        'notify_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/notify',
        'name_first'      => $userName,
        'email_address'   => $userEmail,
        'm_payment_id'    => uniqid(),
        'amount'          => number_format($retailPrice, 2, '.', ''),
        'item_name'       => $title,
        'custom_str1'     => $bookId,
    ];

    $signature = $this->generateSignature($data, 'SABooksOnline2021');
    $data['signature'] = $signature;

    $htmlForm = '<form action="https://www.payfast.co.za/eng/process" method="post">';
    foreach ($data as $name => $value) {
        $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.htmlspecialchars($value, ENT_QUOTES).'" />';
    }
    $htmlForm .= '<input class="ud-btn btn-thm mt-2" type="submit" value="Pay With PayFast">
    <img src="https://my.sabooksonline.co.za/img/Payfast By Network_dark.svg" width="200px"></form>';

    echo $htmlForm;
}


    function generateSignature($data, $passPhrase = null) {
    // Create parameter string
    $pfOutput = '';
    foreach( $data as $key => $val ) {
        if($val !== '') {
            $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
        }
    }
    // Remove last ampersand
    $getString = substr( $pfOutput, 0, -1 );
    if( $passPhrase !== null ) {
        $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
    }
    return md5( $getString );
}


    public function paymentNotify() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = $_POST;

        // 1. Signature check
        $submittedSignature = $postData['signature'] ?? '';
        unset($postData['signature']);

        $expectedSignature = $this->generateSignature($postData, 'SABooksOnline2021');

        if ($expectedSignature !== $submittedSignature) {
            die("Invalid signature");
        }

        // 2. Check status
        if ($postData['payment_status'] === 'COMPLETE') {
            $paymentId = $postData['m_payment_id'];
            $amount = $postData['amount_gross'];
            $bookId = $postData['custom_str1'];
            $userEmail = $postData['email_address'];

            $user = $this->userModel->getUserByEmail($userEmail);
            if (!$user) {
                die("User not found");
            }
            $userId = $user['ADMIN_ID'];

            // 3. DB insert
            $stmt = $this->conn->prepare("INSERT INTO purchases (user_id, book_id, payment_id, amount, payment_status) VALUES (?, ?, ?, ?, 'COMPLETE')");
            $stmt->bind_param("isss", $userId, $bookId, $paymentId, $amount);
            $stmt->execute();

            include __DIR__ . '/../views/payment/return.php';
        } else {
            include __DIR__ . '/../views/payment/cancel.php';
        }
    }
}


}
