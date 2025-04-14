<?php
require_once "includes/header.php";
?>

<body>
    <?php require_once "includes/nav.php"; ?>

    <div class="jumbotron jumbotron-lg">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4">Welcome to <b>SABooksOnline</b></h1>
                <p class="lead mb-4">The Gateway to South African Literature</p>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-red me-2" href="#" role="button">
                        EXPLORE LIBRARY
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php require_once "includes/banner.php" ?>
    </div>

    <section class="section">
        <div class="container">
            <h1 class="fw-bold mb-0">Editor's Choice</h1>
            <span class="text-muted">
                Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
            </span>

            <div class="book-cards mt-4" id="editor_choice">
                <div class="book-card-slide">
                    <?php
                    require_once __DIR__ . "/../../database/connection.php";
                    require_once __DIR__ . "/../../database/models/Book.php";

                    $book = new Book($conn);
                    $books = $book->getBooksByCategory("Editors Choice", 20);
                    $book->renderBooks($books);
                    ?>

                </div>

                <div class="book-card-btn btn-right">
                    <div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <h1 class="mt-4">
                <a href="" class="btn btn-red text-uppercase">View more books</a>
            </h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h1 class="fw-bold mb-0">Latest Collections</h1>

            <div class="book-cards mt-4" id="latest_collections">
                <div class="book-card-slide">
                    <?php
                    require_once __DIR__ . "/../../database/connection.php";
                    require_once __DIR__ . "/../../database/models/Book.php";

                    $book = new Book($conn);
                    $books = $book->getBooksByCategory("Latest Collections", 20);
                    $book->renderBooks($books);
                    ?>

                </div>

                <div class="book-card-btn btn-right">
                    <div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <h1 class="mt-4">
                <a href="" class="btn btn-red text-uppercase">View more books</a>
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
                    require_once __DIR__ . "/../../database/models/Book.php";

                    $book = new Book($conn);
                    $books = $book->getBooksByCategory("Fiction Collections", 20);
                    $book->renderBooks($books);
                    ?>

                </div>

                <div class="book-card-btn btn-right">
                    <div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <h1 class="mt-4">
                <a href="" class="btn btn-red text-uppercase">View more books</a>
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
                        require_once __DIR__ . "/../../database/models/Book.php";

                        $book = new Book($conn);
                        $books = $book->getBooksByCategory("Childrens Collection", 20);
                        $book->renderBooks($books);
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

    <?php require_once "includes/scripts.php" ?>
</body>