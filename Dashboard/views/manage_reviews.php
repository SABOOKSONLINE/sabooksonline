<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/ReviewsModel.php";
require_once __DIR__ . "/../controllers/ReviewsController.php";

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
                <div class="col offset-lg-3 offset-xl-2 p-5 overflow-y-scroll mt-5">
                    <?php
                    renderHeading("Manage Reviews", "Track feedback and performance insights on your uploaded content.");

                    // Add pop alerts for delete success/fail
                    $deleteReview = $_GET["delete"] ?? null;
                    if ($deleteReview == 'success') {
                        echo '<div class="alert alert-warning text-center" role="alert">Review successfully deleted!</div>';
                    } else if ($deleteReview == 'fail') {
                        echo '<div class="alert alert-danger text-center" role="alert">Something went wrong while deleting the review!</div>';
                    }

                    $reviewsController = new ReviewsController($conn);
                    $reviewsController->renderReviewsByUserKey("64aa6e168889112464aa6ef49");
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php"; ?>
</body>

</html>