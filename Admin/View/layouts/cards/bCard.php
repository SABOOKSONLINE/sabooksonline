<?php
function renderAnalysisBar($title, array $labels, array $values, string $theme = "primary")
{
    // Map Bootstrap-like theme names to hex colors
    $colors = [
        "primary" => ["bg" => "#0d6efd", "border" => "#0a58ca"],
        "success" => ["bg" => "#198754", "border" => "#146c43"],
        "danger"  => ["bg" => "#dc3545", "border" => "#b02a37"],
        "warning" => ["bg" => "#ffc107", "border" => "#d39e00"],
        "info"    => ["bg" => "#0dcaf0", "border" => "#31d2f2"]
    ];

    $bgColor = $colors[$theme]["bg"] ?? "#0d6efd";
    $borderColor = $colors[$theme]["border"] ?? "#0a58ca";

    $chartId = "barChart_" . uniqid();
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
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: '<?= htmlspecialchars($title) ?>',
                        data: <?= json_encode($values) ?>,
                        backgroundColor: '<?= $bgColor ?>',
                        borderColor: '<?= $borderColor ?>',
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