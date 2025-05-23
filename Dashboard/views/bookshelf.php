<?php
require_once __DIR__ . "/../database/connection.php";

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
                    renderHeading("My Bookshelf", "View and manage all your saved and purchased books here.");
                    ?>

                    <div class="alert alert-info shadow-sm mb-4" role="alert">
                        <h5 class="alert-heading mb-2">Your Books</h5>
                        <p class="mb-1">This is where your bookshelf will appear. You can add, remove, or view your books.</p>
                        <hr>
                        <p class="mb-0 text-warning"><i class="fas fa-tools me-1"></i> This feature is currently under development. Please check back soon!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php"; ?>
</body>

</html>