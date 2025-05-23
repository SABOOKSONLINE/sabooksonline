<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/includes/nav.php" ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-xl-2 position-fixed bg-light vh-100 pt-5">
                    <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>
                </div>


                <div class="col offset-lg-3 offset-xl-2 p-5 overflow-y-scroll mt-5">
                    <?php renderHeading("Manage Book Listings", "You can manage, add or delete your book listings.", "add/add_book.php", "Add New Book");


                    $bookListingController = new BookListingController($conn);
                    $bookListingController->renderBookListing("62309008e164734976862309008e");
                    ?>

                    <!-- <div class="row p-3">
                        <div class="alert alert-success" role="alert">
                            Submit your PDF Manuscript and MP3 Audio Book File to our Mobile App! Submissions can take up-to 5 working days.
                        </div>
                    </div> -->

                    <!-- 62309008e164734976862309008e -->


                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php" ?>
</body>

</html>