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

<script>
// Add DataTables functionality if available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#purchasesTable').DataTable({
            "pageLength": 25,
            "order": [[ 6, "desc" ]], // Sort by date descending
            "columnDefs": [
                { "orderable": false, "targets": [1] } // Disable sorting on book details column
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