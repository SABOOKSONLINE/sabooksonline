<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="container py-5 text-center">
        <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
            <div class="col-lg-8">
                <h1 class="display-1 text-danger fw-bold mb-3">401</h1>
                <h2 class="mb-4">Access Denied</h2>
                <p class="lead mb-4">
                    You don't have permission to view this page.
                </p>
                <a href="/" class="btn btn-primary btn-lg mt-3">Go to Homepage</a>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>
    <?php require_once __DIR__ . "/includes/scripts.php" ?>
</body>