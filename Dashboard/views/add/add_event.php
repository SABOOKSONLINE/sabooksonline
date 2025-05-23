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
                <?php include __DIR__ . "/../includes/layouts/side-bar.php"; ?>


                <div class="col offset-lg-3 offset-xl-2 p-5 overflow-y-scroll mt-5">
                    <?php
                    $eventId = $_GET["id"] ?? "";

                    if ($eventId) {
                        renderHeading("Update Event", "You may add a new event and select the new options.");
                    } else {
                        renderHeading("Create New Event", "You may add a new event and select the new options.");
                    }

                    $eventsController = new EventsController($conn);
                    $eventsController->renderEventByContentId("65ca14170774224165ca14219", $eventId);
                    ?>
                </div>
            </div>
        </div>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/scripts.php" ?>
</body>

</html>