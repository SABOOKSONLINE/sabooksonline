<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../models/UserModel.php";

class BookListingController
{
    private $booksModel;
    private $usersModel;

    public function __construct($conn)
    {
        $this->booksModel = new BookListingsModel($conn);
        $this->usersModel = new UserModel($conn);
    }

    public function renderBookListing($userId)
    {
        // Get filter parameters from GET request
        $filters = [
            'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
            'status' => isset($_GET['status']) ? trim($_GET['status']) : '',
            'category' => isset($_GET['category']) ? trim($_GET['category']) : '',
            'format' => isset($_GET['format']) ? trim($_GET['format']) : '',
            'min_price' => isset($_GET['min_price']) ? trim($_GET['min_price']) : '',
            'max_price' => isset($_GET['max_price']) ? trim($_GET['max_price']) : '',
            'date_from' => isset($_GET['date_from']) ? trim($_GET['date_from']) : '',
            'date_to' => isset($_GET['date_to']) ? trim($_GET['date_to']) : '',
            'sort' => isset($_GET['sort']) ? trim($_GET['sort']) : ''
        ];
        
        // Get books with filters
        $books = $this->booksModel->selectBooksByUserId($userId, $filters);
        
        // Get categories for filter dropdown
        $categories = $this->booksModel->getCategoriesByUserId($userId);
        
        // Get price range for filter
        $priceRange = $this->booksModel->getPriceRangeByUserId($userId);
        
        // Pass filters, categories, and price range to view
        $filters['categories'] = $categories;
        $filters['price_range'] = $priceRange;
        
        include __DIR__ . "/../views/includes/layouts/tables/listing_table.php";
    }

    public function renderBookByContentId($userKey, $contentId)
    {
        $book = $this->booksModel->selectBookByContentId($userKey, $contentId);
        $hcPublishers = $this->usersModel->getHardcopyPublishers();
        $publisherEmails = array_column($hcPublishers, 'email');
        // echo "<pre>";
        // print_r($publisherEmails);
        // echo "</pre>";
        // include __DIR__ . "/../views/includes/layouts/forms/book_form.php";
        include __DIR__ . "/../views/includes/layouts/forms/temp_book_form.php";
    }

    public function getBookByContent($userId, $contentId)
    {
        return $this->booksModel->selectBookByContentId($userId, $contentId);
    }

    public function getAdminName($userKey)
    {
        $this->booksModel->getAdminName($userKey);
    }

    public function insertBookData($data)
    {
        $result = $this->booksModel->insertBook($data);

        return $result;
    }

    public function updateBookData($bookId, $data)
    {
        return $this->booksModel->updateBook($bookId, $data);
    }

    public function deleteBookListing($contentId)
    {
        return $this->booksModel->deleteBook($contentId);
    }

    // ----------------- HARDCOPY METHODS -----------------
    public function insertHardcopy($data)
    {
        return $this->booksModel->insertHardcopy($data);
    }

    public function updateHardcopy($data)
    {
        return $this->booksModel->updateHardcopy($data);
    }

    public function getHardcopyByBookId($bookId)
    {
        return $this->booksModel->getHardcopyByBookId($bookId);
    }

    // ----------------- AUDIOBOOK METHODS -----------------
    public function insertAudiobook($data)
    {
        return $this->booksModel->insertAudiobook($data);
    }

    public function updateAudiobook($bookId, $data)
    {
        return $this->booksModel->updateAudiobook($bookId, $data);
    }

    public function deleteAudiobookByBookId($bookId)
    {
        return $this->booksModel->deleteAudiobookByBookId($bookId);
    }

    public function getAudiobookByBookId($bookId, $contentId = null)
    {
        $audiobook = $this->booksModel->selectAudiobookByBookId($bookId);
        include __DIR__ . "/../views/includes/layouts/forms/audiobook_form.php";
    }

    public function insertAudiobookChapter($data)
    {
        return $this->booksModel->insertAudiobookChapter($data);
    }

    public function updateAudiobookChapter($chapterId, $data)
    {
        return $this->booksModel->updateAudiobookChapter($chapterId, $data);
    }

    public function deleteAudiobookChapter($chapterId)
    {
        return $this->booksModel->deleteAudiobookChapter($chapterId);
    }

    public function getAudiobookByContentId($contentId)
    {
        return $this->booksModel->selectAudiobookByContentId($contentId);
    }

    public function insertAudiobookSample($data)
    {
        return $this->booksModel->insertAudiobookSample($data);
    }

    public function updateAudiobookSample($data)
    {
        return $this->booksModel->updateAudiobookSample($data);
    }

    public function deleteAudiobookSample($data)
    {
        return $this->booksModel->deleteAudiobookSample($data);
    }
}
