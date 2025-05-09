<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../config/config.php';


class PaymentController {

    private $bookModel;
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    $bookModel = new Book();
    $book = $this->bookModel->getBookById($contentId);

    $bookId = strtolower($book['CONTENTID']);
    $cover = htmlspecialchars($book['COVER']);
    $title = htmlspecialchars($book['TITLE']);
    $publisher = ucwords(htmlspecialchars($book['PUBLISHER']));
    $description = htmlspecialchars($book['DESCRIPTION']);
    $retailPrice = htmlspecialchars($book['RETAILPRICE']);
    $bookCover = "https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>";
    $logo = 'https://sabooksonline.co.za/img/social.png';
    
    $adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'];

    if (strpos($adminProfileImage, 'googleusercontent.com') !== false) {
        $profile = $adminProfileImage;
    } else {
        $profile = "https://sabooksonline.co.za/cms-data/profile-images/" . $adminProfileImage;
    }
    // Display Book Purchase Page
    public function purchaseBook($bookId) {
        $book = $this->bookModel->getBookById($bookId); // Get book details
        if ($book) {
            $user = $this->userModel->getUserById($_SESSION['user_id']); // Get user details
            include __DIR__ . '/../views/payment/purchaseForm.php';
        } else {
            echo "Book not found.";
        }
    }

    // Generate the PayFast payment form
    public function generatePaymentForm($book, $user) {
        // Set up necessary data for PayFast
        $data = [
            'merchant_id' => '18172469', // Merchant ID
            'merchant_key' => 'gwkk16pbxdd8m', // Merchant Key
            'return_url' => 'https://yourwebsite.com/payment/thank-you',
            'cancel_url' => 'https://yourwebsite.com/payment/cancel',
            'notify_url' => 'https://yourwebsite.com/payment/notify',
            'name_first' => $user['first_name'],
            'name_last' => $user['last_name'],
            'email_address' => $user['email'],
            'm_payment_id' => uniqid(), // Unique payment ID for transaction
            'amount' => number_format($book['price'], 2, '.', ''),
            'item_name' => $book['title'],
            'subscription_type' => '2', // Subscription type (1=recurring, 2=once-off)
        ];

        $signature = $this->generateSignature($data, 'SABooksOnline2021');
        $data['signature'] = $signature;

        $htmlForm = '<form action="https://www.payfast.co.za/eng/process" method="post">';
        foreach ($data as $name => $value) {
            $htmlForm .= '<input name="' . $name . '" type="hidden" value="' . $value . '" />';
        }
        $htmlForm .= '<input type="submit" value="Pay With PayFast" />';
        $htmlForm .= '</form>';

        return $htmlForm;
    }

    // Generate signature for PayFast
    private function generateSignature($data, $passPhrase = null) {
        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }
        // Remove last ampersand
        $getString = substr($pfOutput, 0, -1);
        if ($passPhrase !== null) {
            $getString .= '&passphrase=' . urlencode(trim($passPhrase));
        }
        return md5($getString);
    }

    // Payment notification handler (webhook)
    public function paymentNotify() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;
            // Verify the PayFast signature
            $signature = $this->generateSignature($postData, 'SABooksOnline2021');
            if ($signature !== $postData['signature']) {
                die("Invalid signature");
            }

            // Handle payment status (e.g., mark the book as purchased)
            if ($postData['payment_status'] == 'COMPLETE') {
                $paymentId = $postData['m_payment_id'];
                $amount = $postData['amount_gross'];

                // Update the database to mark the book as purchased
                $this->bookModel->markBookAsPurchased($paymentId, $amount);

                echo "Payment successful!";
            } else {
                echo "Payment failed or incomplete!";
            }
        }
    }

    // Thank you page after successful payment
    public function thankYou() {
        echo "<h2>Thank you for your purchase!</h2>";
        echo "<p>Your payment was successful, and the book is now available for download.</p>";
    }

    // Cancellation page
    public function cancel() {
        echo "<h2>Payment Cancelled</h2>";
        echo "<p>Your payment was not completed. Please try again later.</p>";
    }
}
