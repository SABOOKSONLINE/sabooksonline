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
        <div class="row align-items-center mb-4">
            <!-- Image on the left -->
            <div class="col-md-5 col-lg-4">
                <div class="education-banner">
                    <img src="/public/images/Education-cuate.svg" alt="Education" class="education-banner-img">
                </div>
            </div>
            
            <!-- Content on the right -->
            <div class="col-md-7 col-lg-8">
                <h1 class="fw-bold mb-0">Academic Books</h1>
                <span class="text-muted">Explore a world of knowledge and learning</span>
            </div>
        </div>

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
    <?php require_once __DIR__ . "/includes/payfast.php" ?>

    <?php require_once __DIR__ . "/includes/footer.php"; ?>
    <?php require_once __DIR__ . "/includes/scripts.php"; ?>
</body>