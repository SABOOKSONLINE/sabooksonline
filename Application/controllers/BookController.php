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

    public function readBook($contentId)
    {

        if (!$contentId) {
            header("Location: /404");
            exit;
        }

        // Sanitize the contentId (if used in the view)
        $contentId = htmlspecialchars(trim($contentId));

        $book = $this->bookModel->getBookById($contentId);

        if ($book && $book['PDFURL'] != null) {
            include __DIR__ . '/../views/books/ebook/bookReader.php';
        } else {
            echo "Book Content unavailble";
        }
    }

    /**
     * Render the view for a single book by its ID.
     */
    public function renderAudioBookView()
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
            include __DIR__ . '/../views/books/audio/audiobook_details.php';
        } else {
            echo "Book not found.";
        }
    }

    /**
     * Render the view for a single book by its ID.
     */
    public function renderAudioBookChapters($audiobook_id)
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
            include __DIR__ . '/../views/books/audio/audiobook_details.php';
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
     * Render a list of books searched.
     */
    public function RenderSearchedBooks($keyword)
    {
        $books = $this->bookModel->searchBooks($keyword);

        if ($books) {
            include __DIR__ . '/../views/books/catalogueView.php';
        } else {
            echo "No books found in this category.";
        }
    }

    public function renderListingsByCategory($category, $limit)
    {
        $category = htmlspecialchars(trim($category)); // Sanitize category input
        $books = $this->bookModel->getBookListingsByCategory($category, $limit);

        if ($books) {
            include __DIR__ . '/../views/books/bookCategory.php';
        } else {
            echo "<div class='container'>No books found in this category.</div>";
        }
    }

    /**
     * Render a list of books filtered by category.
     */
    public function renderLibraryByCategory($category)
    {
        $books = $this->bookModel->getBooksByCategory($category);

        if ($books) {
            include __DIR__ . '/../views/books/catalogueView.php';
        } else {
            echo "<div class='container'>No books found in this category.</div>";
        }
    }

    /**
     * Render a list of books filtered by userId.
     */
    public function renderBooksByPublisher($userId)
    {
        $books = $this->bookModel->getBooksByPublisher($userId);

        if ($books) {
            include __DIR__ . '/../views/books/catalogueView.php';
        } else {
            echo "<div class='container'>No books found in this publisher.</div>";
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
            echo "<div class='container'>No books found.</div>";
        }
    }

    public function renderPagination()
    {
        // this code will be changed later does follow MVC
        $category = $_GET['category'] ?? null;
        $keyword = $_GET['k'] ?? null;

        if ($category) {
            $books = $this->bookModel->getBooksByCategory($category);
        } else if ($keyword) {
            $books = $this->bookModel->searchBooks($keyword);
        } else {
            $books = $this->bookModel->getBooks();
        }

        if ($books) {
            include __DIR__ . '/../views/books/pagination.php';
        } else {
            echo "<div class='container'>No books found.</div>";
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

        //  JSON version: Get single book by ID
    public function getBookJson($id)
    {
        $book = $this->bookModel->getBookById($id);

        header('Content-Type: application/json');

        if ($book) {
            echo json_encode($book);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Book not found']);
        }
    }

    public function renderListingsByCategoryJson($category, $limit)
    {
        $category = htmlspecialchars(trim($category)); // Sanitize category input
        $books = $this->bookModel->getBookListingsByCategory($category, $limit);

        if ($books) {
            echo json_encode($book);

        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Book not found']);        }
    }

    // JSON version: Get all books
    public function getAllBooksJson()
    {
        $books = $this->bookModel->getBooks();

        header('Content-Type: application/json');
        echo json_encode($books);
    }

    // JSON version: Get books by category
    public function getBooksByCategoryJson($category)
    {
        $category = htmlspecialchars(trim($category));
        $books = $this->bookModel->getBooksByCategory($category);

        header('Content-Type: application/json');
        echo json_encode($books);
    }

    // JSON version: Search books
    public function searchBooksJson($keyword)
    {
        $keyword = htmlspecialchars(trim($keyword));
        $books = $this->bookModel->searchBooks($keyword);

        header('Content-Type: application/json');
        echo json_encode($books);
    }

}
