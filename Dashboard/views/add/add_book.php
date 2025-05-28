<?php
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/dashboard_heading.php";

require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../../models/BookListingsModel.php";
require_once __DIR__ . "/../../controllers/BookListingsController.php";

$bookListingController = new BookListingController($conn);
?>

<body>
    <?php include __DIR__ . "/../includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/../includes/layouts/side-bar.php"; ?>

                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                    <?php
                    $contentId = $_GET["id"] ?? null;
                    $userKey = $_SESSION["ADMIN_USERKEY"];

                    if (isset($_GET["id"]) && !empty($_GET["id"])) {
                        renderHeading("Update Book Listing", "You can manage, add or delete your book listings.", "/dashboards/add/audiobook/$contentId", "Add Audiobook");
                    } else {
                        renderHeading("Add Book Listing", "You can manage, add or delete your book listings.");
                    }

                    $bookListingController->renderBookByContentId($userKey, $contentId);
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/scripts.php" ?>
</body>

</html>