<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/cards/aCard.php";  // Include analysis card helper

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Book Purchases";
ob_start();

renderHeading(
    "Book Purchases",
    "View and manage all book purchases with detailed information."
);

renderAlerts();

renderSectionHeader(
    "Purchase Overview", 
    "Summary of book purchase statistics including formats and revenue breakdown"
);
?>

<div class="row mb-4">
    <?php
    // Calculate detailed statistics
    $totalPurchases = count($data["book_purchases"]["all"]);
    $completedPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => $p['payment_status'] === 'COMPLETE'));
    $pendingPurchases = $totalPurchases - $completedPurchases;
    $ebookPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => strtolower($p['format']) === 'ebook'));
    $audiobookPurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => strtolower($p['format']) === 'audiobook'));
    $otherFormatPurchases = $totalPurchases - $ebookPurchases - $audiobookPurchases;
    $freePurchases = count(array_filter($data["book_purchases"]["all"], fn($p) => floatval($p['amount']) == 0));
    $paidPurchases = $totalPurchases - $freePurchases;
    
    $cards = [
        [
            "title" => "Total Purchases",
            "value" => $totalPurchases,
            "icon"  => "fas fa-shopping-cart",
            "color" => "primary"
        ],
        [
            "title" => "Total Revenue",
            "value" => "R" . number_format($data["book_purchases"]["revenue"], 2),
            "icon"  => "fas fa-coins",
            "color" => "success"
        ],
        [
            "title" => "Ebook Purchases",
            "value" => $ebookPurchases,
            "icon"  => "fas fa-book",
            "color" => "info"
        ],
        [
            "title" => "Audiobook Purchases",
            "value" => $audiobookPurchases,
            "icon"  => "fas fa-headphones",
            "color" => "warning"
        ],
        [
            "title" => "Completed Sales",
            "value" => $completedPurchases,
            "icon"  => "fas fa-check-circle",
            "color" => "success"
        ],
        [
            "title" => "Paid Purchases",
            "value" => $paidPurchases,
            "icon"  => "fas fa-money-bill-wave",
            "color" => "success"
        ],
        [
            "title" => "Free Downloads",
            "value" => $freePurchases,
            "icon"  => "fas fa-gift",
            "color" => "secondary"
        ],
        [
            "title" => "Pending Orders",
            "value" => $pendingPurchases,
            "icon"  => "fas fa-clock",
            "color" => "danger"
        ]
    ];

    foreach ($cards as $card) {
        renderAnalysisCard($card["title"], $card["value"], $card["icon"], $card["color"]);
    }
    ?>
</div>

<!-- Format Breakdown Section -->
<?php
renderSectionHeader(
    "Format & Revenue Analysis",
    "Detailed breakdown of purchases by format and payment status"
);
?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Format Breakdown</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fas fa-book text-info me-2"></i>Ebooks</span>
                        <strong><?= $ebookPurchases ?></strong>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: <?= $totalPurchases > 0 ? ($ebookPurchases / $totalPurchases * 100) : 0 ?>%;"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fas fa-headphones text-warning me-2"></i>Audiobooks</span>
                        <strong><?= $audiobookPurchases ?></strong>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: <?= $totalPurchases > 0 ? ($audiobookPurchases / $totalPurchases * 100) : 0 ?>%;"></div>
                    </div>
                </div>
                
                <?php if ($otherFormatPurchases > 0): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fas fa-file-alt text-secondary me-2"></i>Other Formats</span>
                        <strong><?= $otherFormatPurchases ?></strong>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-secondary" style="width: <?= $totalPurchases > 0 ? ($otherFormatPurchases / $totalPurchases * 100) : 0 ?>%;"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-money-check-alt me-2"></i>Revenue Breakdown</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fas fa-dollar-sign text-success me-2"></i>Paid Sales</span>
                        <strong><?= $paidPurchases ?></strong>
                    </div>
                    <small class="text-muted">Revenue: R<?= number_format($data["book_purchases"]["revenue"], 2) ?></small>
                    <div class="progress mt-1" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: <?= $totalPurchases > 0 ? ($paidPurchases / $totalPurchases * 100) : 0 ?>%;"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fas fa-gift text-info me-2"></i>Free Downloads</span>
                        <strong><?= $freePurchases ?></strong>
                    </div>
                    <small class="text-muted">No revenue generated</small>
                    <div class="progress mt-1" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: <?= $totalPurchases > 0 ? ($freePurchases / $totalPurchases * 100) : 0 ?>%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-white">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Status Overview</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fas fa-check-circle text-success me-2"></i>Completed</span>
                        <strong><?= $completedPurchases ?></strong>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: <?= $totalPurchases > 0 ? ($completedPurchases / $totalPurchases * 100) : 0 ?>%;"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fas fa-clock text-warning me-2"></i>Pending/Failed</span>
                        <strong><?= $pendingPurchases ?></strong>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: <?= $totalPurchases > 0 ? ($pendingPurchases / $totalPurchases * 100) : 0 ?>%;"></div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Success Rate: <strong><?= $totalPurchases > 0 ? number_format(($completedPurchases / $totalPurchases * 100), 1) : 0 ?>%</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
