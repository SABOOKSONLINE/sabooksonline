<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="jumbotron jumbotron-sm">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
        </div>
    </div>

    <?php
    require_once __DIR__ . "/../../database/connection.php";
    require_once __DIR__ . "/../models/Book.php";
    require_once __DIR__ . "/../controllers/BookController.php";

    $controller = new BookController($conn);
    $controller->renderBookView();
    ?>

    <section class="section">
        <div class="container">
            <h1 class="fw-bold mb-0">You might also like:</h1>

            <div class="book-cards mt-4" id="similar_book">
                <div class="book-card-slide">
                    <?php
                    require_once __DIR__ . "/../../database/connection.php";
                    require_once __DIR__ . "/../models/Book.php";
                    require_once __DIR__ . "/../controllers/BookController.php";

                    $controller = new BookController($conn);
                    $controller->renderBooksByCategory("editors choice", 10);
                    ?>

                </div>

                <div class="book-card-btn btn-right">
                    <div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <h1 class="mt-4">
                <a href="/library" class="btn btn-red text-uppercase">View more books</a>
            </h1>
        </div>
    </section>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>