<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="container py-4 ">
        <h1 class="fw-bold mb-0">Library</h1>
        <span class="text-muted">
            The Never-Closing Library
        </span>

        <div class="category-container py-3">
            <div class="">
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
                <a href="#" class="category-link">Category</a>
            </div>
        </div>

        <div class="row">
            <?php
            require_once __DIR__ . "/../../database/connection.php";
            require_once __DIR__ . "/../models/Book.php";
            require_once __DIR__ . "/../controllers/BookController.php";
            allBooks($conn, 20);
            ?>

        </div>
        <div class="py-3">
            <a href="" class="btn btn-red gap">
                Show More
                <i class="fas fa-angle-down"></i>
            </a>

        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>