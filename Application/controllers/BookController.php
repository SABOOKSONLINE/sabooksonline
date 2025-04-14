<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function bookViewRender($conn)
{

    $contentId = $_GET['q'] ?? null;

    $book = new Book($conn);
    $book = $book->getBookById($contentId);

    if ($contentId) {
        if ($book) {
            include __DIR__ . '/../views/books/bookView.php';
        } else {
            echo "Book not found.";
        }
    }
}

function bookByCategoryRender($conn, $category, $limit)
{
    $book = new Book($conn);
    $books = $book->getBooksByCategory($category, $limit);

    if ($books) {
        include __DIR__ . '/../views/books/bookCategory.php';
    }
}
