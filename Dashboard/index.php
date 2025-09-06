<?php
include __DIR__ . "/views/includes/header.php";

include __DIR__ . "/views/includes/layouts/card.php";
include __DIR__ . "/views/includes/layouts/purchaseCard.php";
include __DIR__ . "/views/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/views/includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/views/includes/layouts/side-bar.php" ?>

                <div id="pdfContent" class="col offset-lg-3 offset-xl-2 p-5 hv-100 overflow-y-scroll mt-5">
                    <?php renderHeading("Dashboard", "Your Publishing Insights & Performance Data", "", "Print to PDF", true) ?>

                    <form method="GET" class="mb-4 d-flex gap-3 align-items-end">
                        <div>
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="<?= htmlspecialchars($_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'))) ?>">
                        </div>

                        <div>
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="<?= htmlspecialchars($_GET['end_date'] ?? date('Y-m-d')) ?>">
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="?" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>

                    <div class="row">
                        <?php
                        require_once __DIR__ . "/database/connection.php";
                        require_once __DIR__ . "/controllers/AnalysisController.php";
                        require_once __DIR__ . "/models/UserModel.php";


                        $analysisController = new AnalysisController($conn);
                        $userKey = $_SESSION["ADMIN_USERKEY"];
                        $email = $_SESSION["ADMIN_EMAIL"];

                        $start = $_GET['start_date'] ?? null;
                        $end = $_GET['end_date'] ?? null;

                        $start_date = $start ? $start . ' 00:00:00' : null;
                        $end_date = $end ? $end . ' 23:59:59' : null;

                        $revenue = $analysisController->getUserRevenue($userKey);

                        $booksMap = [];
                        foreach ($revenue['books'] as $book) {
                            $booksMap[$book['id']] = $book['title'];
                        }

                        // Merge book title into purchases
                        $purchasesWithTitle = array_map(function($purchase) use ($booksMap) {
                            return array_merge($purchase, [
                                'title' => $booksMap[$purchase['book_id']] ?? 'Unknown Book'
                            ]);
                        }, $revenue['purchases']);

                        $titlesCount = $analysisController->getTitlesCount($userKey);
                        $subscriptionDetails = $analysisController->viewSubscription($userKey);
                        $bookView = $analysisController->getBookViews($userKey, $start_date, $end_date);
                        $profileView = $analysisController->getProfileViews($userKey, $start_date, $end_date);
                        // $serviceView = $analysisController->getServiceViews($_SESSION['ADMIN_ID'], $start_date, $end_date);
                        $eventView = $analysisController->getEventViews($userKey, $start_date, $end_date);
                        $downloads = $analysisController->getDownloadsByEmail($email);
                        $topBooks = $analysisController->getTopBooks($userKey);

                        // revenue analyticssssss

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
                        renderAnalysisCard("Audiobook Plays", "0", "fas fa-headphones-alt");
                        renderAnalysisCard("Total Revenue", $revenue['total_revenue'], "fas fa-credit-card");
                        renderAnalysisCard("Published Titles", $titlesCount, "fas fa-book-open");
                        renderAnalysisCard("Book Views", $bookView['unique_user_count'], "fas fa-eye");
                        renderAnalysisCard("Media Views", '0', "fas fa-newspaper");
                        renderAnalysisCard("Profile Views", $profileView['visit_count'], "fas fa-user");
                        renderAnalysisCard("Events Views", $eventView['visit_count'], "fas fa-calendar-alt");
                        renderPurchaseCard($purchasesWithTitle);

                        ?>
                    </div>

                    <?php if (!empty($topBooks)): ?>
                        <div class="row">
                            <h5 class="display-6 small fw-semibold">
                                <small>Top Performing Books</small>
                            </h5>
                            <?php foreach ($topBooks as $index => $book): ?>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
                                    <div class="book-card position-relative text-center">
                                        <span class="book-card-num"><?= $index + 1 ?></span>

                                        <div class="card shadow-sm rounded-4 overflow-hidden" style="width: 100%; max-width: 260px;">
                                            <a href="/library/book/<?= htmlspecialchars($book['CONTENTID']) ?>">
                                            <img src="/cms-data/book-covers/<?= htmlspecialchars($book['COVER']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['TITLE']) ?>">
                                        </a>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No views yet. Share your books to get more readers!</p>
                    <?php endif; ?>

                    <hr class="my-3">

                    <div class="row">
                        <h5 class="display-6 small fw-semibold">
                            <small>Content Performance Graphs</small>
                        </h5>
                        <!-- Time-based charts -->
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
                            <div class="card analysis-card rounded-4 shadow-sm h-200 p-3 p-4">
                                <h5 class="fw-semibold text-muted text-capitalize small mb-1">Overall Views</h5>
                                <canvas id="combinedDonutChart" height="100"></canvas>
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
                                <div id="totalCountryViews" class="text-muted small mb-2"></div>
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

                                <div id="totalProvinceViews" class="text-muted small mb-2"></div>
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
                                <div id="totalCityViews" class="text-muted small mb-2"></div>
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


        // Geographic charts
        const countryCtx = document.getElementById('bookViewsByCountryChart')?.getContext('2d');
        const provinceCtx = document.getElementById('bookViewsByProvinceChart')?.getContext('2d');
        const cityCtx = document.getElementById('bookViewsByCityChart')?.getContext('2d');

        // Helper function for time-based charts

        const combinedCtx = document.getElementById('combinedDonutChart').getContext('2d');

        const bookViews = <?= json_encode($bookViewsByMonthYear) ?>;
        const profileViews = <?= json_encode($profileViewsByMonthYear) ?>;
        // const serviceViews = <?= json_encode($serviceViewsByMonthYear) ?>;
        const eventViews = <?= json_encode($eventViewsByMonthYear) ?>;

        const sumViews = (data) =>
            Array.isArray(data) ? data.reduce((total, item) => total + parseInt(item.views || 0), 0) : 0;

        const labels = ['Book Views', 'Profile Views', 'Service Views', 'Event Views'];
        const data = [
            sumViews(bookViews),
            sumViews(profileViews),
            // sumViews(serviceViews),
            sumViews(eventViews)
        ];

        new Chart(combinedCtx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    label: 'Total Views Breakdown',
                    data,
                    backgroundColor: ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.parsed;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${value} views (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });


        const calculateTotalViews = (rawData) => {
            if (!Array.isArray(rawData)) return 0;
            return rawData.reduce((sum, item) => sum + (parseInt(item.views) || 0), 0);
        };

        // Helper function for geographic charts (horizontal bar charts)
        const generateGeoChart = (ctx, label, rawData, limit = 10, totalElementId = null) => {
            if (!ctx || !Array.isArray(rawData)) return;

            const sortedData = [...rawData].sort((a, b) => b.views - a.views).slice(0, limit);
            const labels = sortedData.map(d => d.country || d.province || d.city);
            const data = sortedData.map(d => parseInt(d.views) || 0);

            // ✅ Show total views if an element ID is provided
            if (totalElementId) {
                const total = calculateTotalViews(sortedData);
                const totalEl = document.getElementById(totalElementId);
                if (totalEl) totalEl.innerText = `Total: ${total.toLocaleString()}`;
            }

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



        // Generate geographic charts

        generateGeoChart(countryCtx, 'Views by Country', <?= json_encode($bookViewsByCountry) ?>, 10, 'totalCountryViews');
        generateGeoChart(provinceCtx, 'Views by Province', <?= json_encode($bookViewsByProvince) ?>, 10, 'totalProvinceViews');
        generateGeoChart(cityCtx, 'Views by City', <?= json_encode($bookViewsByCity) ?>, 10, 'totalCityViews');
    </script>
    <script>
        document.getElementById('printPDF')?.addEventListener('click', async () => {
            const {
                jsPDF
            } = window.jspdf;

            const content = document.getElementById("pdfContent");

            html2canvas(content, {
                scale: 2
            }).then((canvas) => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');

                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                const imgProps = pdf.getImageProperties(imgData);

                const pdfWidth = pageWidth;
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                let position = 0;

                if (pdfHeight > pageHeight) {
                    // Split into multiple pages
                    let heightLeft = pdfHeight;
                    while (heightLeft > 0) {
                        pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
                        heightLeft -= pageHeight;
                        position -= pageHeight;
                        if (heightLeft > 0) pdf.addPage();
                    }
                } else {
                    // Single page
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                }

                pdf.save("dashboard_report.pdf");
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll(".count-up");

            counters.forEach(counter => {
                const target = +counter.getAttribute("data-target");
                const duration = 4000; // Total animation time in ms
                const frameRate = 60; // Frames per second
                const totalFrames = Math.round(duration / (1000 / frameRate));
                let currentFrame = 0;

                const countUp = () => {
                    currentFrame++;
                    const progress = currentFrame / totalFrames;
                    const currentValue = Math.round(target * progress);

                    counter.innerText = currentValue;

                    if (currentFrame < totalFrames) {
                        requestAnimationFrame(countUp);
                    } else {
                        counter.innerText = target;
                    }
                };

                countUp();
            });
        });
    </script>


    <!-- jsPDF & html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

</body>

</html>