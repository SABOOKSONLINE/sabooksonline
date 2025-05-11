<?php
// Application/api.php

header('Content-Type: application/json');

require_once __DIR__ . '/Config/connection.php';
require_once __DIR__ . '/models/BookModel.php';
require_once __DIR__ . '/controllers/BookController.php';

$controller = new BookController($conn);

// Support both direct call (?action=...) and routed call via FastRoute
$action = $_GET['action'] ?? 'getAllBooks'; // Default to all books

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
        echo json_encode(['error' => 'Unknown action']);
        break;
}
