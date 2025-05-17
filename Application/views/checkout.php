<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/checkout') {
    require_once '../config/connection.php';
    (new CheckoutController($conn))->purchaseBook($_POST['bookId'], $_POST['userId']);
    exit;
}

session_start();
require_once __DIR__ . '/../controllers/CheckoutController.php';

// Assume user is logged in and their ID is stored in session
$userId = $_SESSION['ADMIN_USERKEY'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookId'])) {
    $bookId = $_POST['bookId'];

    if (!$userId) {
        echo "Please log in to buy books.";
        exit;
    }

    $checkout = new CheckoutController($conn);
    $checkout->purchaseBook($bookId, $userId);
} else {
    echo "Invalid request.";
}
