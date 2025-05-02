<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";

$controller = new BookController($conn);
$category = $_GET['category'] ?? null;
$page = $_GET['page'] ?? 1;

// Get keyword and fetch books
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

        <?php if (!$category && $keyword): ?>
            <h5 class="mt-3">Search results for: <strong><?= htmlspecialchars($keyword) ?></strong></h5>
        <?php endif; ?>

        <?php if ($category && !$keyword): ?>
            <h5 class="mt-3">Category selected: <strong><?= htmlspecialchars($category) ?></strong></h5>
        <?php endif; ?>

        <div class="row py-3">
            <?php
            if ($category && !$keyword) {
                $controller->renderLibraryByCategory($category, $page);
            } else if (!$category && $keyword) {
                $controller->RenderSearchedBooks($keyword);
            } else {
                $controller->renderAllBooks($page);
            }
            ?>
        </div>

        <div class="py-3">
            <nav aria-label="Page navigation">
                <?php
                $controller->renderPagination();
                ?>
            </nav>
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php"; ?>
    <?php require_once __DIR__ . "/includes/scripts.php"; ?>
</body>
