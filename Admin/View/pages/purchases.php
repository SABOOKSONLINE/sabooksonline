<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/cards/aCard.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Book Purchases";
ob_start();

renderHeading(
    "Book Purchases Management",
    "Comprehensive analytics and management of all book purchases with advanced filtering and insights."
);

renderAlerts();

// Calculate detailed statistics
$totalPurchases = count($data["book_purchases"]["all"]);
$completedPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => $p['payment_status'] === 'COMPLETE'));
$pendingPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => $p['payment_status'] === 'PENDING'));
$failedPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => $p['payment_status'] === 'FAILED'));
$ebookPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => strtolower($p['format']) === 'ebook'));
$audiobookPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => strtolower($p['format']) === 'audiobook'));
$otherFormatPurchases = $totalPurchases - $ebookPurchases - $audiobookPurchases;
$freePurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => floatval($p['amount']) == 0));
$paidPurchases = $totalPurchases - $freePurchases;
$totalRevenue = (float)($data["book_purchases"]["revenue"] ?? 0);
$avgOrderValue = $paidPurchases > 0 ? $totalRevenue / $paidPurchases : 0;

// Calculate revenue by format
$ebookRevenue = array_sum(array_map(fn($p) => strtolower($p['format']) === 'ebook' ? (float)$p['amount'] : 0, $data["book_purchases"]["all"]));
$audiobookRevenue = array_sum(array_map(fn($p) => strtolower($p['format']) === 'audiobook' ? (float)$p['amount'] : 0, $data["book_purchases"]["all"]));

// Calculate recent trends (last 7 days)
$sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
$recentPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => strtotime($p['payment_date']) >= strtotime($sevenDaysAgo)));
$recentRevenue = array_sum(array_map(fn($p) => strtotime($p['payment_date']) >= strtotime($sevenDaysAgo) ? (float)$p['amount'] : 0, $data["book_purchases"]["all"]));
?>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --dark-gradient: linear-gradient(135deg, #434343 0%, #000000 100%);
}

.purchases-page {
    --primary-color: #667eea;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
    --dark-color: #1f2937;
    --light-bg: #f9fafb;
    --border-color: #e5e7eb;
}

.stat-card-enhanced {
    background: white;
    border-radius: 16px;
    padding: 1.75rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
}

.stat-card-enhanced::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--card-gradient, var(--primary-gradient));
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.stat-card-enhanced:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    border-color: transparent;
}

.stat-card-enhanced:hover::before {
    transform: scaleX(1);
}

.stat-card-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin-bottom: 1rem;
    background: var(--card-gradient, var(--primary-gradient));
    color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-card-value {
    font-size: 2.25rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.stat-card-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-card-change {
    font-size: 0.75rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.stat-card-change.positive {
    color: var(--success-color);
}

.stat-card-change.negative {
    color: var(--danger-color);
}

.analytics-section {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}

.analytics-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--dark-color);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.progress-enhanced {
    height: 8px;
    border-radius: 10px;
    background: #f3f4f6;
    overflow: hidden;
    margin-top: 0.5rem;
}

.progress-bar-enhanced {
    height: 100%;
    border-radius: 10px;
    transition: width 0.6s ease;
    background: var(--progress-gradient);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.search-filters-container {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.search-wrapper {
    position: relative;
    margin-bottom: 1rem;
}

.search-wrapper .form-control {
    padding-left: 3rem;
    padding-right: 3rem;
    border-radius: 12px;
    border: 2px solid var(--border-color);
    font-size: 0.95rem;
    transition: all 0.3s ease;
    height: 48px;
}

.search-wrapper .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    z-index: 10;
}

.clear-search-btn {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.25rem;
    display: none;
    z-index: 10;
}

.clear-search-btn:hover {
    color: var(--danger-color);
}

.clear-search-btn.show {
    display: block;
}

.filters-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    display: block;
}

.filter-group .form-select,
.filter-group .form-control {
    border-radius: 10px;
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
}

.filter-group .form-select:focus,
.filter-group .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
}

