<?php
function renderAnalysisCard($title, $amount, $iconName, $theme = 'primary')
{
    // Map theme to gradient
    $gradients = [
        'primary' => 'var(--primary-gradient)',
        'success' => 'var(--success-gradient)',
        'warning' => 'var(--warning-gradient)',
        'info' => 'var(--info-gradient)',
        'danger' => 'var(--danger-gradient)',
        'dark' => 'var(--dark-gradient)'
    ];
    
    $gradient = $gradients[$theme] ?? $gradients['primary'];
    
    // Format amount - check if it's a number or currency
    $isRevenue = (strpos($title, 'Revenue') !== false || strpos($title, 'Income') !== false);
    $displayValue = $amount;
    $countTarget = 0;
    
    if (is_numeric($amount)) {
        $countTarget = (int)$amount;
        if ($isRevenue) {
            $displayValue = 'R' . number_format((float)$amount, 2);
        } else {
            $displayValue = number_format((int)$amount);
        }
    } else {
        $displayValue = htmlspecialchars($amount);
    }
?>
    <div class="col-12 col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-3">
        <div class="stat-card-enhanced" style="--card-gradient: <?= $gradient ?>;">
            <div class="stat-card-icon-wrapper">
                <i class="<?= htmlspecialchars($iconName) ?>"></i>
            </div>
            <div class="stat-card-value">
                <?php if (is_numeric($amount)): ?>
                    <?php if ($isRevenue): ?>
                        R<span class="count-up" data-target="<?= $countTarget ?>">0</span>
                    <?php else: ?>
                        <span class="count-up" data-target="<?= $countTarget ?>">0</span>
                    <?php endif; ?>
                <?php else: ?>
                    <?= $displayValue ?>
                <?php endif; ?>
            </div>
            <div class="stat-card-label"><?= htmlspecialchars($title) ?></div>
        </div>
    </div>

<?php
}
?>