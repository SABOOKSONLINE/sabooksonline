<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BookListingController
{
    private $booksModel;

    public function __construct($conn)
    {
        $this->booksModel = new BookListingsModel($conn);
    }

    public function renderBookListing($userId)
    {
        $books = $this->booksModel->selectBooksByUserId($userId);
        include __DIR__ . "/../views/includes/layouts/tables/listing_table.php";
    }

    public function renderBookByContentId($userKey, $contentId)
    {
        $book = $this->booksModel->selectBookByContentId($userKey, $contentId);
        include __DIR__ . "/../views/includes/layouts/forms/book_form.php";
    }

    public function getAdminName($userKey)
    {
        $this->booksModel->getAdminName($userKey);
    }

    public function insertBookData($data)
    {
        return $this->booksModel->insertBook($data);
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
