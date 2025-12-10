<?php
session_start(); // MUST be first

require_once __DIR__ . "/../Config/connection.php";
require_once __DIR__ . "/../models/CartModel.php";
require_once __DIR__ . "/../controllers/CartController.php";

header("Content-Type: application/json");

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'POST required']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents("php://input"), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

// Check user session
if (!isset($_SESSION['ADMIN_ID'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['ADMIN_ID'];

// Initialize controller
$cartController = new CartController($conn);

try {

    // 1️⃣ Call your existing controller method (kept exactly as before)
    $result = $cartController->saveDeliveryAddress($userId, $input);

    // 2️⃣ ALSO store in session so the form can refill automatically
    $_SESSION['DELIVERY_DETAILS'] = $input;

    echo json_encode([
        'success' => (bool)$result,
        'message' => $result ? 'Delivery address saved' : 'Failed to save address'
    ]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
    exit;
}
