<?php
function renderAnalysisPie($title, $value, $total, string $theme = "primary")
{
    $value = is_numeric($value) ? (float)$value : 0;
    $total = is_numeric($total) && $total > 0 ? (float)$total : 1;
    $percentage = round(($value / $total) * 100, 1);
    $chartId = "pieChart_" . uniqid();

    $color = "bg-{$theme}-subtle";
    $textColor = "text-{$theme}";
?>
    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
        <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4 <?= $color ?> border border-<?= $theme ?>-subtle">
            <div class="d-flex flex-column align-items-center justify-content-center h-100">

                <!-- Title -->
                <p class="fw-semibold <?= $textColor ?> text-capitalize small mb-1"><?= htmlspecialchars($title) ?></p>

                <!-- Value -->
                <h4 class="fw-bold mb-1 <?= $textColor ?>">
                    <span><?= $value ?></span>
                </h4>

                <!-- Pie chart canvas -->
                <canvas id="<?= $chartId ?>" style="max-height: 120px;"></canvas>

                <!-- Percentage -->
                <p class="small text-muted mt-1 mb-0">
                    <?= $percentage ?>% of total
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("<?= $chartId ?>").getContext("2d");
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['<?= htmlspecialchars($title) ?>', 'Remaining'],
                    datasets: [{
                        data: [<?= $value ?>, <?= $total - $value ?>],
                        backgroundColor: ['#0d6efd', '#e9ecef'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
<?php
}
?>