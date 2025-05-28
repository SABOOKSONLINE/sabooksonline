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
        $this->booksModel->insertBook($data);
    }

    public function updateBookData($bookId, $data)
    {
        $this->booksModel->updateBook($bookId, $data);
    }

    public function deleteBookListing($contentId)
    {
        $this->booksModel->deleteBook($contentId);
    }

    public function insertAudiobook($data)
    {
        $this->booksModel->insertAudiobook($data);
    }

    public function updateAudiobook($bookId, $data)
    {
        $this->booksModel->updateAudiobook($bookId, $data);
    }

    public function deleteAudiobookByBookId($bookId)
    {
        $this->booksModel->deleteAudiobookByBookId($bookId);
    }

    public function getAudiobookByBookId($bookId, $contentId = null)
    {
        $audiobook = $this->booksModel->selectAudiobookByBookId($bookId);
        include __DIR__ . "/../views/includes/layouts/forms/audiobook_form.php";
    }

    public function insertAudiobookChapter($data)
    {
        $this->booksModel->insertAudiobookChapter($data);
    }

    public function updateAudiobookChapter($chapterId, $data)
    {
        $this->booksModel->updateAudiobookChapter($chapterId, $data);
    }

    public function deleteAudiobookChapter($chapterId)
    {
        $this->booksModel->deleteAudiobookChapter($chapterId);
    }

    public function getAudiobookByContentId($contentId)
    {
        $book = $this->booksModel->selectAudiobookByContentId($contentId);
        return $book;
    }
}
