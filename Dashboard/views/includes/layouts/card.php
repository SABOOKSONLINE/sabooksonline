<?php
function renderAnalysisCard($title, $amount, $iconName)
{
?>
    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
        <div class="card analysis-card rounded-4 shadow-sm h-100 p-3 p-4">
            <div class="d-flex align-items-center justify-content-between h-100">

                <!-- Icon left -->
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="<?= htmlspecialchars($iconName) ?> fs-4"></i>
                </div>

                <!-- Text content right -->
                <div class="flex-grow-1">
                    <p class="fw-semibold text-muted text-capitalize small mb-1"><?= htmlspecialchars($title) ?></p>
                    <h4 class="fw-bold mb-1">
                    <span class="count-up" data-target="<?= (int) $amount ?>">0</span>
                    </h4>


                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: <?= $amount > 100 ? 100 : $amount ?>%;"></div>
                    </div>

                    <p class="small text-muted mt-1 mb-0 <?= ($amount == 0) ? '' : 'd-none' ?>">
                        No data yet
                    </p>
                </div>

            </div>
        </div>
    </div>

<?php
}
?>