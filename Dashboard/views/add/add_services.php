<?php
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/dashboard_heading.php";

require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../../models/ServicesModel.php";
require_once __DIR__ . "/../../controllers/ServicesController.php";
?>

<body>
    <?php include __DIR__ . "/../includes/nav.php"; ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-xl-2 position-fixed bg-light vh-100 pt-5">
                    <?php include __DIR__ . "/../includes/layouts/side-bar.php"; ?>
                </div>

                <div class="col offset-lg-3 offset-xl-2 p-5 overflow-y-scroll mt-5">
                    <?php
                    $serviceId = $_GET["id"] ?? "";

                    if ($serviceId) {
                        renderHeading("Update Service", "You may update your existing service information.");
                    } else {
                        renderHeading("Create New Service", "You may add a new service with its pricing options.");
                    }

                    $servicesController = new ServicesController($conn);
                    $servicesController->renderServiceById("64c971169092344964c971b98", $serviceId);
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/scripts.php"; ?>
</body>

</html>