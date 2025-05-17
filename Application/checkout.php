<?php 

session_start();
require_once '../Config/connection.php';
require_once __DIR__ . '/controllers/CheckoutController.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This route only handles POST.";
    exit;
}


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
