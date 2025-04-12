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
                    <a class="btn btn-red flex-grow-0" href="#" role="button">
                        <span class="mr-2">EXPLORE LIBRARY</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php require "includes/banner.php" ?>

    <?php require "includes/scripts.php" ?>
</body>