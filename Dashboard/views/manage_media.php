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

                    renderHeading(
                        "Your Publication Catalogue",
                        "Easily view, edit, or remove magazines and newspapers you’ve published.",
                        "/dashboards/add/media",
                        "Publish New Publication"
                    );

                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php" ?>
</body>

</html>