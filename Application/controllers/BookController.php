<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BookController
{
    private $bookModel;

    public function __construct($conn)
    {
        $this->bookModel = new Book($conn);
    }


    /**
     * Render the view for a single book by its ID.
     */
    public function renderBookView()
    {
        $contentId = $_GET['q'] ?? null;

        if (!$contentId) {
            header("Location: /404"); // or a custom URL
            exit;
        }

        // Sanitize the contentId (if used in the view)
        $contentId = htmlspecialchars(trim($contentId));

        $book = $this->bookModel->getBookById($contentId);

        if ($book) {
            include __DIR__ . '/../views/books/bookView.php';
        } else {
            echo "Book not found.";
        }
    }

    /**
     * Render a list of books filtered by category.
     */
    public function renderBooksByCategory($category, $limit)
    {
        $category = htmlspecialchars(trim($category)); // Sanitize category input
        $books = $this->bookModel->getBooksByCategory($category, $limit);

        if ($books) {
            include __DIR__ . '/../views/books/bookCategory.php';
        } else {
            echo "No books found in this category.";
        }
    }

    /**
     * Render a list of all books, optionally limited.
     */
    public function renderAllBooks($limit)
    {
        $books = $this->bookModel->getBooks($limit);

        if ($books) {
            include __DIR__ . '/../views/books/catalogueView.php';
        } else {
            echo "No books found.";
        }
    }
}

// // Instantiate the controller
// $controller = new BookController($conn);

// // Example usage:
// if (isset($_GET['q'])) {
//     $controller->renderBookView(); // Single book view
// } elseif (isset($_GET['cat'])) {
//     $category = $_GET['cat'];
//     $controller->renderBooksByCategory($category, 10); // Books by category
// } else {
//     $controller->renderAllBooks(20); // All books, limit 20
// }
