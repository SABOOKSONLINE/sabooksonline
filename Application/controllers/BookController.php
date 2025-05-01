<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BookController
{
    private $bookModel;

    public function __construct($conn)
    {
        $this->bookModel = new BookModel($conn);
    }

    /**
     * Render the view for a single book by its ID.
     */
    public function renderBookView()
    {
        $contentId = $_GET['q'] ?? null;

        if (!$contentId) {
            header("Location: /404");
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

    public function handleSearch($keyword)
    {
        if (empty($keyword)) {
            return [];
        }

        return $this->bookModel->searchBooks($keyword);
    }

    public function renderListingsByCategory($category, $limit)
    {
        $category = htmlspecialchars(trim($category)); // Sanitize category input
        $books = $this->bookModel->getBookListingsByCategory($category, $limit);

        if ($books) {
            include __DIR__ . '/../views/books/bookCategory.php';
        } else {
            echo "No books found in this category.";
        }
    }

    /**
     * Render a list of books filtered by category.
     */
    public function renderLibraryByCategory($category)
    {
        $category = htmlspecialchars(trim($category)); // Sanitize category input
        $books = $this->bookModel->getBooksByCategory($category);

        if ($books) {
            include __DIR__ . '/../views/books/catalogueView.php';
        } else {
            echo "No books found in this category.";
        }
    }

    /**
     * Render a list of books filtered by userId.
     */
    public function renderBooksByPublisher($userId)
    {
        $userId = htmlspecialchars(trim($userId));
        $books = $this->bookModel->getBooksByPublisher($userId);

        if ($books) {
            include __DIR__ . '/../views/books/catalogueView.php';
        } else {
            echo "No books found in this publisher.";
        }
    }

    /**
     * Render a list of all books, optionally limited.
     */
    public function renderAllBooks()
    {
        $books = $this->bookModel->getBooks();

        if ($books) {
            include __DIR__ . '/../views/books/catalogueView.php';
        } else {
            echo "No books found.";
        }
    }

    public function renderPagination()
    {
        $books = $this->bookModel->getBooks();

        if ($books) {
            include __DIR__ . '/../views/books/pagination.php';
        } else {
            echo "No books found.";
        }
    }

    public function renderCategories()
    {
        $categories = $this->bookModel->getBookCategories();

        if ($categories) {
            include __DIR__ . '/../views/books/categories.php';
        } else {
            echo "No categories found.";
        }
    }
}
