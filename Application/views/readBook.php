<?php
require_once __DIR__ . '/../controllers/BookController.php';

$controller = new BookController();

if (isset($_POST['contentID']) ) {
    $contentID = $_POST['contentID'];
    $controller->readBook($contentID);
} else {
    http_response_code(400);    
    echo "No book ID specified.";
}
