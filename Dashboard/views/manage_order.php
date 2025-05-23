<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/ServicesModel.php";
require_once __DIR__ . "/../controllers/ServicesController.php";

include __DIR__ . "/../views/includes/layouts/card.php";
include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/includes/nav.php"; ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>

                <div class="col offset-lg-3 offset-xl-2 p-5 hv-100 overflow-y-scroll mt-5">
                    <?php
                    renderHeading("Manage Orders", "You can manage and view your online orders.");
                    ?>

                    <div class="row">
                        <?php
                        renderAnalysisCard("Net Income", "R 0", "fas fa-money-bill");
                        renderAnalysisCard("Net Income", "R 0", "fas fa-money-bill");
                        renderAnalysisCard("Net Income", "R 0", "fas fa-money-bill");
                        renderAnalysisCard("Net Income", "R 0", "fas fa-money-bill");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php"; ?>
</body>

</html>