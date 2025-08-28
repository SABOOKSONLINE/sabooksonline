<?php
require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/MediaModel.php";



// Check if the method is GET and bookId is set in the query string
if (isset($_GET['q'])) {

    $bookId = $_GET['q'];
    $category = $_GET['category'] ?? "book"; 
    $controller = new BookController($conn);  // Initialize your controller with the DB connection
    $controller->readBook($bookId,$category);  // Call the method to read the book with the bookId
} else {
    http_response_code(400);  // Bad request if no bookId is specified
    echo "No book ID specified.";
}
