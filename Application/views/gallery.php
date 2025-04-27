<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="jumbotron jumbotron-md">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4">Gallery</h1>
                <p class="lead mb-4">Discover Worlds Within Pages, Your Gallery of Great Reads!</p>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-4 p-3">
                <div class="">
                    <img class="img-fluid" src="../../cms-data/profile-images/-64a8141688736908.jpeg" alt="">
                </div>
            </div>

            <div class="col-md-4 p-3">
                <div class="">
                    <img class="img-fluid" src="../../cms-data/profile-images/-64a8141688736908.jpeg" alt="">
                </div>
            </div>

            <div class="col-md-4 p-3">
                <div class="">
                    <img class="img-fluid" src="../../cms-data/profile-images/-64a8141688736908.jpeg" alt="">
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>