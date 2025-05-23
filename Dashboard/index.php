<?php
include __DIR__ . "/views/includes/header.php";
include __DIR__ . "/views/includes/layouts/card.php";
include __DIR__ . "/views/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/views/includes/nav.php" ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-xl-2 position-fixed bg-light vh-100 pt-5">
                    <?php include __DIR__ . "/views/includes/layouts/side-bar.php" ?>
                </div>


                <div class="col offset-lg-3 offset-xl-2 p-5 hv-100 overflow-y-scroll mt-5">
                    <?php renderHeading("Dashboard", "A Comprehensive Overview of Your Dashboard Metrics and Insights", "#", "Print to PDF") ?>


                    <div class="row">
                        <?php
                        // require_once __DIR__ . "/database/connection.php";
                        // require_once __DIR__ . "/controllers/AnalysisController.php";

                        // $analysisController = new AnalysisController($con);
                        // $user_id = "62309008e164734976862309008e";



                        // renderAnalysisCard("Total Customers", "R " + $totalCustomers[], "fas fa-money-bill");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php" ?>
</body>

</html>