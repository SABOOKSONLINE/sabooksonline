<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>


                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                    <?php
                    renderHeading("Manage Book Listings", "You can manage, add or delete your book listings.", "/dashboards/listings/new", "Add New Book");

                    $deleteBook = $_GET["delete"] ?? null;
                    $addBookStatus = $_GET["status"] ?? null;
                    $updateBook = $_GET["update"] ?? null;

                    if ($deleteBook === 'success') {
                        echo '
                        <div class="alert alert-warning text-center" role="alert">
                            Book successfully deleted!
                        </div>
                        ';
                    } else if ($deleteBook === 'fail') {
                        echo '
                        <div class="alert alert-danger text-center" role="alert">
                            Something went wrong while deleting book!
                        </div>
                        ';
                    }

                    if ($addBookStatus === 'success') {
                        echo '
                        <div class="alert alert-success text-center" role="alert">
                            Book successfully added!
                        </div>
                        ';
                    } else if ($addBookStatus === 'fail') {
                        echo '
                        <div class="alert alert-danger text-center" role="alert">
                            Something went wrong while adding book!
                        </div>
                        ';
                    }

                    if ($updateBook === 'success') {
                        echo '
                        <div class="alert alert-success text-center" role="alert">
                            Book successfully updated!
                        </div>
                        ';
                    } else if ($updateBook === 'fail') {
                        echo '
                        <div class="alert alert-danger text-center" role="alert">
                            Something went wrong while updating book!
                        </div>
                        ';
                    }

                    $userKey = $_SESSION["ADMIN_USERKEY"];

                    $bookListingController = new BookListingController($conn);
                    $bookListingController->renderBookListing($userKey);
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php" ?>
</body>

</html>