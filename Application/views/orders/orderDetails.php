<?php
// Ensure connection is available before including nav
if (!isset($conn)) {
    require_once __DIR__ . "/../../Config/connection.php";
}
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/nav.php";
?>

<style>
.order-details-page {
    padding: 2rem 0;
    min-height: 60vh;
}

.order-details-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-header-section {
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 1.5rem;
    margin-bottom: 2rem;
}

.order-number-large {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.order-date-large {
    color: #666;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.order-status-large {
    display: inline-block;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-processing { background: #cfe2ff; color: #084298; }
.status-packed { background: #d1ecf1; color: #055160; }
.status-shipped { background: #d4edda; color: #155724; }
.status-out_for_delivery { background: #d1ecf1; color: #0c5460; }
.status-delivered { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.status-returned { background: #f8d7da; color: #721c24; }

.payment-status-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: capitalize;
}

.payment-paid { background: #d4edda; color: #155724; }
.payment-pending { background: #fff3cd; color: #856404; }
.payment-failed { background: #f8d7da; color: #721c24; }
.payment-refunded { background: #f8d7da; color: #721c24; }

.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
    }
}

.details-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.detail-item {
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.detail-label {
    font-weight: 600;
    color: #666;
    display: inline-block;
    min-width: 120px;
}

.detail-value {
    color: #333;
}

.order-items-section {
    margin-top: 2rem;
}

.order-item-detail {
    display: flex;
    gap: 1rem;
    align-items: center;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.order-item-img-large {
    width: 80px;
    height: 110px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.order-item-details {
    flex: 1;
}

.order-item-title-large {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.order-item-meta-large {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.order-item-price {
    font-size: 1rem;
    font-weight: 600;
    color: #007bff;
}

.order-summary-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 2rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    font-size: 1rem;
}

.summary-row.total {
    font-size: 1.25rem;
    font-weight: 600;
    padding-top: 1rem;
    border-top: 2px solid #dee2e6;
    margin-top: 1rem;
    color: #333;
}

.back-link {
    display: inline-block;
    margin-bottom: 1.5rem;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}

.back-link:hover {
    text-decoration: underline;
}
</style>

<div class="container order-details-page">
    <a href="/orders" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>Back to Orders
    </a>

    <div class="order-details-card">
        <div class="order-header-section">
            <div class="order-number-large">Order #<?= htmlspecialchars($order['order_number'] ?? $order['id']) ?></div>
            <div class="order-date-large">
                Placed on <?= date('F j, Y g:i A', strtotime($order['created_at'])) ?>
            </div>
            <span class="order-status-large status-<?= $order['order_status'] ?? 'pending' ?>">
                <?= ucfirst(str_replace('_', ' ', $order['order_status'] ?? 'pending')) ?>
            </span>
        </div>

        <div class="details-grid">
            <div class="details-section">
                <h3 class="section-title">Order Information</h3>
                <div class="detail-item">
                    <span class="detail-label">Order Number:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['order_number'] ?? $order['id']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Status:</span>
                    <span class="detail-value">
                        <?php 
                        $paymentStatus = $order['payment_status'] ?? 'pending';
                        $paymentClass = 'payment-' . strtolower($paymentStatus);
                        ?>
                        <span class="payment-status-badge <?= $paymentClass ?>">
                            <?= ucfirst($paymentStatus) ?>
                        </span>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value"><?= ucfirst($order['payment_method'] ?? 'N/A') ?></span>
                </div>
                <?php if (!empty($order['tracking_number'])): ?>
                <div class="detail-item">
                    <span class="detail-label">Tracking Number:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['tracking_number']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($order['full_name'])): ?>
            <div class="details-section">
                <h3 class="section-title">Delivery Address</h3>
                <div class="detail-item">
                    <span class="detail-value">
                        <?= htmlspecialchars($order['full_name']) ?><br>
                        <?= htmlspecialchars($order['street_address'] ?? '') ?>
                        <?php if (!empty($order['street_address2'])): ?>
                            <?= htmlspecialchars($order['street_address2']) ?><br>
                        <?php endif; ?>
                        <?= htmlspecialchars($order['local_area'] ?? '') ?><br>
                        <?= htmlspecialchars($order['zone'] ?? '') ?><br>
                        <?= htmlspecialchars($order['postal_code'] ?? '') ?><br>
                        <?php if (!empty($order['email'])): ?>
                            <strong>Email:</strong> <?= htmlspecialchars($order['email']) ?><br>
                        <?php endif; ?>
                        <?php if (!empty($order['phone'])): ?>
                            <strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="order-items-section">
            <h3 class="section-title">Order Items</h3>
            <?php if (!empty($order['items'])): ?>
                <?php foreach ($order['items'] as $item): 
                    $coverPath = ($item['cover_path'] ?? '/cms-data/book-covers/') . ($item['cover'] ?? '');
                    $title = $item['title'] ?? 'Unknown Book';
                    $author = $item['author'] ?? 'Unknown Author';
                    $quantity = $item['quantity'] ?? 1;
                    $unitPrice = $item['unit_price'] ?? 0;
                    $totalPrice = $unitPrice * $quantity;

                    // Determine book type and correct URL
                    $bookType = $item['book_type'] ?? 'regular';

                    if ($bookType === 'academic') {
                        $bookId = $item['academic_public_key'] ?? $item['book_id'] ?? '';
                        $bookUrl = !empty($bookId) ? '/library/academic/' . urlencode($bookId) : '#';
                    } else {
                        $contentId = $item['regular_content_id'] ?? $item['book_id'] ?? '';
                        $bookUrl = !empty($contentId) ? '/library/book/' . urlencode($contentId) : '#';
                    }
                ?>
                    <div class="order-item-detail">
                        <a href="<?= $bookUrl ?>" class="d-flex align-items-center text-decoration-none text-reset">
                            <img src="<?= $coverPath ?>" 
                                 alt="<?= htmlspecialchars($title) ?>" 
                                 class="order-item-img-large"
                                 onerror="this.src='/assets/img/default-book.png'">
                            <div class="order-item-details ms-3">
                                <div class="order-item-title-large"><?= htmlspecialchars($title) ?></div>
                                <div class="order-item-meta-large">
                                    <?= htmlspecialchars($author) ?>
                                </div>
                                <div class="order-item-meta-large">
                                    Quantity: <?= $quantity ?> × R<?= number_format($unitPrice, 2) ?>
                                </div>
                            </div>
                            <div class="order-item-price ms-auto">
                                R<?= number_format($totalPrice, 2) ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No items found for this order.</p>
            <?php endif; ?>
        </div>

        <div class="order-summary-section">
            <h3 class="section-title">Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>R<?= number_format(($order['total_amount'] ?? 0) - ($order['shipping_fee'] ?? 0), 2) ?></span>
            </div>
            <div class="summary-row">
                <span>Shipping:</span>
                <span>R<?= number_format($order['shipping_fee'] ?? 0, 2) ?></span>
            </div>
            <div class="summary-row total">
                <span>Total:</span>
                <span>R<?= number_format($order['total_amount'] ?? 0, 2) ?></span>
            </div>
            
            <?php 
            $paymentStatus = $order['payment_status'] ?? 'pending';
            $paymentClass = 'payment-' . strtolower($paymentStatus);
            ?>
            <div class="mt-4 pt-3 border-top">
                <div class="mb-3">
                    <span class="payment-status-badge <?= $paymentClass ?>">
                        Payment Status: <?= ucfirst($paymentStatus) ?>
                    </span>
                </div>
                <?php if ($paymentStatus !== 'paid'): ?>
                    <a href="/orders/<?= $order['id'] ?>/retry-payment" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-credit-card me-2"></i>Pay Now / Retry Payment
                    </a>
                    <?php
                    require_once __DIR__ . '/../../helpers/OrderHelper.php';
                    if (isOrderDeletable($paymentStatus)) {
                        echo '<div class="mt-2">';
                        echo getOrderDeleteButton($order['id'], 'w-100');
                        echo '</div>';
                    }
                    ?>
                    <p class="text-muted small mt-2 mb-0">Complete your payment to process this order</p>
                <?php else: ?>
                    <p class="text-success small mt-2 mb-0">
                        <i class="fas fa-check-circle me-2"></i>Payment completed successfully
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
<?php require_once __DIR__ . "/../includes/scripts.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle order deletion
    const deleteButton = document.querySelector('.delete-order-btn');
    
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            
            if (!confirm('Are you sure you want to remove this order? This action cannot be undone.')) {
                return;
            }
            
            // Disable button during request
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Removing...';
            
            // Send DELETE request (with POST fallback)
            const deleteOrder = async () => {
                try {
                    let response = await fetch(`/orders/${orderId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    // If DELETE fails, try POST fallback
                    if (!response.ok && response.status === 404) {
                        response = await fetch(`/orders/${orderId}/delete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                    }
                    
                    return response;
                } catch (error) {
                    // Try POST fallback on error
                    return fetch(`/orders/${orderId}/delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                }
            };
            
            deleteOrder()
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Redirect to orders page
                    window.location.href = '/orders?deleted=1';
                } else {
                    alert('Failed to remove order: ' + (data.message || 'Unknown error'));
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-trash me-2"></i>Remove Order';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the order. Please try again.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-trash me-2"></i>Remove Order';
            });
        });
    }
});
</script>
