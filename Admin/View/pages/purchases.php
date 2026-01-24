<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";

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
    "Summary of book purchase statistics"
);
?>

<div class="row mb-4">
    <?php
    $cards = [
        [
            "title" => "Total Purchases",
            "value" => count($data["book_purchases"]["all"]),
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
            "title" => "Completed Purchases",
            "value" => count(array_filter($data["book_purchases"]["all"], fn($p) => $p['payment_status'] === 'COMPLETE')),
            "icon"  => "fas fa-check-circle",
            "color" => "success"
        ],
        [
            "title" => "Pending Purchases",
            "value" => count(array_filter($data["book_purchases"]["all"], fn($p) => $p['payment_status'] !== 'COMPLETE')),
            "icon"  => "fas fa-clock",
            "color" => "warning"
        ]
    ];

    foreach ($cards as $card) {
        renderAnalysisCard($card["title"], $card["value"], $card["icon"], $card["color"]);
    }
    ?>
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
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>