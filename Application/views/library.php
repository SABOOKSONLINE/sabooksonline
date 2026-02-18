<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";
require_once __DIR__ . "/../models/BannerModel.php";
require_once __DIR__ . "/../controllers/BannerController.php";

$controller = new BookController($conn);

$category = $_GET['category'] ?? null;
$page = $_GET['page'] ?? 1;
$keyword = $_GET['k'] ?? null;

?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>


    <div class="container py-4">
        <h1 class="fw-bold mb-0">Library</h1>
        <span class="text-muted">The Never-Closing Library</span>

        <div class="category-container py-3">
            <div class="">
                <?php
                $controller->renderCategories();
                ?>
            </div>
            <span class="btn category-collapse-btn">
                <i class="fas fa-angle-down"></i>
            </span>
        </div>

        <?php
        include_once __DIR__ . "/includes/banner.php";
        ?>

        <div class="py-3">
            <?php
            // Get filter parameters
            $filters = [
                'search' => $_GET['search'] ?? ($keyword ?? ''),
                'category' => $_GET['category'] ?? ($category ?? ''),
                'subject' => $_GET['subject'] ?? '',
                'min_price' => $_GET['min_price'] ?? '',
                'max_price' => $_GET['max_price'] ?? '',
                'sort' => $_GET['sort'] ?? 'newest'
            ];
            
            // Get filtered books (combines regular and academic books)
            $books = $controller->getAllBooksWithFilters($filters);

            if (!empty($books) || !empty(array_filter($filters))) {
                require_once __DIR__ . "/books/libraryFilters.php";
            } else {
            ?>
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-start" role="alert">
                    <div>
                        <strong>No Books Available</strong>
                        <div class="mt-1">
                            Check back soon or explore our <a href="/library/academic">academic collection</a>.
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