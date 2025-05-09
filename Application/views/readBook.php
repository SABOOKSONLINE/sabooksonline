<?php
require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";


$controller = new BookController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookId']) ) {
    $contentID = $_POST['bookId'];
    $controller->readBook($contentID);
} else {
    http_response_code(400);    
    echo "No book ID specified.";
}
