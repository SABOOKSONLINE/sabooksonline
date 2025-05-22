<?php
function renderAnalysisCard($title, $amount, $iconName)
{
?>
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
        <div class="card analysis-card shadow-sm border">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="card-text text-muted mb-1"><?= htmlspecialchars($title) ?></p>
                    <h3 class="card-title fw-semibold"><?= htmlspecialchars($amount) ?></h3>
                </div>
                <div class="p-3 card-icon text-muted">
                    <i class="<?= htmlspecialchars($iconName) ?>"></i>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>