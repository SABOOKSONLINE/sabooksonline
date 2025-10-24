<?php
require_once __DIR__ . "/../includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/../includes/nav.php"; ?>

    <div class="container py-5 text-center">
        <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
            <div class="col-lg-8">
                <h1 class="mb-4 text-success fw-bolder">Check your email to verify your account.</h1>
                <p class="lead mb-4">
                    We've sent a verification link to your email address. Please click the link to activate your account.<br>
                    If you don't see the email, please check your spam folder.
                </p>
                <a href="/" class="btn btn-primary btn-lg mt-3">Go to Homepage</a>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../includes/footer.php" ?>
    <?php require_once __DIR__ . "/../includes/scripts.php" ?>
    <!-- Google tag (gtag.js) event -->
    <script>
        gtag('event', 'ads_conversion_About_Us_1', {});
    </script>
</body>