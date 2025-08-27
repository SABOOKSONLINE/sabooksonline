<?php
function renderPurchaseCard($purchases)
{
    // Limit items to show in card preview
    $previewLimit = 2;
    $preview = array_slice($purchases, 0, $previewLimit);
    $hasMore = count($purchases) > $previewLimit;
?>
    <div class="card purchased-books-summary-card rounded-4 shadow-sm p-4 mb-4">
        <h5 class="fw-bold mb-3">Purchased Books</h5>

        <?php if (empty($purchases)): ?>
            <p class="text-muted mb-0">No purchases yet.</p>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($preview as $purchase): ?>
                    <div class="col-12 col-md-6">
                        <div class="border rounded p-3 h-100">
                            <p class="mb-1 fw-semibold text-truncate" style="max-width: 200px;">
                                <?= htmlspecialchars($purchase['title']) ?>
                            </p>
                            <small class="text-muted d-block mb-1">
                                Format: <?= htmlspecialchars(ucfirst($purchase['format'])) ?> |
                                <?= date("d M Y", strtotime($purchase['payment_date'])) ?>
                            </small>
                            <div class="fw-bold">
                                <?= $purchase['amount'] == 0 ? "Free" : "R" . number_format($purchase['amount'], 2) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($hasMore): ?>
                <button type="button" class="btn btn-link p-0 mt-2" data-bs-toggle="modal" data-bs-target="#purchasesModal">
                    See All
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="purchasesModal" tabindex="-1" aria-labelledby="purchasesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable"> <!-- xl for wider modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="purchasesModalLabel">All Purchased Books</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <?php foreach ($purchases as $purchase): ?>
                            <div class="col-12 col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <p class="mb-1 fw-semibold"><?= htmlspecialchars($purchase['title']) ?></p>
                                    <small class="text-muted d-block mb-1">
                                        Format: <?= htmlspecialchars(ucfirst($purchase['format'])) ?> |
                                        <?= date("d M Y", strtotime($purchase['payment_date'])) ?>
                                    </small>
                                    <div class="fw-bold">
                                        <?= $purchase['amount'] == 0 ? "Free" : "R" . number_format($purchase['amount'], 2) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
