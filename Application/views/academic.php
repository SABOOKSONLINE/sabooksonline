<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/AcademicBookModel.php";
require_once __DIR__ . "/../controllers/AcademicBookController.php";

$controller = new AcademicBookController($conn);
?>

<body class="d-flex flex-column min-vh-100">
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="container py-4 flex-grow-1">
        <h1 class="fw-bold mb-0">Academic Books</h1>
        <span class="text-muted">Explore a world of knowledge and learning</span>

        <div class="py-3">
            <?php
            // Get filter parameters
            $filters = [
                'search' => $_GET['search'] ?? '',
                'subject' => $_GET['subject'] ?? '',
                'level' => $_GET['level'] ?? '',
                'language' => $_GET['language'] ?? '',
                'min_price' => $_GET['min_price'] ?? '',
                'max_price' => $_GET['max_price'] ?? '',
                'sort' => $_GET['sort'] ?? 'newest'
            ];
            
            // Get filtered books
            $books = $controller->getAllBooks($filters);

            if (!empty($books) || !empty(array_filter($filters))) {
                require_once __DIR__ . "/books/academicFilters.php";
            } else {
            ?>
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-start" role="alert">
                    <div>
                        <strong>No Academic Books Available</strong>
                        <div class="mt-1">
                            Check back soon or explore our <a href="/library">main library</a>.
                        </div>
                    </div>
                </div>
            <?php 
            }
            ?>
        </div>
    </div>
    <?php require_once __DIR__ . "/includes/payfast.php" ?>

    <?php require_once __DIR__ . "/includes/footer.php"; ?>
    <?php require_once __DIR__ . "/includes/scripts.php"; ?>
</body>