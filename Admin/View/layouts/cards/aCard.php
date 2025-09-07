<?php
function renderAnalysisCard(
    string $title,
    float|int|string $amount,
    string $iconName,
    string $theme = "primary",
    int $target = 100
) {
    $amount = is_numeric($amount) ? (float)$amount : 0;
    $progressWidth = max(0, min($amount, $target));

    $color = "bg-{$theme}-subtle";
    $textColor = "text-{$theme}";
?>
    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
        <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 py-4 pe-5 <?= $color ?> border border-<?= $theme ?>-subtle">
            <div class="d-flex align-items-center justify-content-between h-100">

                <!-- Icon left -->
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 <?= $color ?> <?= $textColor ?>"
                    style="width: 60px; height: 60px;">
                    <i class="<?= htmlspecialchars($iconName) ?> fs-4"></i>
                </div>

                <!-- Text content right -->
                <div class="flex-grow-1">
                    <p class="fw-semibold <?= $textColor ?> text-capitalize small mb-1"><?= htmlspecialchars($title) ?></p>
                    <h4 class="fw-bold mb-1 <?= $textColor ?>">
                        <span><?= $amount ?></span>
                    </h4>

                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-<?= $theme ?>" style="width: <?= $progressWidth ?>%;"></div>
                    </div>

                    <p class="small text-muted mt-1 mb-0 <?= ($amount > 0) ? 'd-none' : '' ?>">
                        No data yet
                    </p>
                </div>

            </div>
        </div>
    </div>
<?php
}
?>