.purchases-table-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.table-header-enhanced {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 1.5rem;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-title-enhanced {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark-color);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.table-actions-enhanced {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-action-enhanced {
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: 2px solid var(--border-color);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-action-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.table-responsive-enhanced {
    max-height: 700px;
    overflow-y: auto;
}

.table-enhanced {
    margin-bottom: 0;
}

.table-enhanced thead th {
    background: #f8fafc;
    color: #374151;
    font-weight: 600;
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1.25rem 1rem;
    border-bottom: 2px solid var(--border-color);
    position: sticky;
    top: 0;
    z-index: 10;
    white-space: nowrap;
}

.table-enhanced tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f3f4f6;
}

.table-enhanced tbody tr:hover {
    background-color: #f9fafb;
    transform: scale(1.001);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.table-enhanced tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    font-size: 0.9rem;
    color: #374151;
}

.book-cover-mini {
    width: 100px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    cursor: pointer;
    display: block;
}

.book-cover-mini:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    z-index: 10;
    position: relative;
}

.book-info-compact {
    max-width: 300px;
}

.book-title-compact {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.book-meta-compact {
    font-size: 0.8125rem;
    color: #6b7280;
    line-height: 1.5;
}

.badge-enhanced {
    padding: 0.5rem 0.875rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.8125rem;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.price-display {
    font-weight: 700;
    font-size: 1rem;
}

.price-display.paid {
    color: var(--success-color);
}

.price-display.free {
    color: #9ca3af;
}

.table-footer-enhanced {
    padding: 1.5rem;
    background: #f9fafb;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-enhanced {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.page-info-enhanced {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

.empty-state-enhanced {
    text-align: center;
    padding: 4rem 2rem;
    color: #9ca3af;
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.modal-enhanced .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-enhanced .modal-header {
    border-bottom: 2px solid var(--border-color);
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px 16px 0 0;
}

.modal-enhanced .modal-body {
    padding: 1.5rem;
}

.modal-enhanced .modal-footer {
    border-top: 2px solid var(--border-color);
    padding: 1.5rem;
}

/* Cover Modal for Book Images */
.cover-modal-enhanced {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(5px);
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.cover-modal-enhanced.show {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.cover-modal-content-enhanced {
    max-width: 90%;
    max-height: 90%;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    animation: zoomIn 0.3s ease;
    border: 4px solid white;
}

@keyframes zoomIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.cover-modal-close-enhanced {
    position: absolute;
    top: 30px;
    right: 40px;
    color: white;
    font-size: 2.5rem;
    font-weight: bold;
    cursor: pointer;
    z-index: 1051;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.cover-modal-close-enhanced:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg) scale(1.1);
}

@media (max-width: 768px) {
    .table-responsive-enhanced {
        max-height: 500px;
    }
    
    .stat-card-value {
        font-size: 1.75rem;
    }
    
    .filters-row {
        flex-direction: column;
    }
    
    .filter-group {
        width: 100%;
    }
}
</style>

<?php
renderSectionHeader(
    "Key Metrics",
    "Real-time insights into your book purchase performance"
);
?>

<div class="purchases-page">
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card-enhanced" style="--card-gradient: var(--primary-gradient);">
                <div class="stat-card-icon-wrapper">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-card-value"><?= number_format($totalPurchases) ?></div>
                <div class="stat-card-label">Total Purchases</div>
                <?php if ($recentPurchases > 0): ?>
                    <div class="stat-card-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <?= $recentPurchases ?> in last 7 days
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card-enhanced" style="--card-gradient: var(--success-gradient);">
                <div class="stat-card-icon-wrapper">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-card-value">R<?= number_format($totalRevenue, 2) ?></div>
                <div class="stat-card-label">Total Revenue</div>
                <?php if ($recentRevenue > 0): ?>
                    <div class="stat-card-change positive">
                        <i class="fas fa-arrow-up"></i>
                        R<?= number_format($recentRevenue, 2) ?> recent
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card-enhanced" style="--card-gradient: var(--info-gradient);">
                <div class="stat-card-icon-wrapper">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-card-value"><?= number_format($completedPurchases) ?></div>
                <div class="stat-card-label">Completed Sales</div>
                <div class="stat-card-change positive">
                    <i class="fas fa-percentage"></i>
                    <?= $totalPurchases > 0 ? number_format(($completedPurchases / $totalPurchases * 100), 1) : 0 ?>% success rate
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card-enhanced" style="--card-gradient: var(--warning-gradient);">
                <div class="stat-card-icon-wrapper">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-value">R<?= number_format($avgOrderValue, 2) ?></div>
                <div class="stat-card-label">Average Order Value</div>
                <div class="stat-card-change">
                    <i class="fas fa-chart-line"></i>
                    Based on <?= $paidPurchases ?> paid orders
                </div>
            </div>
        </div>
    </div>

    <?php
    renderSectionHeader(
        "Performance Analytics",
        "Detailed breakdown of purchases by format, payment status, and revenue"
    );
    ?>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="analytics-section">
                <div class="analytics-header">
                    <h6 class="analytics-title">
                        <i class="fas fa-chart-pie" style="color: var(--info-color);"></i>
                        Format Distribution
                    </h6>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-book" style="color: var(--info-color);"></i>
                            <strong>Ebooks</strong>
                        </span>
                        <div>
                            <strong><?= number_format($ebookPurchases) ?></strong>
                            <small class="text-muted ms-2">(<?= $totalPurchases > 0 ? number_format(($ebookPurchases / $totalPurchases * 100), 1) : 0 ?>%)</small>
                        </div>
                    </div>
                    <div class="progress-enhanced">
                        <div class="progress-bar-enhanced" style="width: <?= $totalPurchases > 0 ? ($ebookPurchases / $totalPurchases * 100) : 0 ?>%; --progress-gradient: var(--info-gradient);"></div>
                    </div>
                    <small class="text-muted">Revenue: R<?= number_format($ebookRevenue, 2) ?></small>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-headphones" style="color: var(--warning-color);"></i>
                            <strong>Audiobooks</strong>
                        </span>
                        <div>
                            <strong><?= number_format($audiobookPurchases) ?></strong>
                            <small class="text-muted ms-2">(<?= $totalPurchases > 0 ? number_format(($audiobookPurchases / $totalPurchases * 100), 1) : 0 ?>%)</small>
                        </div>
                    </div>
                    <div class="progress-enhanced">
                        <div class="progress-bar-enhanced" style="width: <?= $totalPurchases > 0 ? ($audiobookPurchases / $totalPurchases * 100) : 0 ?>%; --progress-gradient: var(--warning-gradient);"></div>
                    </div>
                    <small class="text-muted">Revenue: R<?= number_format($audiobookRevenue, 2) ?></small>
                </div>
                
                <?php if ($otherFormatPurchases > 0): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-file-alt" style="color: #9ca3af;"></i>
                            <strong>Other</strong>
                        </span>
                        <div>
                            <strong><?= number_format($otherFormatPurchases) ?></strong>
                            <small class="text-muted ms-2">(<?= $totalPurchases > 0 ? number_format(($otherFormatPurchases / $totalPurchases * 100), 1) : 0 ?>%)</small>
                        </div>
                    </div>
                    <div class="progress-enhanced">
                        <div class="progress-bar-enhanced" style="width: <?= $totalPurchases > 0 ? ($otherFormatPurchases / $totalPurchases * 100) : 0 ?>%; background: #9ca3af;"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="analytics-section">
                <div class="analytics-header">
                    <h6 class="analytics-title">
                        <i class="fas fa-money-check-alt" style="color: var(--success-color);"></i>
                        Payment Status
                    </h6>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                            <strong>Completed</strong>
                        </span>
                        <strong><?= number_format($completedPurchases) ?></strong>
                    </div>
                    <div class="progress-enhanced">
                        <div class="progress-bar-enhanced" style="width: <?= $totalPurchases > 0 ? ($completedPurchases / $totalPurchases * 100) : 0 ?>%; --progress-gradient: var(--success-gradient);"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock" style="color: var(--warning-color);"></i>
                            <strong>Pending</strong>
                        </span>
                        <strong><?= number_format($pendingPurchases) ?></strong>
                    </div>
                    <div class="progress-enhanced">
                        <div class="progress-bar-enhanced" style="width: <?= $totalPurchases > 0 ? ($pendingPurchases / $totalPurchases * 100) : 0 ?>%; --progress-gradient: var(--warning-gradient);"></div>
                    </div>
                </div>
                
                <?php if ($failedPurchases > 0): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                            <strong>Failed</strong>
                        </span>
                        <strong><?= number_format($failedPurchases) ?></strong>
                    </div>
                    <div class="progress-enhanced">
                        <div class="progress-bar-enhanced" style="width: <?= $totalPurchases > 0 ? ($failedPurchases / $totalPurchases * 100) : 0 ?>%; --progress-gradient: var(--danger-gradient);"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="analytics-section">
                <div class="analytics-header">
                    <h6 class="analytics-title">
                        <i class="fas fa-chart-bar" style="color: var(--primary-color);"></i>
                        Revenue Breakdown
                    </h6>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-dollar-sign" style="color: var(--success-color);"></i>
                            <strong>Paid Sales</strong>
                        </span>
                        <strong><?= number_format($paidPurchases) ?></strong>
                    </div>
                    <div class="progress-enhanced">
                        <div class="progress-bar-enhanced" style="width: <?= $totalPurchases > 0 ? ($paidPurchases / $totalPurchases * 100) : 0 ?>%; --progress-gradient: var(--success-gradient);"></div>
                    </div>
                    <small class="text-muted">Total: R<?= number_format($totalRevenue, 2) ?></small>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-gift" style="color: var(--info-color);"></i>
                            <strong>Free Downloads</strong>
                        </span>
                        <strong><?= number_format($freePurchases) ?></strong>
                    </div>
                    <div class="progress-enhanced">
                        <div class="progress-bar-enhanced" style="width: <?= $totalPurchases > 0 ? ($freePurchases / $totalPurchases * 100) : 0 ?>%; --progress-gradient: var(--info-gradient);"></div>
                    </div>
                    <small class="text-muted">No revenue generated</small>
                </div>
            </div>
        </div>
    </div>

    <?php
    renderSectionHeader(
        "Purchase Records",
        "Complete list of all book purchases with advanced search and filtering"
    );
    ?>

    <?php if (empty($data["book_purchases"]["all"])): ?>
        <div class="purchases-table-card">
            <div class="empty-state-enhanced">
                <div class="empty-state-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h4 class="mb-2">No Purchases Found</h4>
                <p class="text-muted">When customers purchase books, they will appear here.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="search-filters-container">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="purchaseSearch" 
                    class="form-control" 
                    placeholder="Search by book title, customer email, ISBN, payment ID, or any field..."
                    autocomplete="off">
                <button class="clear-search-btn" id="clearPurchaseSearch" title="Clear search">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="filters-row">
                <div class="filter-group">
                    <label><i class="fas fa-filter me-1"></i>Format</label>
                    <select class="form-select" id="formatFilter">
                        <option value="">All Formats</option>
                        <option value="Ebook">Ebooks Only</option>
                        <option value="Audiobook">Audiobooks Only</option>
                        <option value="Other">Other Formats</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-info-circle me-1"></i>Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="COMPLETE">Completed</option>
                        <option value="PENDING">Pending</option>
                        <option value="FAILED">Failed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-money-bill-wave me-1"></i>Payment Type</label>
                    <select class="form-select" id="paymentFilter">
                        <option value="">All Payments</option>
                        <option value="paid">Paid Only</option>
                        <option value="free">Free Only</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar me-1"></i>Date Range</label>
                    <input type="date" class="form-control" id="dateFrom" placeholder="From">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <input type="date" class="form-control" id="dateTo" placeholder="To">
                </div>
                <div class="filter-actions">
                    <button class="btn btn-outline-secondary btn-action-enhanced" id="clearFilters">
                        <i class="fas fa-times"></i> Clear
                    </button>
                    <button class="btn btn-primary btn-action-enhanced" id="exportBtn">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <div class="purchases-table-card">
            <div class="table-header-enhanced">
                <h5 class="table-title-enhanced">
                    <i class="fas fa-list"></i>
                    All Purchases
                </h5>
                <div class="table-actions-enhanced">
                    <span class="page-info-enhanced" id="filterResults">
                        Showing <?= $totalPurchases ?> of <?= $totalPurchases ?> purchases
                    </span>
                </div>
            </div>
            
            <div class="table-responsive-enhanced">
                <table class="table table-enhanced table-hover" id="purchasesTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ID</th>
                            <th><i class="fas fa-book me-1"></i>Book</th>
                            <th><i class="fas fa-user me-1"></i>Customer</th>
                            <th><i class="fas fa-file-alt me-1"></i>Format</th>
                            <th><i class="fas fa-tag me-1"></i>Amount</th>
                            <th><i class="fas fa-info-circle me-1"></i>Status</th>
                            <th><i class="fas fa-calendar me-1"></i>Date</th>
                            <th><i class="fas fa-credit-card me-1"></i>Payment ID</th>
                            <th><i class="fas fa-cog me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="purchasesTableBody">
                        <?php foreach ($data["book_purchases"]["all"] as $purchase): ?>
                            <tr class="purchase-row"
                                data-format="<?= htmlspecialchars(strtolower($purchase['format'])) ?>"
                                data-status="<?= htmlspecialchars($purchase['payment_status']) ?>"
                                data-payment-type="<?= floatval($purchase['amount']) > 0 ? 'paid' : 'free' ?>"
                                data-date="<?= date('Y-m-d', strtotime($purchase['payment_date'])) ?>">
                                <td>
                                    <strong class="text-primary">#<?= htmlspecialchars($purchase['id']) ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-4">
                                        <?php if (!empty($purchase['book_cover'])): ?>
                                            <img src="/cms-data/book-covers/<?= htmlspecialchars($purchase['book_cover']) ?>" 
                                                 alt="<?= htmlspecialchars($purchase['book_title'] ?? 'Book Cover') ?>" 
                                                 class="book-cover-mini"
                                                 onclick="showCoverModal('/cms-data/book-covers/<?= htmlspecialchars($purchase['book_cover']) ?>', '<?= htmlspecialchars($purchase['book_title'] ?? 'Book Cover') ?>')"
                                                 onerror="this.style.display='none';">
                                        <?php endif; ?>
                                        <div class="book-info-compact flex-grow-1">
                                            <div class="book-title-compact mb-2">
                                                <?= htmlspecialchars($purchase['book_title'] ?? 'Unknown Book') ?>
                                            </div>
                                            <div class="book-meta-compact">
                                                <div class="mb-1">
                                                    <i class="fas fa-building text-muted me-1"></i>
                                                    Publisher: <strong><?= htmlspecialchars($purchase['book_publisher'] ?? 'Unknown') ?></strong>
                                                </div>
                                                <div>
                                                    <i class="fas fa-hashtag text-muted me-1"></i>
                                                    ID: <span class="font-monospace"><?= htmlspecialchars($purchase['book_id']) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= htmlspecialchars($purchase['user_email']) ?></strong>
                                    </div>
                                    <div class="book-meta-compact">
                                        Key: <span class="font-monospace"><?= htmlspecialchars($purchase['user_key']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $formatClass = $purchase['format'] === 'Ebook' ? 'primary' : ($purchase['format'] === 'Audiobook' ? 'info' : 'secondary');
                                    $formatIcon = $purchase['format'] === 'Ebook' ? 'book' : ($purchase['format'] === 'Audiobook' ? 'headphones' : 'file');
                                    ?>
                                    <span class="badge-enhanced bg-<?= $formatClass ?> text-white">
                                        <i class="fas fa-<?= $formatIcon ?>"></i>
                                        <?= htmlspecialchars(ucfirst($purchase['format'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="price-display <?= floatval($purchase['amount']) > 0 ? 'paid' : 'free' ?>">
                                        <?= floatval($purchase['amount']) > 0 ? 'R' . number_format($purchase['amount'], 2) : 'FREE' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    $statusClass = $purchase['payment_status'] === 'COMPLETE' ? 'success' : ($purchase['payment_status'] === 'PENDING' ? 'warning' : 'danger');
                                    $statusIcon = $purchase['payment_status'] === 'COMPLETE' ? 'check' : ($purchase['payment_status'] === 'PENDING' ? 'clock' : 'times');
                                    ?>
                                    <span class="badge-enhanced bg-<?= $statusClass ?> text-white">
                                        <i class="fas fa-<?= $statusIcon ?>"></i>
                                        <?= htmlspecialchars($purchase['payment_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <?= date('M j, Y', strtotime($purchase['payment_date'])) ?>
                                    </div>
                                    <div class="book-meta-compact">
                                        <?= date('g:i A', strtotime($purchase['payment_date'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-monospace" style="font-size: 0.8125rem;">
                                        <?= htmlspecialchars($purchase['payment_id']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary btn-action-enhanced" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#purchaseDetailModal" 
                                            onclick="showPurchaseDetail(<?= htmlspecialchars(json_encode($purchase)) ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer-enhanced">
                <div class="page-info-enhanced">
                    <span id="purchaseCount">
                        <i class="fas fa-info-circle me-1"></i>
                        Total: <strong><?= $totalPurchases ?></strong> purchases
                    </span>
                </div>
                <div class="pagination-enhanced">
                    <button class="btn btn-sm btn-outline-secondary btn-action-enhanced" id="prevPage" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="page-info-enhanced" id="pageInfo">Page 1</span>
                    <button class="btn btn-sm btn-outline-secondary btn-action-enhanced" id="nextPage">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Book Cover Modal -->
<div id="coverModalEnhanced" class="cover-modal-enhanced" onclick="closeCoverModalEnhanced(event)">
    <span class="cover-modal-close-enhanced" onclick="closeCoverModalEnhanced(event)">&times;</span>
    <img id="modalCoverImageEnhanced" class="cover-modal-content-enhanced" src="" alt="Book Cover">
</div>

<!-- Enhanced Purchase Detail Modal -->
<div class="modal fade modal-enhanced" id="purchaseDetailModal" tabindex="-1" aria-labelledby="purchaseDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseDetailModalLabel">
                    <i class="fas fa-shopping-cart me-2"></i>Purchase Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <h6 class="fw-bold mb-3"><i class="fas fa-book me-2"></i>Book Information</h6>
                        <div class="text-center mb-3">
                            <img id="modalBookCover" src="" alt="Book Cover" 
                                 class="img-fluid rounded shadow" style="max-height: 300px; width: auto;">
                        </div>
                        <table class="table table-sm">
                            <tr>
                                <td class="fw-bold">Title:</td>
                                <td id="modalBookTitle">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Publisher:</td>
                                <td id="modalBookPublisher">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Book ID:</td>
                                <td><span id="modalBookId" class="font-monospace">-</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Content ID:</td>
                                <td><span id="modalContentId" class="font-monospace">-</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Ebook Price:</td>
                                <td><span id="modalEbookPrice" class="text-success">-</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Audiobook Price:</td>
                                <td><span id="modalAudiobookPrice" class="text-info">-</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-7">
                        <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Purchase Information</h6>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2"><i class="fas fa-user me-2"></i>Customer</h6>
                                        <p class="card-text mb-1"><strong id="modalCustomerEmail">-</strong></p>
                                        <small class="text-muted">User Key: <span id="modalUserKey" class="font-monospace">-</span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2"><i class="fas fa-money-bill-wave me-2"></i>Payment</h6>
                                        <p class="card-text mb-1">
                                            <span class="fs-5 fw-bold text-success" id="modalAmount">-</span>
                                        </p>
                                        <small class="text-muted">Status: <span id="modalPaymentStatus" class="badge">-</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-sm">
                            <tr>
                                <td class="fw-bold"><i class="fas fa-file-alt me-2"></i>Format:</td>
                                <td><span id="modalFormat" class="badge">-</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-calendar me-2"></i>Purchase Date:</td>
                                <td id="modalPurchaseDate">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-credit-card me-2"></i>Payment ID:</td>
                                <td><span id="modalPaymentId" class="font-monospace">-</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-hashtag me-2"></i>Purchase ID:</td>
                                <td><span id="modalPurchaseId" class="font-monospace">-</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('purchaseSearch');
    const clearSearchBtn = document.getElementById('clearPurchaseSearch');
    const formatFilter = document.getElementById('formatFilter');
    const statusFilter = document.getElementById('statusFilter');
    const paymentFilter = document.getElementById('paymentFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const exportBtn = document.getElementById('exportBtn');
    const tableBody = document.getElementById('purchasesTableBody');
    const purchaseRows = tableBody ? Array.from(tableBody.querySelectorAll('.purchase-row')) : [];
    const filterResults = document.getElementById('filterResults');
    const purchaseCount = document.getElementById('purchaseCount');
    const totalPurchases = <?= $totalPurchases ?>;
    
    let currentPage = 1;
    const rowsPerPage = 25;
    
    function updateClearButton() {
        if (searchInput && clearSearchBtn) {
            if (searchInput.value.trim().length > 0) {
                clearSearchBtn.classList.add('show');
            } else {
                clearSearchBtn.classList.remove('show');
            }
        }
    }
    
    function filterPurchases() {
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const formatValue = formatFilter ? formatFilter.value.toLowerCase() : '';
        const statusValue = statusFilter ? statusFilter.value : '';
        const paymentValue = paymentFilter ? paymentFilter.value : '';
        const dateFromValue = dateFrom ? dateFrom.value : '';
        const dateToValue = dateTo ? dateTo.value : '';
        
        let visibleCount = 0;
        let visibleRows = [];
        
        purchaseRows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            const rowText = cells.map(cell => cell.textContent || cell.innerText).join(' ').toLowerCase();
            
            // Search filter
            const matchesSearch = !searchValue || rowText.includes(searchValue);
            
            // Format filter
            const matchesFormat = !formatValue || 
                (formatValue === 'other' ? 
                    row.dataset.format !== 'ebook' && row.dataset.format !== 'audiobook' :
                    row.dataset.format === formatValue);
            
            // Status filter
            const matchesStatus = !statusValue || row.dataset.status === statusValue;
            
            // Payment filter
            const matchesPayment = !paymentValue || row.dataset.paymentType === paymentValue;
            
            // Date filter
            let matchesDate = true;
            if (dateFromValue || dateToValue) {
                const rowDate = row.dataset.date;
                if (dateFromValue && rowDate < dateFromValue) matchesDate = false;
                if (dateToValue && rowDate > dateToValue) matchesDate = false;
            }
            
            if (matchesSearch && matchesFormat && matchesStatus && matchesPayment && matchesDate) {
                visibleRows.push(row);
                visibleCount++;
            }
        });
        
        // Update pagination
        updatePagination(visibleRows);
        updateResults(visibleCount);
    }
    
    function updateResults(visibleCount) {
        if (filterResults) {
            filterResults.textContent = `Showing ${visibleCount} of ${totalPurchases} purchases`;
        }
        if (purchaseCount) {
            purchaseCount.innerHTML = `
                <i class="fas fa-info-circle me-1"></i>
                Total: <strong>${visibleCount}</strong> purchases
            `;
        }
    }
    
    function updatePagination(visibleRows) {
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);
        
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        
        purchaseRows.forEach((row, index) => {
            const rowIndex = visibleRows.indexOf(row);
            if (rowIndex >= startIndex && rowIndex < endIndex && rowIndex !== -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const pageInfo = document.getElementById('pageInfo');
        
        if (prevBtn) prevBtn.disabled = currentPage === 1;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages || totalPages === 0;
        if (pageInfo) {
            pageInfo.textContent = totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : 'No results';
        }
    }
    
    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            updateClearButton();
            currentPage = 1;
            filterPurchases();
        });
    }
    
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                updateClearButton();
                currentPage = 1;
                filterPurchases();
                searchInput.focus();
            }
        });
    }
    
    [formatFilter, statusFilter, paymentFilter, dateFrom, dateTo].forEach(filter => {
        if (filter) {
            filter.addEventListener('change', function() {
                currentPage = 1;
                filterPurchases();
            });
        }
    });
    
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (formatFilter) formatFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            if (paymentFilter) paymentFilter.value = '';
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';
            updateClearButton();
            currentPage = 1;
            filterPurchases();
        });
    }
    
    // Pagination
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                filterPurchases();
            }
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            const visibleRows = purchaseRows.filter(row => row.style.display !== 'none');
            const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                filterPurchases();
            }
        });
    }
    
    // Export functionality
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const visibleRows = purchaseRows.filter(row => row.style.display !== 'none');
            const headers = ['ID', 'Book Title', 'Publisher', 'Book ID', 'Customer Email', 'User Key', 'Format', 'Amount', 'Status', 'Purchase Date', 'Payment ID'];
            
            let csv = [headers.join(',')];
            
            visibleRows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                const rowData = [
                    cells[0].textContent.trim().replace(/"/g, '""'),
                    cells[1].querySelector('.book-title-compact')?.textContent.trim().replace(/"/g, '""') || '',
                    cells[1].textContent.match(/Publisher: ([^\n]+)/)?.[1]?.trim().replace(/"/g, '""') || '',
                    cells[1].textContent.match(/ID: ([^\n]+)/)?.[1]?.trim().replace(/"/g, '""') || '',
                    cells[2].querySelector('strong')?.textContent.trim().replace(/"/g, '""') || '',
                    cells[2].textContent.match(/Key: ([^\n]+)/)?.[1]?.trim().replace(/"/g, '""') || '',
                    cells[3].textContent.trim().replace(/"/g, '""'),
                    cells[4].textContent.trim().replace(/"/g, '""'),
                    cells[5].textContent.trim().replace(/"/g, '""'),
                    cells[6].textContent.trim().replace(/"/g, '""'),
                    cells[7].textContent.trim().replace(/"/g, '""')
                ];
                csv.push(rowData.map(d => `"${d}"`).join(','));
            });
            
            const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `purchases_export_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }
    
    // Initialize
    filterPurchases();
    updateClearButton();
});

// Show cover image in modal
function showCoverModal(imageUrl, title) {
    const modal = document.getElementById('coverModalEnhanced');
    const img = document.getElementById('modalCoverImageEnhanced');
    img.src = imageUrl;
    img.alt = title;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeCoverModalEnhanced(event) {
    if (event.target.id === 'coverModalEnhanced' || event.target.classList.contains('cover-modal-close-enhanced')) {
        const modal = document.getElementById('coverModalEnhanced');
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('coverModalEnhanced');
        if (modal && modal.classList.contains('show')) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
});

// Show purchase details in modal
function showPurchaseDetail(purchase) {
    const bookCover = purchase.book_cover ? 
        `/cms-data/book-covers/${purchase.book_cover}` : 
        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iI2U1ZTdlYiIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTYiIGZpbGw9IiM5Y2EzYWYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5ObyBDb3ZlcjwvdGV4dD48L3N2Zz4=';
    document.getElementById('modalBookCover').src = bookCover;
    document.getElementById('modalBookTitle').textContent = purchase.book_title || 'Unknown Book';
    document.getElementById('modalBookPublisher').textContent = purchase.book_publisher || 'Unknown Publisher';
    document.getElementById('modalBookId').textContent = purchase.book_id;
    document.getElementById('modalContentId').textContent = purchase.book_contentid || 'N/A';
    
    const ebookPrice = purchase.ebook_price ? `R${parseFloat(purchase.ebook_price).toFixed(2)}` : 'Not Available';
    const audiobookPrice = purchase.audiobook_price ? `R${parseFloat(purchase.audiobook_price).toFixed(2)}` : 'Not Available';
    document.getElementById('modalEbookPrice').textContent = ebookPrice;
    document.getElementById('modalAudiobookPrice').textContent = audiobookPrice;
    
    document.getElementById('modalCustomerEmail').textContent = purchase.user_email;
    document.getElementById('modalUserKey').textContent = purchase.user_key || 'N/A';
    
    const amount = purchase.amount > 0 ? `R${parseFloat(purchase.amount).toFixed(2)}` : 'FREE';
    document.getElementById('modalAmount').textContent = amount;
    document.getElementById('modalAmount').className = purchase.amount > 0 ? 'fs-5 fw-bold text-success' : 'fs-5 fw-bold text-muted';
    
    const statusBadge = document.getElementById('modalPaymentStatus');
    statusBadge.textContent = purchase.payment_status;
    statusBadge.className = `badge ${purchase.payment_status === 'COMPLETE' ? 'bg-success' : 
                                 purchase.payment_status === 'PENDING' ? 'bg-warning' : 'bg-danger'}`;
    
    const formatBadge = document.getElementById('modalFormat');
    formatBadge.textContent = purchase.format;
    formatBadge.className = `badge ${purchase.format === 'Ebook' ? 'bg-primary' : 
                                 purchase.format === 'Audiobook' ? 'bg-info' : 'bg-secondary'}`;
    
    const purchaseDate = new Date(purchase.payment_date);
    document.getElementById('modalPurchaseDate').textContent = purchaseDate.toLocaleString();
    document.getElementById('modalPaymentId').textContent = purchase.payment_id;
    document.getElementById('modalPurchaseId').textContent = `#${purchase.id}`;
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>
