<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../config/config.php';


use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController {
    public function buy($bookId, $email) {
        $bookModel = new Book();
        $book = $this->bookModel->getBookById($contentId);

        $contentId = strtolower($book['USERID']);
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


        $currency = 'ZAR';
        $tx_ref = uniqid("sabooks_");

        $callback_url = BASE_URL . "/flutterwave-webhook";

        $data = [
            'tx_ref' => $tx_ref,
            'amount' => $retailPrice,
            'currency' => $currency,
            'redirect_url' => BASE_URL . "/success",
            'customer' => [
                'email' => $email,
            ],
            'customizations' => [
                'publisher' => $publisher,
                'title' => $title,
                'description' => 'Book Purchase',
                'logo' => $logo,
                'image' => $bookCover,
                'profile' => $rofile,
                'contentID' => $contentId,
            ]
        ];


        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.flutterwave.com/v3/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . FLW_SECRET_KEY,
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $res = json_decode($response);

        if ($res->status === 'success') {
            header("Location: " . $res->data->link);
        } else {
            echo "Payment failed. Try again.";
        }
    }
}
