<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/ServicesModel.php";
require_once __DIR__ . "/../controllers/ServicesController.php";

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
                    renderHeading("Manage Services", "You can manage, add or delete your service listings.", "add/add_services.php", "Create New Service");

                    $deleteService = $_GET["delete"] ?? null;
                    $addServiceStatus = $_GET["status"] ?? null;
                    $updateService = $_GET["update"] ?? null;

                    if ($deleteService == 'success') {
                        echo '
                        <div class="alert alert-warning text-center" role="alert">
                            Service succesfully deleted!
                        </div>
                        ';
                    } else if ($deleteService == 'fail') {
                        echo '
                        <div class="alert alert-danger text-center" role="alert">
                            Something went wrong while deleting service!
                        </div>
                        ';
                    }

                    if ($addServiceStatus == 'success') {
                        echo '
                        <div class="alert alert-success text-center" role="alert">
                            Service succesfully added!
                        </div>
                        ';
                    } else if ($addServiceStatus == 'fail') {
                        echo '
                        <div class="alert alert-danger text-center" role="alert">
                            Something went wrong while adding service!
                        </div>
                        ';
                    }

                    if ($updateService == 'success') {
                        echo '
                        <div class="alert alert-success text-center" role="alert">
                            Service succesfully Updated!
                        </div>
                        ';
                    } else if ($updateService == 'fail') {
                        echo '
                        <div class="alert alert-danger text-center" role="alert">
                            Something went wrong while updating service!
                        </div>
                        ';
                    }

                    $serviceController = new ServicesController($conn);
                    $serviceController->renderServicesByUserId("64c971169092344964c971b98");
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php"; ?>
</body>

</html>