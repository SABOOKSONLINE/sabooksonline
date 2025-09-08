<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";

class PagesController extends Controller
{
    private BookModel $bookModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->bookModel = new BookModel($conn);
    }

    public function pages(): void
    {
        $listings = $this->bookModel->getBooksListings();

        $this->render("homePage", [
            "listings" => $listings
        ]);
    }
}
