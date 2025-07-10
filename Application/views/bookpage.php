<?php
require_once __DIR__ . "/includes/header.php";

require_once __DIR__ . "/layout/sectionHeading.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php";
    ?>

    <div class="jumbotron jumbotron-sm">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
        </div>
    </div>

    <?php
    require __DIR__ . "/../../database/connection.php";
    require __DIR__ . "/../models/BookModel.php";
    require __DIR__ . "/../controllers/BookController.php";

    $controller = new BookController($conn);
    $controller->renderBookView();
    ?>

    <section class="section">
        <div class="container-fluid">
            <div class="container">
                <?php renderSectionHeading("You might also like:", "Carefully selected for their depth, relevance, and lasting impact.", "Show more", "/library") ?>
            </div>

            <div class="book-cards mt-4" id="editors_choice">
                <div class="book-card-slide">
                    <?php
                    $Book = new BookModel($conn);
                    $bookData = $Book->getBookById($_GET['q']);

                    if (!isset($bookData['category'])) {
                        $category = $bookData['CATEGORY'] ?? null;

                        if ($category === null) {
                            die("Book exists but has no category assigned");
                        }
                    } else {
                        $category = $bookData['category'];
                    }

                    // $controller = new BookController($conn);
                    $controller->renderBooksByCategory($category, 10);
                    ?>
                </div>

                <div class="book-card-btn btn-right">
                    <div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>