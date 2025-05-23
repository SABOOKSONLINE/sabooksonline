<?php
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/dashboard_heading.php";

require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../../models/EventModel.php";
require_once __DIR__ . "/../../controllers/EventsController.php";
?>

<body>
    <?php include __DIR__ . "/../includes/nav.php" ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-xl-2 position-fixed bg-light vh-100 pt-5">
                    <?php include __DIR__ . "/../includes/layouts/side-bar.php" ?>
                </div>


                <div class="col offset-lg-3 offset-xl-2 p-5 overflow-y-scroll mt-5">
                    <?php
                    $contentId = $_GET["q"] ?? "";

                    if ($contentId) {
                        renderHeading("Update Event", "You may add a new event and select the new options.");
                    } else {
                        renderHeading("Create New Event", "You may add a new event and select the new options.");
                    }

                    $eventsController = new EventsController($conn);
                    $eventsController->renderEventByContentId("65ca14170774224165ca14219", $contentId);
                    ?>
                </div>
            </div>
        </div>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/scripts.php" ?>
</body>

</html>