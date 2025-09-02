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

        <br class="my-3">

        <div class="row py-3">
            <?php
            $books = $controller->getAllBooks();

            // COMING SOON
            // $books = [];
            require_once __DIR__ . "/books/catalogueView.php";
            ?>

            <?php if (empty($books)): ?>
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-start" role="alert">
                    <div>
                        <strong>Academic Books Catalog
                            (Coming Soon).
                        </strong>
                        <div class="mt-1">
                            Try exploring our <a href="/library">library</a>.
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php"; ?>
    <?php require_once __DIR__ . "/includes/scripts.php"; ?>
</body>