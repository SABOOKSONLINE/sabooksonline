<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController {
    public function buy($bookId) {
        $bookModel = new Book();
        $book = $this->bookModel->getBookById($contentId);

        $contentId = strtolower($book['USERID']);
        $cover = htmlspecialchars($book['COVER']);
        $title = htmlspecialchars($book['TITLE']);
        $publisher = ucwords(htmlspecialchars($book['PUBLISHER']));
        $description = htmlspecialchars($book['DESCRIPTION']);
        $retailPrice = htmlspecialchars($book['RETAILPRICE']);


        Stripe::setApiKey('sk_test_YourSecretKey');

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'zar',
                    'product_data' => [
                        'name' => $title,
                        'description' => $description,
                    ],
                    'unit_amount' => $retailPrice * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'https://11-july-2023.sabooksonline.co.za/success',
            'cancel_url' => 'https://11-july-2023.sabooksonline.co.za/cancel',
        ]);

        header("Location: " . $session->url);
        exit;
    }
}
