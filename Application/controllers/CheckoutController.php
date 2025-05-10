<?php
require_once __DIR__ . '/../models/BookModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/connection.php';

class CheckoutController {
    private $bookModel;
    private $userModel;

    public function __construct() {
        $this->userModel = new userModel($conn);
        $this->bookModel = new BookModel($conn);
    }

    // Show purchase form for a specific book
    public function purchaseBook($bookId, $userId) {
        $book = $this->bookModel->getBookById($bookId);
        $user = $this->userModel->getUserByNameOrKey($userId);

        if ($book && $user) {
            $paymentForm = $this->generatePaymentForm($book, $user);
            include __DIR__ . '/../views/payment/purchaseForm.php';
        } else {
            echo "Book or user not found.";
        }
    }

    // Generate PayFast Payment Form HTML
    public function generatePaymentForm($book, $user) {
        $bookId = strtolower($book['CONTENTID']);
        $cover = htmlspecialchars($book['COVER']);
        $title = htmlspecialchars($book['TITLE']);
        $publisher = ucwords(htmlspecialchars($book['PUBLISHER']));
        $description = htmlspecialchars($book['DESCRIPTION']);
        $retailPrice = htmlspecialchars($book['RETAILPRICE']);
        $coverUrl = "https://sabooksonline.co.za/cms-data/book-covers/" . $cover;

        $userName = htmlspecialchars($user['ADMIN_NAME']);
        $userName = htmlspecialchars($user['ADMIN_USERKEY']);
        $userEmail = htmlspecialchars($user['ADMIN_EMAIL']);
        $adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'] ?? '';

        if (strpos($adminProfileImage, 'googleusercontent.com') !== false) {
            $profile = $adminProfileImage;
        } else {
            $profile = "https://sabooksonline.co.za/cms-data/profile-images/" . $adminProfileImage;
        }

        $data = [
            'merchant_id' => '18172469',
            'merchant_key' => 'gwkk16pbxdd8m',
            'return_url' => 'https://yourwebsite.com/payment/thank-you',
            'cancel_url' => 'https://yourwebsite.com/payment/cancel',
            'notify_url' => 'https://yourwebsite.com/payment/notify',
            'name_first' => $userName,
            'user_id' => $user['last_name'],
            'email_address' => $userEmail,
            'm_payment_id' => uniqid(),
            'amount' => number_format($retailPrice, 2, '.', ''),
            'item_id' => $bookId,
            'subscription_type' => '2',
        ];

        $signature = $this->generateSignature($data, 'SABooksOnline2021');
        $data['signature'] = $signature;

        $htmlForm = '
        <div style="border:1px solid #ccc; padding:20px; border-radius:10px; max-width:500px; margin:auto; font-family:sans-serif;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                <img src="' . $profile . '" alt="User" style="width:50px;height:50px;border-radius:50%;border:2px solid #eee;" />
                <strong>' . $userName . '</strong>
            </div>

            <div style="text-align:center;">
                <img src="' . $coverUrl . '" alt="Book Cover" style="max-width:100%;height:auto;border-radius:10px;box-shadow:0 0 5px rgba(0,0,0,0.2);" />
            </div>

            <h3 style="margin-top:15px;">' . $title . '</h3>
            <p><strong>Publisher:</strong> ' . $publisher . '</p>
            <p><strong>Description:</strong> ' . $description . '</p>
            <p><strong>Price:</strong> R' . $retailPrice . '</p>

            <form action="https://www.payfast.co.za/eng/process" method="post">';
        
        foreach ($data as $name => $value) {
            $htmlForm .= '<input name="' . $name . '" type="hidden" value="' . $value . '" />';
        }

        $htmlForm .= '
                <input type="submit" value="Pay Securely with PayFast" style="margin-top:10px; background-color:#00b086; color:#fff; border:none; padding:10px 15px; border-radius:5px; cursor:pointer;" />
            </form>
        </div>';

        return $htmlForm;
    }

    private function generateSignature($data, $passPhrase = null) {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = $_POST;

            $signature = $this->generateSignature($postData, 'SABooksOnline2021');
            if ($signature !== $postData['signature']) {
                die("Invalid signature");
            }

            if ($postData['payment_status'] == 'COMPLETE') {
                $paymentId = $postData['m_payment_id'];
                $amount = $postData['amount_gross'];

                $this->bookModel->markBookAsPurchased($paymentId, $amount);
                $this->thankYou();
            } else {
                $this->cancel();
            }
        }
    }

    public function thankYou() {
        echo "<h2>Thank you for your purchase!</h2>";
        echo "<p>Your payment was successful, and the book is now available for download.</p>";
    }

    public function cancel() {
        echo "<h2>Payment Cancelled</h2>";
        echo "<p>Your payment was not completed. Please try again later.</p>";
    }
}
