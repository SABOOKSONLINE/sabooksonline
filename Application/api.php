<?php

// Show errors for development (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Load required files (adjust paths if needed)
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/BookModel.php';
require_once __DIR__ . '/controllers/BookController.php';

// 2. Instantiate the controller
$controller = new BookController($conn);

// 3. Get API action from query string (e.g., ?action=getBook&id=1)
$action = $_GET['action'] ?? null;

// 4. Call methods based on the action
switch ($action) {
    case 'getBook':
        $id = $_GET['id'] ?? null;
        $controller->getBookJson($id);
        break;

    case 'getAllBooks':
        $controller->getAllBooksJson();
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
        echo json_encode(['error' => 'Invalid or missing action']);
        break;
}
