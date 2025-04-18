<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../config/connection.php';

class BookController
{
    private $bookModel;

    public function __construct($conn)
    {
        $this->bookModel = new Book($conn);
    }

    public function showBooks()
    {
        // Define categories
        $categories = ['Editors Choice', 'latest', 'fiction', 'children'];

        // Prepare data for each category
        $data = [];
        foreach ($categories as $category) {
            $books = $this->bookModel->getBooksByCategory($category);
            // Check if books were returned
            if ($books) {
                $data[$category] = $books;
            } else {
                // Optionally, handle the case when no books are found for a category
                $data[$category] = [];
            }
        }

        // Pass category => books to the view
        include __DIR__ . '/../views/books/category.php';
    }
}
