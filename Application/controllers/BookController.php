<?php

class BookController
{
    private $bookModel;
    private $conn;

    public function __construct($conn)
    {
        $this->bookModel = new BookModel($conn);
        $this->conn = $conn;
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


    public function readBook($contentId, $category = 'book')
    {
        require_once __DIR__ . '/../models/UserModel.php';
        require_once __DIR__ . '/../models/MediaModel.php';
        require_once __DIR__ . '/../models/AcademicBookModel.php';
        $mediaModel = new MediaModel($this->conn);
        $academicModel = new AcademicBookModel($this->conn);



        if (!$contentId) {
            header("Location: /404");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['ADMIN_EMAIL'])) {
            header('Location: /login');
            exit;
        }

        $email = $_SESSION['ADMIN_EMAIL'];
        $contentId = htmlspecialchars(trim($contentId));

        $content = null;
        $pdf = null;

        // Pick content & URL
        switch (strtolower($category)) {
            case 'magazine':
                $content = $mediaModel->selectMagazineById($contentId);
                $pdf = $content['pdf_path'] ?? null;
                break;

            case 'newspaper':
                $content = $mediaModel->selectNewspaperById($contentId);
                $pdf = $content['pdf_path'] ?? null;
                break;

            case 'academic':
                $content = $academicModel->selectBookByPublicKey($contentId);
                $pdf = $content['pdf_path'] ?? null;
                break;

            case 'book':
            default:
                $content = $this->bookModel->getBookById($contentId);
                $pdf = $content['PDFURL'] ?? null;
                break;
        }

        if (!$content) {
            header("Location: /404");
            exit;
        }

        // Ownership check only for books
        $userOwnsThisContent = false;
        if ($category === 'book') {
            $userModel = new UserModel($this->conn);
            $userBooks = $userModel->getPurchasedBooksByUserEmail($email);
            foreach ($userBooks as $purchasedBook) {
                if ($purchasedBook['ID'] == $content['ID']) {
                    $userOwnsThisContent = true;
                    break;
                }
            }
        }

        $canView = ($category === 'book') ? $userOwnsThisContent : true;

        if ($pdf) {
            // Map category to folder for URL
            $folderMap = [
                'book'      => 'book-pdfs',
                'academic'      => 'academic/pdfs',
                'magazine'  => 'magazine/pdfs',
                'newspaper' => 'newspaper/pdfs'
            ];
            $folder = $folderMap[strtolower($category)] ?? 'book-pdfs';
            $pdfUrl = "https://www.sabooksonline.co.za/cms-data/{$folder}/" . htmlspecialchars($pdf, ENT_QUOTES, 'UTF-8');

            // Decide which reader to use
            $extension = strtolower(pathinfo($pdf, PATHINFO_EXTENSION));

            if ($extension === 'pdf') {
                include __DIR__ . '/../views/books/ebook/bookReader.php';
            } elseif ($extension === 'epub') {
                include __DIR__ . '/../views/books/ebook/ebupReader.php';
            } else {
                // Unsupported format
                header("Location: /404");
                exit;
            }
        } else {
            header("Location: /404");
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

        if (empty($book['ABOOKPRICE']) || $book['ABOOKPRICE'] === 0) {
            $userOwnsThisBook = true;
        } else {
            foreach ($userBooks as $purchasedBook) {
                if ($purchasedBook['ID'] == $book['ID']) {
                    $userOwnsThisBook = true;
                    break;
                }
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


    public function getAudiobookDetailsApi($a_id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $chapters = $this->bookModel->getChaptersByAudiobookId($a_id ?? null);

        echo json_encode([
            'chapters' => $chapters,
        ]);
        exit;
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


    // JSON version: Get all books
    public function getAllBooksJson($date = null)
    {
        $books = $this->bookModel->getAllBooks($date);
        header('Content-Type: application/json');
        echo json_encode($books);
    }

    public function getAcademicBooks($date = null)
    {
        $books = $this->bookModel->getAcademicBooks($date);
        header('Content-Type: application/json');
        echo json_encode($books);
    }

    public function getBanners($screen)
    {
        $banner = $this->bookModel->getBanners($screen);
        header('Content-Type: application/json');
        echo json_encode($banner);
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
