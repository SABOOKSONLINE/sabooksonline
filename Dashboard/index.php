<?php
include __DIR__ . "/views/includes/header.php";
include __DIR__ . "/views/includes/layouts/card.php";
include __DIR__ . "/views/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/views/includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/views/includes/layouts/side-bar.php" ?>

                <div class="col offset-lg-3 offset-xl-2 p-5 hv-100 overflow-y-scroll mt-5">
                    <?php renderHeading("Dashboard", "A Comprehensive Overview of Your Dashboard Metrics and Insights", "#", "Print to PDF") ?>


                    <div class="row">
                        <?php
                        require_once __DIR__ . "/database/connection.php";
                        require_once __DIR__ . "/controllers/AnalysisController.php";

                        $analysisController = new AnalysisController($conn);
                        $user_id = "62309008e164734976862309008e";

                        $titlesCount = $analysisController->getTitlesCount($user_id);

                        renderAnalysisCard("Downloads", "1,240", "fas fa-download");
                        renderAnalysisCard("Listens", "980", "fas fa-headphones");
                        renderAnalysisCard("Revenue", "R18,500", "fas fa-money-bill");
                        renderAnalysisCard("Titles", $titlesCount, "fas fa-book");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/views/includes/scripts.php" ?>
</body>

</html>