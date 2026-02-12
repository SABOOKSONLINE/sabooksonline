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
    require __DIR__ . "/../models/ReviewsModel.php";
    require __DIR__ . "/../controllers/ReviewsController.php";

    $controller = new BookController($conn);
    $controller->renderBookView();

    $reviewsController = new ReviewsController($conn);
    $reviewsController->renderReviews($_GET['q']);

    ?>

    <?php
    // Check if there are books to show before rendering the section
    $Book = new BookModel($conn);
    $bookData = $Book->getBookById($_GET['q']);

    if (!isset($bookData['category'])) {
        $category = $bookData['CATEGORY'] ?? null;
    } else {
        $category = $bookData['category'];
    }

    // Get books by category to check if any exist (excluding current book)
    $relatedBooks = [];
    $hasRelatedBooks = false;
    
    if ($category !== null && !empty(trim($category))) {
        $allBooks = $Book->getBooksByCategory($category);
        if (!empty($allBooks) && is_array($allBooks)) {
            $currentBookId = strtolower(trim($_GET['q'] ?? ''));
            // Filter out the current book and reindex array
            $relatedBooks = array_values(array_filter($allBooks, function($book) use ($currentBookId) {
                if (empty($currentBookId)) return true; // If no current book ID, show all
                $bookId = strtolower(trim($book['CONTENTID'] ?? ''));
                return !empty($bookId) && $bookId !== $currentBookId;
            }));
            $hasRelatedBooks = !empty($relatedBooks) && count($relatedBooks) > 0;
        }
    }
    
    // Only render section if there are related books (entire section including heading is hidden when empty)
    if ($hasRelatedBooks):
    ?>
    <section class="section">
        <div class="container">
            <?php renderSectionHeading("You might also like:", "Carefully selected for their depth, relevance, and lasting impact.", "Show more", "/library") ?>

            <div class="book-cards mt-4" id="editors_choice">
                <div class="book-card-slide">
                    <?php
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
    <?php endif; ?>


    <?php
    include __DIR__ . "/layout/reviewForm.php";
    require_once __DIR__ . "/includes/payfast.php";

    require_once __DIR__ . "/includes/footer.php";
    require_once __DIR__ .  "/includes/scripts.php";
    ?>
</body>