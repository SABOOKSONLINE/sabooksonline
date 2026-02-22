<?php
session_start(); // MUST be first

require_once __DIR__ . "/../Config/connection.php";
require_once __DIR__ . "/../models/CartModel.php";
require_once __DIR__ . "/../controllers/CartController.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'POST required']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

if (!isset($_SESSION['ADMIN_ID'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$cartController = new CartController($conn);
$userId = $_SESSION['ADMIN_ID'];

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '';
$action   = str_replace($basePath . '/cart/', '', $requestPath);

switch ($action) {
    case 'add':
        $bookId = $input['book_id'] ?? null;
        $qty    = intval($input['qty'] ?? 1);
        $bookType = $input['book_type'] ?? 'regular';
        $result = $cartController->addCartItem($userId, $bookId, $qty, $bookType);
        break;

    case 'remove':
        $bookId = $input['book_id'] ?? null;
        $bookType = $input['book_type'] ?? 'regular';
        $result = $cartController->removeCartItem($userId, $bookId, $bookType);
        break;

    case 'update':
        $bookId = $input['book_id'] ?? null;
        $qty    = intval($input['qty'] ?? 1);
        $bookType = $input['book_type'] ?? 'regular';
        $result = $cartController->updateCartItem($userId, $bookId, $qty, $bookType);
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Invalid cart action']);
        exit;
}

// Return result
echo json_encode(['success' => (bool)$result]);
exit;
