<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="container py-4 ">
        <h1 class="fw-bold mb-0">Service Providers</h1>

        <div class="row py-4">
            <?php
            require_once __DIR__ . "/../Config/connection.php";
            require_once __DIR__ . "/../models/ServiceProviderModel.php";
            require_once __DIR__ . "/../controllers/ServiceProviderController.php";

            $providerController = new ServiceProviderController($conn);
            $providerController->renderServiceProvider("service-provider")
            ?>
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>