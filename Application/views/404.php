<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="container py-5 text-center">
        <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
            <div class="col-lg-8">
                <h1 class="display-1 text-danger fw-bold mb-3">404</h1>
                <h2 class="mb-4">Page Not Found</h2>
                <p class="lead mb-4">
                    Oops! The page you are looking for doesn't exist or has been moved.<br>
                    Please check the URL or return to the homepage.
                </p>
                <a href="/" class="btn btn-primary btn-lg mt-3">Go to Homepage</a>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>
    <?php require_once __DIR__ . "/includes/scripts.php" ?>
</body>