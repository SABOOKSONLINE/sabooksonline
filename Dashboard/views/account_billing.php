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

                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                    <?php
                    renderHeading("Account Billing", "Manage your billing information, invoices, and payment methods here.");
                    ?>

                    <div class="alert alert-info shadow-sm mb-4" role="alert">
                        <h5 class="alert-heading mb-2">Account Billing</h5>
                        <p class="mb-1">This is where your billing details and payment history will appear.</p>
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