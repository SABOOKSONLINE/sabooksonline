<?php
require "includes/header.php";
?>

<body>
    <?php require "includes/nav.php"; ?>

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
        <?php require "includes/banner.php" ?>
    </div>

    <section class="section">
        <div class="container">
            <div>
                <h1 class="fw-bold mb-0">Editor's Choice</h1>
                <span class="text-muted">
                    Chosen by Our Editors for Their Impact, Insight, and the Power to Stay With You.
                </span>
            </div>

            <div class="owl-carousel owl-theme" id="choice">

                <?php
                include_once '../../database/connection.php';
                include_once '../../database/models/Book.php';

                $book = new Book($conn);
                $books = $book->getBooksByCategory("Editors Choice", 20);
                $book->renderBooks($books);
                ?>

            </div>
            <!-- /carousel -->
        </div>
    </section>

    <?php require "includes/scripts.php" ?>

</body>