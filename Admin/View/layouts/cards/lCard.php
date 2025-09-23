<?php
function renderAnalysisLine($title, array $labels, array $values, string $theme = "primary")
{
    // Map Bootstrap-like theme names to colors
    $colors = [
        "primary" => ["bg" => "rgba(13,110,253,0.2)", "border" => "#0d6efd"],
        "success" => ["bg" => "rgba(25,135,84,0.2)", "border" => "#198754"],
        "danger"  => ["bg" => "rgba(220,53,69,0.2)", "border" => "#dc3545"],
        "warning" => ["bg" => "rgba(255,193,7,0.2)", "border" => "#ffc107"],
        "info"    => ["bg" => "rgba(13,202,240,0.2)", "border" => "#0dcaf0"]
    ];

    $bgColor = $colors[$theme]["bg"] ?? "rgba(13,110,253,0.2)";
    $borderColor = $colors[$theme]["border"] ?? "#0d6efd";

    $chartId = "lineChart_" . uniqid();
?>
    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
        <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4 border border-<?= $theme ?>-subtle">
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <p class="fw-semibold text-capitalize small mb-1"><?= htmlspecialchars($title) ?></p>
                <canvas id="<?= $chartId ?>" style="max-height: 120px;"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("<?= $chartId ?>").getContext("2d");
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: '<?= htmlspecialchars($title) ?>',
                        data: <?= json_encode($values) ?>,
                        backgroundColor: '<?= $bgColor ?>',
                        borderColor: '<?= $borderColor ?>',
                        fill: true,
                        tension: 0.3
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
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
<?php
}
?>