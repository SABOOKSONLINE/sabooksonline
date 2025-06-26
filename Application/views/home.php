<?php
require_once __DIR__ . "/includes/header.php";

require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";
require_once __DIR__ . "/../models/BannerModel.php";
require_once __DIR__ . "/../controllers/BannerController.php";

$bookController = new BookController($conn);
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="jumbotron jumbotron-lg">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4">Welcome to <b>SABooksOnline</b></h1>
                <p class="lead mb-4">The Gateway to South African Literature</p>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-red me-2" href="/library" role="button">
                        EXPLORE LIBRARY
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php
        $bannerController = new BannerController($conn);
        $bannerController->renderBanner("home");
        ?>
    </div>

    <section class="section" id="stylish-section">
        <div class="container-fluid">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <!-- Left Content -->
                    <div class="col-md-8 col-12 mb-3 mb-md-0">
                        <h1 class="fw-bold mb-1">Trending Books</h1>
                        <span class="text-muted">
                            Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
                        </span>
                    </div>

                    <!-- Right Button -->
                    <div class="col-md-auto col-12 text-md-end">
                        <a href="/library" class="text-decoration-none text-muted fw-semibold g-3">
                            Show more
                            <div class="fas fa-arrow-right"></div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="book-cards mt-4" id="editor_choice">
                <div class="book-card-slide">
                    <?php
                    $bookController->renderBookByViews();
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

    <section class="section" id="stylish-section">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <!-- Left Content -->
                <div class="col-md-8 col-12 mb-3 mb-md-0">
                    <h1 class="fw-bold mb-1">Editor's Choice</h1>
                    <span class="text-muted">
                        Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
                    </span>
                </div>

                <!-- Right Button -->
                <div class="col-md-auto col-12 text-md-end">
                    <a href="/library" class="text-decoration-none text-muted fw-semibold g-3">
                        Show more
                        <div class="fas fa-arrow-right"></div>
                    </a>
                </div>
            </div>

            <div class="book-cards mt-4" id="editor_choice">
                <div class="book-card-slide">
                    <?php
                    $bookController->renderListingsByCategory("editors choice", 6);
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

    <?php include_once __DIR__ . "/includes/newsLetter.php" ?>

    <section class="section bg-off-white">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <!-- Left Content -->
                <div class="col-md-8 col-12 mb-3 mb-md-0">
                    <h1 class="fw-bold mb-1">Latest Collections</h1>
                    <span class="text-muted">
                        Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
                    </span>
                </div>

                <!-- Right Button -->
                <div class="col-md-auto col-12 text-md-end">
                    <a href="/library" class="text-decoration-none text-muted fw-semibold g-3">
                        Show more
                        <div class="fas fa-arrow-right"></div>
                    </a>
                </div>
            </div>

            <div class="book-cards mt-4" id="latest_collections">
                <div class="book-card-slide">
                    <?php
                    $bookController->renderListingsByCategory("Latest Collections", 10);
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

    <section class="section">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <!-- Left Content -->
                <div class="col-md-8 col-12 mb-3 mb-md-0">
                    <h1 class="fw-bold mb-1">Fiction Collections</h1>
                    <span class="text-muted">
                        Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
                    </span>
                </div>

                <!-- Right Button -->
                <div class="col-md-auto col-12 text-md-end">
                    <a href="/library" class="text-decoration-none text-muted fw-semibold g-3">
                        Show more
                        <div class="fas fa-arrow-right"></div>
                    </a>
                </div>
            </div>

            <div class="book-cards mt-4" id="fiction_collections">
                <div class="book-card-slide">
                    <?php
                    $bookController->renderListingsByCategory("fiction collections", 10);
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

    <section class="section bg-children">
        <div class="container">
            <div>
                <div class="row align-items-center justify-content-between">
                    <!-- Left Content -->
                    <div class="col-md-8 col-12 mb-3 mb-md-0">
                        <h1 class="fw-bold mb-1">Children's Collections</h1>
                        <span class="text-muted">
                            Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
                        </span>
                    </div>

                    <!-- Right Button -->
                    <div class="col-md-auto col-12 text-md-end">
                        <a href="/library" class="text-decoration-none text-muted fw-semibold g-3">
                            Show more
                            <div class="fas fa-arrow-right"></div>
                        </a>
                    </div>
                </div>

                <div class="book-cards mt-4" id="childrens_collections">
                    <div class="book-card-slide">
                        <?php
                        $bookController->renderListingsByCategory("childrens collection", 10);
                        ?>

                    </div>

                    <div class="book-card-btn btn-right">
                        <div>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>