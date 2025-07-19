<?php

class BookController
{
    private $bookModel;
    private $conn;



    public function __construct($conn)
    {
        $this->bookModel = new BookModel($conn);
        $this->conn = $conn; // Save connection for readBook


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
        require_once __DIR__ . '/../models/UserModel.php';

        if (!$contentId) {
            header("Location: /404");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 🚧 Redirect if user not logged in
        if (empty($_SESSION['ADMIN_EMAIL'])) {
            include_once __DIR__ . '/../views/403.php';
            exit;
        }

        $email = $_SESSION['ADMIN_EMAIL'];
        $contentId = htmlspecialchars(trim($contentId));
        $book = $this->bookModel->getBookById($contentId);

        if (!$book) {
            header("Location: /404");
            exit;
        }

        $userModel = new UserModel($this->conn);
        $userBooks = $userModel->getPurchasedBooksByUserEmail($email);

        $userOwnsThisBook = false;
        foreach ($userBooks as $purchasedBook) {
            if ($purchasedBook['ID'] == $book['ID']) {
                $userOwnsThisBook = true;
                break;
            }
        }

        if ($userOwnsThisBook && $book['PDFURL']) {
            include __DIR__ . '/../views/books/ebook/bookReader.php';
        } else {
            include_once __DIR__ . '/../views/401.php';
            exit;
        }
    }


    /**
     * Render the view for a single book by its ID.
     */
    public function renderAudioBookView()
    {
        $contentId = $_GET['q'] ?? null;

        require_once __DIR__ . '/../models/UserModel.php';

        if (!$contentId) {
            header("Location: /404");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['ADMIN_EMAIL'])) {
            include __DIR__ . '/../views/403.php';
            exit;
        }

        $contentId = htmlspecialchars(trim($contentId));
        $book = $this->bookModel->getBookById($contentId);

        // Ensure the book exists before continuing
        if (!$book) {
            include __DIR__ . '/../views/404.php';
            exit;
        }

        $audiobookChapters = $this->bookModel->getChaptersByAudiobookId($book['a_id'] ?? null);

        $email = $_SESSION['ADMIN_EMAIL'];
        $userModel = new UserModel($this->conn);
        $userBooks = $userModel->getPurchasedBooksByUserEmail($email);

        $userOwnsThisBook = false;
        foreach ($userBooks as $purchasedBook) {
            if ($purchasedBook['ID'] == $book['ID']) {
                $userOwnsThisBook = true;
                break;
            }
        }

        if ($userOwnsThisBook) {
            include __DIR__ . '/../views/books/audio/audiobook_details.php';

            // Optional: show warning if no chapters found
            if (empty($audiobookChapters)) {
                echo "<p>No audiobook chapters found.</p>";
            }
        } else {
            include_once __DIR__ . '/../views/401.php';
            exit;
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

        // Cache directory path
        // $cacheDir = __DIR__ . '/../cache';

        // // Create cache directory if it doesn't exist
        // if (!is_dir($cacheDir)) {
        //     mkdir($cacheDir, 0775, true);
        // }

        // // Safe cache file name based on category and limit
        // $safeCategory = strtolower(str_replace(' ', '_', $category));
        // $cacheFile = $cacheDir . "/books_category_{$safeCategory}_limit_{$limit}.html";

        // $cacheTime = 3600; // Cache duration in seconds (1 hour)

        // // Serve cached content if available and fresh
        // if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
        //     echo file_get_contents($cacheFile);
        //     return;
        // }

        // Fetch fresh book listings
        $books = $this->bookModel->getBookListingsByCategory($category, $limit);

        if ($books) {
            // Capture the output of the included view
            ob_start();
            include __DIR__ . '/../views/books/bookCategory.php';
            $html = ob_get_clean();

            // Save the generated HTML to the cache file
            file_put_contents($cacheFile, $html);

            // Output the generated HTML
            echo $html;
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

    public function renderListingsByCategoryJson($category)
    {
        $category = htmlspecialchars(urldecode(trim($category))); // Sanitize category input

        $books = $this->bookModel->getBookListingsByCategory($category);

        header('Content-Type: application/json');

        if ($books) {
            echo json_encode($books);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Home categories not found']);
        }
    }

    // JSON version: Get all books
    public function getAllBooksJson()
    {
        $books = $this->bookModel->getBooks();


        header('Content-Type: application/json');
        echo json_encode($books);
    }

    public function getAllEbooksJson()
    {
        $books = $this->bookModel->getEbooks();


        header('Content-Type: application/json');
        echo json_encode($books);
    }

    public function renderCategoriesJson()
    {
        $categories = $this->bookModel->getBookCategories();

        header('Content-Type: application/json');


        if ($categories) {
            echo json_encode($categories);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No categories found']);
        }
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

    public function renderBookCardByCategory($category = null, $limit = null, $reverse = false)
    {
        if (!$category || !$limit) {
            $books = $this->bookModel->getBooksByViews();

            if ($books) {
                include __DIR__ . '/../views/books/bkCard.php';
            }
        } else {
            $books = $this->bookModel->getBookListingsByCategory($category, $limit);

            if ($books) {
                include __DIR__ . '/../views/books/bkCard.php';
            }
        }
    }
}
