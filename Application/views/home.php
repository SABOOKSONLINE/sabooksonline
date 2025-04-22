<?php
require_once __DIR__ . "/includes/header.php";
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
        <?php require_once __DIR__ . "/includes/banner.php" ?>
    </div>

    <section class="section" id="stylish-section">
        <div class="container">
            <h1 class="fw-bold mb-0">Editor's Choice</h1>
            <span class="text-muted">
                Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
            </span>

            <div class="book-cards mt-4" id="editor_choice">
                <div class="book-card-slide">
                    <?php
                    require_once __DIR__ . "/../../database/connection.php";
                    require_once __DIR__ . "/../models/Book.php";
                    require_once __DIR__ . "/../controllers/BookController.php";

                    $controller = new BookController($conn);
                    $controller->renderBooksByCategory("editors choice", 6);
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

    <section class="section bg-off-white">
        <div class="container">
            <h1 class="fw-bold mb-0">Latest Collections</h1>

            <div class="book-cards mt-4" id="latest_collections">
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

    <section class="section">
        <div class="container">
            <h1 class="fw-bold mb-0">Fiction Collections</h1>

            <div class="book-cards mt-4" id="fiction_collections">
                <div class="book-card-slide">
                    <?php
                    require_once __DIR__ . "/../../database/connection.php";
                    require_once __DIR__ . "/../models/Book.php";
                    require_once __DIR__ . "/../controllers/BookController.php";

                    $controller = new BookController($conn);
                    $controller->renderBooksByCategory("fiction collections", 10);
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

    <section class="section bg-children">
        <div class="container">
            <div>
                <h1 class="fw-bold mb-0">Children's Collections</h1>
                <div class="text-muted">
                    Rooted in Wisdom, Growing Through Stories — Empowering African Children to Read and Rise.
                </div>

                <div class="book-cards mt-4" id="childrens_collections">
                    <div class="book-card-slide">
                        <?php
                        require_once __DIR__ . "/../../database/connection.php";
                        require_once __DIR__ . "/../models/Book.php";
                        require_once __DIR__ . "/../controllers/BookController.php";

                        $controller = new BookController($conn);
                        $controller->renderBooksByCategory("childrens collection", 10);
                        ?>

                    </div>

                    <div class="book-card-btn btn-right">
                        <div>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="" class="btn btn-red text-uppercase">View more books</a>
                </div>
            </div>
        </div>
    </section>

    <?php include_once __DIR__ . "/includes/newsLetter.php" ?>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>