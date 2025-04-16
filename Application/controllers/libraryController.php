<?php
require_once __DIR__ . '/../config/connection.php';
include_once 'models/LibraryModel.php';

class LibraryController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new LibraryModel($conn);
    }

    public function loadCataloguePage()
    {
        $category = $_GET['cat'] ?? null;
        $search = $_GET['k'] ?? null;

        $categories = $this->model->getCategoriesWithCounts();
        $books = $this->model->getFilteredBooks($category, $search);

        include 'views/catalogue-view.php';
    }
}

$controller = new LibraryController($conn);
$controller->loadCataloguePage();
