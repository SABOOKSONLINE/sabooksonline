<?php 
session_start();
require_once __DIR__ . '/Config/connection.php';
require_once __DIR__ . '/controllers/CheckoutController.php';

$userId = $_SESSION['ADMIN_USERKEY'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bookId = $_POST['bookId'] ?? null;
    $audiobookId = $_POST['audiobookId'] ?? null;
    $magazineId = $_POST['magazineId'] ?? null;
    $newspaperId = $_POST['newspaperId'] ?? null;


    if (!$userId) {
        $_SESSION['buy'] = 'yes';
        header('Location: /login');
        exit;
    }

    $checkout = new CheckoutController($conn);

    if ($bookId) {
        $checkout->purchaseBook($bookId, $userId);
    }

    elseif ($audiobookId) {
        $checkout->purchaseBook($audiobookId, $userId, 'Audiobook');
    }

    elseif ($magazineId) {
        $checkout->purchaseMedia($magazineId, $userId, 'Magazine');
    }
    elseif ($newspaperId) {
        $checkout->purchaseMedia($newspaperId, $userId, 'Newspaper');
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['planType'])) {
    $planType = $_POST['planType']; // e.g., "Pro-Monthly"
    $paymentOption = $_POST['paymentOption'];
   
    if (!$userId) {
        header('Location: /login');
        exit;
    }

    $checkout = new CheckoutController($conn);
    $checkout->subscribe($planType, $paymentOption, $userId);

} 


