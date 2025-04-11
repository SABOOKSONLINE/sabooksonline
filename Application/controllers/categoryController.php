<?php

require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../config/connection.php';

class BookController
{
    private $bookModel;

    public function __construct($Conn)
    {
        $this->bookModel = new Book($Conn);
    }

    public function showBooks()
    {
        $data = [
            'Editors Choice' => $this->bookModel->getBooksByCategory('Editors Choice'),
            'latest' => $this->bookModel->getBooksByCategory('latest'),
            'fiction' => $this->bookModel->getBooksByCategory("fiiction"),
            'children' => $this->bookModel->getBooksByCategory('children'),
        ];

        // Pass category => books to the view
        include __DIR__ . '/../views/books/category.php';
    }
}

