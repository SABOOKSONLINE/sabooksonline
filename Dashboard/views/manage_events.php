<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/EventModel.php";
require_once __DIR__ . "/../controllers/EventsController.php";

include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/includes/nav.php"; ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>

                <!-- Main Content -->
                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                    <?php
                    renderHeading("Manage Events", "You can manage, add or delete your event listings.", "/dashboards/add/event", "Create New Event");

                    $deleteEvent = $_GET["delete"] ?? null;
                    $addEventStatus = $_GET["status"] ?? null;
                    $updateEvent = $_GET["update"] ?? null;

                    if ($deleteEvent == 'success') {
                        echo '<div class="alert alert-warning text-center" role="alert">Event successfully deleted!</div>';
                    } else if ($deleteEvent == 'fail') {
                        echo '<div class="alert alert-danger text-center" role="alert">Something went wrong while deleting the event!</div>';
                    }

                    if ($addEventStatus == 'success') {
                        echo '<div class="alert alert-success text-center" role="alert">Event successfully added!</div>';
                    } else if ($addEventStatus == 'fail') {
                        echo '<div class="alert alert-danger text-center" role="alert">Something went wrong while adding the event!</div>';
                    }

                    if ($updateEvent == 'success') {
                        echo '<div class="alert alert-success text-center" role="alert">Event successfully updated!</div>';
                    } else if ($updateEvent == 'fail') {
                        echo '<div class="alert alert-danger text-center" role="alert">Something went wrong while updating the event!</div>';
                    }

                    $eventsController = new EventsController($conn);
                    $eventsController->renderEvents($_SESSION["ADMIN_USERKEY"]);
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php"; ?>
</body>

</html>