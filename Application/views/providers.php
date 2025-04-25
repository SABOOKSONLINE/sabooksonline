<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="container py-4 ">
        <h1 class="fw-bold mb-0">Service Providers</h1>

        <div class="container my-4">
            <div class="d-flex justify-content-between align-items-center p-4 bg-red text-white rounded">
                <div>
                    <h4 class="mb-1">Become a service provider today!</h4>
                    <p class="mb-0">Join Our Network of Providers, & Unlock Opportunities as a Service Provider.</p>
                </div>
            </div>
        </div>


        <div class="row py-4  d-flex align-items-stretch">
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