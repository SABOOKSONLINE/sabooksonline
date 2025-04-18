<?php
// controllers/LibraryController.php

require_once __DIR__ . '/../config/connection.php';
include_once 'models/LibraryModel.php';

class LibraryController
{
    private LibraryModel $model;

    public function __construct(mysqli $conn)
    {
        $this->model = new LibraryModel($conn);
    }

    /**
     * Load the catalogue view, filtered by category or search query.
     */
    public function loadCataloguePage(): void
    {
        // Sanitize GET parameters
        $category = isset($_GET['cat']) ? htmlspecialchars(trim($_GET['cat'])) : null;
        $search = isset($_GET['k']) ? htmlspecialchars(trim($_GET['k'])) : null;

        // Fetch data from the model
        $categories = $this->model->getCategoriesWithCounts();
        $books = $this->model->getFilteredBooks($category, $search);

        // Load the view with $categories and $books available
        include 'views/catalogue-view.php';
    }
}

// Run the controller
$controller = new LibraryController($conn);
$controller->loadCataloguePage();

// Optional: Close DB if not handled elsewhere
// $conn->close();