renderSectionHeader(
    "All Book Purchases",
    "Complete list of book purchases with customer and book details"
);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($data["book_purchases"]["all"])): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No book purchases yet</h5>
                        <p class="text-muted">When customers purchase books, they will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <!-- Filter Options -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select class="form-select" id="formatFilter" onchange="filterTable()">
                                    <option value="">All Formats</option>
                                    <option value="Ebook">Ebooks Only</option>
                                    <option value="Audiobook">Audiobooks Only</option>
                                    <option value="Other">Other Formats</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="statusFilter" onchange="filterTable()">
                                    <option value="">All Status</option>
                                    <option value="COMPLETE">Completed</option>
                                    <option value="PENDING">Pending</option>
                                    <option value="FAILED">Failed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="paymentFilter" onchange="filterTable()">
                                    <option value="">All Payments</option>
                                    <option value="paid">Paid Only</option>
                                    <option value="free">Free Only</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="fas fa-times me-2"></i>Clear Filters
                                </button>
                            </div>
                        </div>
                        
                        <table class="table table-striped table-hover" id="purchasesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Book Details</th>
                                    <th>Customer</th>
                                    <th>Format</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Purchase Date</th>
                                    <th>Payment ID</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data["book_purchases"]["all"] as $purchase): ?>
                                    <tr>
                                        <td>
                                            <strong>#<?= htmlspecialchars($purchase['id']) ?></strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($purchase['book_cover'])): ?>
                                                    <img src="/cms-data/book-covers/<?= htmlspecialchars($purchase['book_cover']) ?>" 
                                                         alt="Cover" class="me-3 rounded shadow-sm" 
                                                         style="width: 50px; height: 75px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="me-3 rounded bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 75px;">
                                                        <i class="fas fa-book text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-bold mb-1">
                                                        <?= htmlspecialchars($purchase['book_title'] ?? 'Unknown Book') ?>
                                                    </div>
                                                    <small class="text-muted d-block">
                                                        Publisher: <?= htmlspecialchars($purchase['book_publisher'] ?? 'Unknown') ?>
                                                    </small>
                                                    <small class="text-muted">
                                                        Book ID: <?= htmlspecialchars($purchase['book_id']) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?= htmlspecialchars($purchase['user_email']) ?></strong>
                                            </div>
                                            <small class="text-muted">
                                                User Key: <?= htmlspecialchars($purchase['user_key']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $purchase['format'] === 'Ebook' ? 'primary' : ($purchase['format'] === 'Audiobook' ? 'info' : 'secondary') ?>">
                                                <i class="fas fa-<?= $purchase['format'] === 'Ebook' ? 'book' : ($purchase['format'] === 'Audiobook' ? 'headphones' : 'file') ?>"></i>
                                                <?= htmlspecialchars(ucfirst($purchase['format'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="<?= $purchase['amount'] > 0 ? 'text-success' : 'text-muted' ?>">
                                                <?= $purchase['amount'] > 0 ? 'R' . number_format($purchase['amount'], 2) : 'FREE' ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $purchase['payment_status'] === 'COMPLETE' ? 'success' : ($purchase['payment_status'] === 'PENDING' ? 'warning' : 'danger') ?>">
                                                <i class="fas fa-<?= $purchase['payment_status'] === 'COMPLETE' ? 'check' : ($purchase['payment_status'] === 'PENDING' ? 'clock' : 'times') ?>"></i>
                                                <?= htmlspecialchars($purchase['payment_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <?= date('M j, Y', strtotime($purchase['payment_date'])) ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= date('g:i A', strtotime($purchase['payment_date'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="font-monospace">
                                                <?= htmlspecialchars($purchase['payment_id']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#purchaseDetailModal" 
                                                    onclick="showPurchaseDetail(<?= htmlspecialchars(json_encode($purchase)) ?>)">
                                                <i class="fas fa-eye"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Detail Modal -->
<div class="modal fade" id="purchaseDetailModal" tabindex="-1" aria-labelledby="purchaseDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purchaseDetailModalLabel">
                    <i class="fas fa-shopping-cart me-2"></i>Purchase Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Book Information -->
                    <div class="col-md-5">
                        <h6 class="fw-bold mb-3"><i class="fas fa-book me-2"></i>Book Information</h6>
                        <div class="text-center mb-3">
                            <img id="modalBookCover" src="" alt="Book Cover" 
                                 class="img-fluid rounded shadow" style="max-height: 250px;">
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
                    
                    <!-- Purchase Information -->
                    <div class="col-md-7">
                        <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Purchase Information</h6>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2"><i class="fas fa-user me-2"></i>Customer</h6>
                                        <p class="card-text mb-1"><strong id="modalCustomerEmail">-</strong></p>
                                        <small class="text-muted">User Key: <span id="modalUserKey" class="font-monospace">-</span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card bg-light mb-3">
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
// Show purchase details in modal
function showPurchaseDetail(purchase) {
    // Book Information
    const bookCover = purchase.book_cover ? 
        `/cms-data/book-covers/${purchase.book_cover}` : 
        '/img/lazy-placeholder.jpg';
    document.getElementById('modalBookCover').src = bookCover;
    document.getElementById('modalBookTitle').textContent = purchase.book_title || 'Unknown Book';
    document.getElementById('modalBookPublisher').textContent = purchase.book_publisher || 'Unknown Publisher';
    document.getElementById('modalBookId').textContent = purchase.book_id;
    document.getElementById('modalContentId').textContent = purchase.book_contentid || 'N/A';
    
    // Book pricing information
    const ebookPrice = purchase.ebook_price ? `R${parseFloat(purchase.ebook_price).toFixed(2)}` : 'Not Available';
    const audiobookPrice = purchase.audiobook_price ? `R${parseFloat(purchase.audiobook_price).toFixed(2)}` : 'Not Available';
    document.getElementById('modalEbookPrice').textContent = ebookPrice;
    document.getElementById('modalAudiobookPrice').textContent = audiobookPrice;
    
    // Customer Information
    document.getElementById('modalCustomerEmail').textContent = purchase.user_email;
    document.getElementById('modalUserKey').textContent = purchase.user_key || 'N/A';
    
    // Payment Information
    const amount = purchase.amount > 0 ? `R${parseFloat(purchase.amount).toFixed(2)}` : 'FREE';
    document.getElementById('modalAmount').textContent = amount;
    document.getElementById('modalAmount').className = purchase.amount > 0 ? 'fs-5 fw-bold text-success' : 'fs-5 fw-bold text-muted';
    
    // Payment Status Badge
    const statusBadge = document.getElementById('modalPaymentStatus');
    statusBadge.textContent = purchase.payment_status;
    statusBadge.className = `badge ${purchase.payment_status === 'COMPLETE' ? 'bg-success' : 
                                     purchase.payment_status === 'PENDING' ? 'bg-warning' : 'bg-danger'}`;
    
    // Format Badge
    const formatBadge = document.getElementById('modalFormat');
    formatBadge.textContent = purchase.format;
    formatBadge.className = `badge ${purchase.format === 'Ebook' ? 'bg-primary' : 
                                     purchase.format === 'Audiobook' ? 'bg-info' : 'bg-secondary'}`;
    
    // Other Information
    const purchaseDate = new Date(purchase.payment_date);
    document.getElementById('modalPurchaseDate').textContent = purchaseDate.toLocaleString();
    document.getElementById('modalPaymentId').textContent = purchase.payment_id;
    document.getElementById('modalPurchaseId').textContent = `#${purchase.id}`;
}

// Filter functionality
function filterTable() {
    const formatFilter = document.getElementById('formatFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const paymentFilter = document.getElementById('paymentFilter').value;
    const table = document.getElementById('purchasesTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        let showRow = true;
        const cells = row.querySelectorAll('td');
        
        // Format filter
        if (formatFilter) {
            const formatCell = cells[3]; // Format column
            const formatText = formatCell.textContent.trim();
            if (formatFilter === 'Other') {
                showRow = showRow && !formatText.includes('Ebook') && !formatText.includes('Audiobook');
            } else {
                showRow = showRow && formatText.includes(formatFilter);
            }
        }
        
        // Status filter
        if (statusFilter) {
            const statusCell = cells[5]; // Status column
            showRow = showRow && statusCell.textContent.includes(statusFilter);
        }
        
        // Payment filter
        if (paymentFilter) {
            const amountCell = cells[4]; // Amount column
            const amountText = amountCell.textContent.trim();
            if (paymentFilter === 'free') {
                showRow = showRow && amountText.includes('FREE');
            } else if (paymentFilter === 'paid') {
                showRow = showRow && !amountText.includes('FREE');
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
    
    // Update visible row count
    updateFilterResults(rows);
}

function clearFilters() {
    document.getElementById('formatFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('paymentFilter').value = '';
    filterTable();
}

function updateFilterResults(rows) {
    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    const resultText = document.getElementById('filterResults');
    if (resultText) {
        resultText.textContent = `Showing ${visibleRows.length} of ${rows.length} purchases`;
    }
}

// Add DataTables functionality if available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#purchasesTable').DataTable({
            "pageLength": 25,
            "order": [[ 6, "desc" ]], // Sort by date descending
            "columnDefs": [
                { "orderable": false, "targets": [1, 8] } // Disable sorting on book details and actions columns
            ],
            "responsive": true,
            "language": {
                "search": "Search purchases:",
                "lengthMenu": "Show _MENU_ purchases per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ purchases",
                "infoEmpty": "No purchases found",
                "infoFiltered": "(filtered from _MAX_ total purchases)"
            }
        });
    }
    
    // Add filter results indicator
    const tableWrapper = document.querySelector('.table-responsive');
    if (tableWrapper) {
        const resultDiv = document.createElement('div');
        resultDiv.className = 'text-muted small mb-2';
        resultDiv.id = 'filterResults';
        tableWrapper.insertBefore(resultDiv, tableWrapper.firstChild);
        
        // Initial count
        const rows = document.querySelectorAll('#purchasesTable tbody tr');
        updateFilterResults(rows);
    }
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>