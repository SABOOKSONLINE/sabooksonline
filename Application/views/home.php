<?php
require_once __DIR__ . "/includes/header.php";

require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";
require_once __DIR__ . "/../models/BannerModel.php";
require_once __DIR__ . "/../controllers/BannerController.php";

require_once __DIR__ . "/layout/sectionHeading.php";

$bookController = new BookController($conn);
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="jumbotron jumbotron-lg bg-dark text-white">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4 fw-bold">Welcome to <b>SABooksOnline</b></h1>
                <div class="row">
                    <div class="col-12 col-md-7">
                        <p class="lead mb-3 text-shadow">
                            The Gateway to South African Literature. Discover stories that reflect the heart of South Africa, from township tales to historic struggles and modern voices.
                        </p>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <a class="btn btn-red me-2" href="/library" role="button">
                        Explore Library <i class="fas fa-arrow-right"></i>
                    </a>
                    <a class="btn btn-white" href="/signup" role="button">
                        Publish with Us
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
                <?php renderSectionHeading("Recommended For You", "Your Next Great Read Starts Here.", "Show more", "/library") ?>
            </div>

            <div class="book-cards mt-4" id="recommended">
                <div class="book-card-slide scroll-right">
                    <?php
                    $bookController->renderBookCardByCategory();
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
        <div class="container-fluid">
            <div class="container">
                <?php renderSectionHeading("Editor's Choice", "Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.", "Show more", "/library") ?>
            </div>

            <div class="book-cards mt-4" id="editors_choice">
                <div class="book-card-slide scroll-left">
                    <?php
                    $bookController->renderBookCardByCategory("editors choice", 10, true);
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

    <section class="section">
        <div class="container">
            <?php renderSectionHeading("Latest Collections", "Fresh Off the Press — Discover the Newest Reads.", "Show more", "/library") ?>

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
            <?php renderSectionHeading("Fiction Collections", "Escape Into Stories — Fiction That Moves You.", "Show more", "/library") ?>

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
                <?php renderSectionHeading("Children's Collections", "Rooted in Wisdom, Growing Through Stories — Empowering African Children to Read and Rise.", "Show more", "/library") ?>

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