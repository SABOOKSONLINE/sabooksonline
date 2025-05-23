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

        if ($books) {
            include __DIR__ . "/../views/includes/layouts/tables/listing_table.php";
        }
    }

    public function renderBookByContentId($userId, $contentId)
    {
        $book = $this->booksModel->selectBookByContentId($userId, $contentId);

        include __DIR__ . "/../views/includes/layouts/forms/book_form.php";
    }

    public function insertBookData($data)
    {
        $this->booksModel->insertBook($data);
    }

    public function updateBookData($contentId, $data)
    {
        $this->booksModel->updateBook($contentId, $data);
    }

    public function deleteBookListing($contentId)
    {
        $this->booksModel->deleteBook($contentId);
    }
}
