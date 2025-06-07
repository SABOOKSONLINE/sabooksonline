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
                        require_once __DIR__ . "/models/UserModel.php";


                        $analysisController = new AnalysisController($conn);
                        $userKey = $_SESSION["ADMIN_USERKEY"];
                        $email = $_SESSION["ADMIN_EMAIL"];


                        $titlesCount = $analysisController->getTitlesCount($userKey);
                        $subscriptionDetails = $analysisController->viewSubscription($userKey);
                        $bookView = $analysisController->getBookViews($userKey);
                        $profileView = $analysisController->getProfileViews($userKey);
                        $serviceView = $analysisController->getServiceViews($_SESSION['ADMIN_ID']); // optionally add date range
                        $eventView = $analysisController->getEventViews($userKey);
                        $downloads = $analysisController->getDownloadsByEmail($email);

                        // Time-based analytics
                        $bookViewsByMonthYear = $analysisController->getBookViewsByMonthYear($userKey);
                        $profileViewsByMonthYear = $analysisController->getProfileViewsByMonthYear($userKey);
                        $serviceViewsByMonthYear = $analysisController->getServiceViewsByMonthYear($userKey);
                        $eventViewsByMonthYear = $analysisController->getEventViewsByMonthYear($userKey);

                        // Geographic analytics
                        $bookViewsByCountry = $analysisController->getBookViewsByCountry($userKey);
                        $bookViewsByProvince = $analysisController->getBookViewsByProvince($userKey);
                        $bookViewsByCity = $analysisController->getBookViewsByCity($userKey);



                        renderAnalysisCard("Downloads", $downloads, "fas fa-cloud-download-alt"); 
                        renderAnalysisCard("Listens", "0", "fas fa-headphones-alt");       
                        renderAnalysisCard("Revenue", "0", "fas fa-coins");                 
                        renderAnalysisCard("Titles", $titlesCount, "fas fa-book-open");     
                        renderAnalysisCard("Book Views", $bookView['unique_user_count'], "fas fa-eye");  
                        renderAnalysisCard("Profile Views", $profileView['visit_count'], "fas fa-user");
                        renderAnalysisCard("Services Views", $serviceView['visit_count'], "fas fa-user-tie");
                        renderAnalysisCard("Events Views", $eventView['visit_count'], "fas fa-calendar-alt"); 
                        ?>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card p-4 shadow-sm border-0">
                                <h5 class="mb-3">My Subscription</h5>
                                <?php if (!empty($subscriptionDetails)): ?>
                                    <p><strong>Plan:</strong> <?= htmlspecialchars($subscriptionDetails['admin_subscription']) ?></p>
                                    <p><strong>Billing Cycle:</strong> <?= htmlspecialchars($subscriptionDetails['billing_cycle']) ?></p>
                                    <p><strong>Status:</strong> <?= htmlspecialchars($subscriptionDetails['subscription_status']) ?></p>
                                <?php else: ?>
                                    <p>No subscription data available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <h5 class="display-6 small fw-semibold">
                            <small>Content Performance Graphs</small>
                        </h5>
                        <!-- Time-based charts -->
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
                            <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4">
                                <h5 class="fw-semibold text-muted text-capitalize small mb-1">Monthly and Yearly Book Views</h5>
                                <canvas id="bookViewsChart" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
                            <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4">
                                <h5 class="fw-semibold text-muted text-capitalize small mb-1">Monthly and Yearly Profile Views</h5>
                                <canvas id="profileViewsChart" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
                            <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4">
                                <h5 class="fw-semibold text-muted text-capitalize small mb-1">Monthly and Yearly Service Views</h5>
                                <canvas id="serviceViewsChart" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
                            <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4">
                                <h5 class="fw-semibold text-muted text-capitalize small mb-1">Monthly and Yearly Event Views</h5>
                                <canvas id="eventViewsChart" height="100"></canvas>
                            </div>
                        </div>

                        <!-- Geographic charts -->
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-3">
                            <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4">
                                <h5 class="fw-semibold text-capitalize small mb-1">
                                    <span class="">Book Views</span>
                                    <span class="text-muted">by Country</span>
                                    <small class="text-muted">(Top 10)</small>
                                </h5>
                                <canvas id="bookViewsByCountryChart" height="200"></canvas>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-3">
                            <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4">
                                <h5 class="fw-semibold text-capitalize small mb-1">
                                    <span class="">Book Views</span>
                                    <span class="text-muted">by Region</span>
                                    <small class="text-muted">(Top 10)</small>
                                </h5>
                                <canvas id="bookViewsByProvinceChart" height="200"></canvas>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-4 mb-3">
                            <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4">
                                <h5 class="fw-semibold text-capitalize small mb-1">
                                    <span class="">Book Views</span>
                                    <span class="text-muted">by City</span>
                                    <small class="text-muted">(Top 10)</small>
                                </h5>
                                <canvas id="bookViewsByCityChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/views/includes/scripts.php" ?>

    <script>
        // Time-based charts
        const bookCtx = document.getElementById('bookViewsChart')?.getContext('2d');
        const profileCtx = document.getElementById('profileViewsChart')?.getContext('2d');
        const serviceCtx = document.getElementById('serviceViewsChart')?.getContext('2d');
        const eventCtx = document.getElementById('eventViewsChart')?.getContext('2d');

        // Geographic charts
        const countryCtx = document.getElementById('bookViewsByCountryChart')?.getContext('2d');
        const provinceCtx = document.getElementById('bookViewsByProvinceChart')?.getContext('2d');
        const cityCtx = document.getElementById('bookViewsByCityChart')?.getContext('2d');

        // Helper function for time-based charts
        const generateTimeChart = (ctx, label, rawData) => {
            if (!ctx || !Array.isArray(rawData)) return;

            const labels = rawData.map(d => `${d.month_year}`);
            const data = rawData.map(d => parseInt(d.views) || 0);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label,
                        data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        };

        // Helper function for geographic charts (horizontal bar charts)
        const generateGeoChart = (ctx, label, rawData, limit = 10) => {
            if (!ctx || !Array.isArray(rawData)) return;

            // Sort and limit the data
            const sortedData = [...rawData].sort((a, b) => b.views - a.views).slice(0, limit);
            const labels = sortedData.map(d => d.country || d.province || d.city);
            const data = sortedData.map(d => parseInt(d.views) || 0);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label,
                        data,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        };

        // Generate time-based charts
        generateTimeChart(bookCtx, 'Book Views', <?= json_encode($bookViewsByMonthYear) ?>);
        generateTimeChart(profileCtx, 'Profile Views', <?= json_encode($profileViewsByMonthYear) ?>);
        generateTimeChart(serviceCtx, 'Service Views', <?= json_encode($serviceViewsByMonthYear) ?>);
        generateTimeChart(eventCtx, 'Event Views', <?= json_encode($eventViewsByMonthYear) ?>);

        // Generate geographic charts
        generateGeoChart(countryCtx, 'Views by Country', <?= json_encode($bookViewsByCountry) ?>);
        generateGeoChart(provinceCtx, 'Views by Province', <?= json_encode($bookViewsByProvince) ?>);
        generateGeoChart(cityCtx, 'Views by City', <?= json_encode($bookViewsByCity) ?>);
    </script>
</body>

</html>