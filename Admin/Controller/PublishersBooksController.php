<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/BookModel.php";

class PublishersBooksController extends Controller
{
    private BookModel $bookModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->bookModel = new BookModel($conn);
    }

    public function books(): void
    {
        // Read from books table (not posts table)
        $books = $this->bookModel->getBooksTable();
        
        $this->render("publishers_books", [
            "books" => $books
        ]);
    }
}
