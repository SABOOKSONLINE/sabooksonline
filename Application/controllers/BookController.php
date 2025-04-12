<?php 

session_start();

$contentId = $_GET['q'] ?? null;

if ($contentId) {
    $bookModel = new Book($conn);
    $book = $bookModel->getBookById($contentId);

    if ($book) {
        include __DIR__ . '/views/books/book.php';
    } else {
        echo "Book not found.";
    }
} else {
    echo "Invalid request 404.";
}
?>