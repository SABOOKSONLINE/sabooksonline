<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../config/connection.php'; // includes $conn

$fictionModel = new Book($conn);
$fictionbooks = $fictionModel->getBooksByCategory('Fiction');

$childrenModel = new Book($conn);
$childrenbooks = $childrenkModel->getBooksByCategory('children');

$editorModel = new Book($conn);
$editorbooks = $editorModel->getBooksByCategory('Editor s Choice');

$latestModel = new Book($conn);
$latestbooks = $latestkModel->getBooksByCategory('Latest Collections');
// Pass data to the view
include __DIR__ . '/../views/books/category.php';
?>