<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/BookModel.php";

class BooksController extends Controller
{
    private BookModel $booksModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->booksModel = new BookModel($conn);
    }

    public function books(): void
    {
        $books = $this->booksModel->getFullBooks();

        $this->render("books", [
            "title" => "Books",
            "books" => $books
        ]);
    }
}
