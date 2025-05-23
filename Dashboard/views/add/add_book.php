<?php include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/dashboard_heading.php";

require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../../models/BookListingsModel.php";
require_once __DIR__ . "/../../controllers/BookListingsController.php";
?>

<body>
    <?php include __DIR__ . "/../includes/nav.php" ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/../includes/layouts/side-bar.php"; ?>

                <div class="col offset-lg-3 offset-xl-2 p-5 overflow-y-scroll mt-5">
                    <?php
                    $contentId = $_GET["q"] ?? "";

                    if ($contentId) {
                        renderHeading("Update Book Listing", "You can manage, add or delete your book listings.");
                    } else {
                        renderHeading("Add Book Listing", "You can manage, add or delete your book listings.");
                    }

                    $bookListingController = new BookListingController($conn);
                    $bookListingController->renderBookByContentId("62309008e164734976862309008e", $contentId);
                    ?>
                </div>
            </div>
        </div>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/scripts.php" ?>
</body>

</html>