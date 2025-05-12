<?php
// CORS headers - Allow cross-origin requests
header('Access-Control-Allow-Origin: *'); // Allows requests from any domain. Change this to a specific domain if needed
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allowed HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); // Allowed headers

// Handle OPTIONS request (for preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); // Respond with status 200 for preflight requests
    exit;
}
header('Content-Type: application/json');

require_once __DIR__ . '/Config/connection.php';
require_once __DIR__ . '/models/BookModel.php';
require_once __DIR__ . '/controllers/BookController.php';

$controller = new BookController($conn);

$action = $_GET['action'] ?? 'getAllBooks';

switch ($action) {
    case 'getBook':
        $id = $_GET['id'] ?? null;
        $controller->getBookJson($id);
        break;

        case 'home':
        $category = $_GET['category'] ?? null;
        $controller->renderListingsByCategoryJson($category);
        break;

    case 'getAllBooks':
        $controller->getAllBooksJson();
        break;

    case 'getAllCategories':
        $controller->renderCategoriesJson();
        break;

    case 'getBooksByCategory':
        $category = $_GET['category'] ?? null;
        $controller->getBooksByCategoryJson($category);
        break;

    case 'searchBooks':
        $keyword = $_GET['keyword'] ?? null;
        $controller->searchBooksJson($keyword);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
        break;
}
