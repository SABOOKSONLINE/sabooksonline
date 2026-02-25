<?php
// Ensure connection is available before including nav
if (!isset($conn)) {
    require_once __DIR__ . "/../../Config/connection.php";
}
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/nav.php";
?>

<style>
.orders-page {
    padding: 2rem 0;
    min-height: 60vh;
}

.order-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s;
}

.order-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.order-number {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.order-date {
    color: #666;
    font-size: 0.9rem;
}

.order-status {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
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
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.payment-badge {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    text-transform: capitalize;
    display: inline-block;
}

.payment-paid { background: #d4edda; color: #155724; }
.payment-pending { background: #fff3cd; color: #856404; }
.payment-failed { background: #f8d7da; color: #721c24; }
.payment-refunded { background: #f8d7da; color: #721c24; }

.order-items {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.order-item {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 6px;
    flex: 0 0 auto;
    min-width: 200px;
}

.order-item-img {
    width: 50px;
    height: 70px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.order-item-info {
    flex: 1;
}

.order-item-title {
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
    color: #333;
}

.order-item-meta {
    font-size: 0.8rem;
    color: #666;
}

.order-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #e0e0e0;
    gap: 1rem;
}

.order-summary > div:first-child {
    flex: 1;
}

.order-total {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.view-order-btn {
    padding: 0.5rem 1.5rem;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background 0.3s;
}

.view-order-btn:hover {
    background: #0056b3;
    color: white;
}

.empty-orders {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.empty-orders-icon {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.page-heading {
    margin-bottom: 2rem;
}

.page-heading h1 {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.page-heading p {
    color: #666;
}
</style>

<div class="container orders-page">
    <div class="page-heading">
        <h1><?php 
            $userName = $_SESSION['ADMIN_NAME'] ?? '';
            $firstName = !empty($userName) ? explode(' ', trim($userName))[0] : '';
            echo !empty($firstName) ? htmlspecialchars($firstName) . "'s Orders" : "Your Orders";
        ?></h1>
        <p>Track and manage your book orders</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <div class="empty-orders-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3>No orders yet</h3>
            <p>You haven't placed any orders. Start shopping to see your orders here.</p>
            <a href="/library" class="btn btn-primary mt-3">Browse Books</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): 
            $items = $order['items'] ?? [];
            $status = $order['order_status'] ?? 'pending';
        ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-number">Order #<?= htmlspecialchars($order['order_number'] ?? $order['id']) ?></div>
                        <div class="order-date">
                            Placed on <?= date('F j, Y', strtotime($order['created_at'])) ?>
                        </div>
                    </div>
                    <span class="order-status status-<?= $status ?>">
                        <?= ucfirst(str_replace('_', ' ', $status)) ?>
                    </span>
                </div>

                <div class="order-items">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): 
                            $coverPath = ($item['cover_path'] ?? '/cms-data/book-covers/') . ($item['cover'] ?? '');
                            $title = $item['title'] ?? 'Unknown Book';
                            $author = $item['author'] ?? 'Unknown Author';

                            // Determine book type and correct URL
                            $bookType = $item['book_type'] ?? 'regular';

                            if ($bookType === 'academic') {
                                // Academic books: book_id / academic_public_key points to /library/academic/{publicKey}
                                $bookId = $item['academic_public_key'] ?? $item['book_id'] ?? '';
                                $bookUrl = !empty($bookId) ? '/library/academic/' . urlencode($bookId) : '#';
                            } else {
                                // Regular books: use CONTENTID for /library/book/{CONTENTID}
                                $contentId = $item['regular_content_id'] ?? $item['book_id'] ?? '';
                                $bookUrl = !empty($contentId) ? '/library/book/' . urlencode($contentId) : '#';
                            }
                        ?>
                            <div class="order-item">
                                <a href="<?= $bookUrl ?>" class="text-decoration-none d-flex align-items-center gap-2">
                                    <img src="<?= $coverPath ?>" 
                                         alt="<?= htmlspecialchars($title) ?>" 
                                         class="order-item-img"
                                         onerror="this.src='/assets/img/default-book.png'">
                                    <div class="order-item-info">
                                        <div class="order-item-title"><?= htmlspecialchars($title) ?></div>
                                        <div class="order-item-meta">
                                            <?= htmlspecialchars($author) ?> • Qty: <?= $item['quantity'] ?? 1 ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No items found for this order.</p>
                    <?php endif; ?>
                </div>

                <div class="order-summary">
                    <div>
                        <div class="order-total">
                            Total: R<?= number_format($order['total_amount'] ?? 0, 2) ?>
                        </div>
                        <?php 
                        $paymentStatus = $order['payment_status'] ?? 'pending';
                        $paymentClass = 'payment-' . strtolower($paymentStatus);
                        ?>
                        <div class="payment-status-badge mt-2">
                            <span class="payment-badge <?= $paymentClass ?>">
                                Payment: <?= ucfirst($paymentStatus) ?>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if ($paymentStatus !== 'paid'): ?>
                            <a href="/orders/<?= $order['id'] ?>/retry-payment" class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i>Pay Now
                            </a>
                            <?php
                            require_once __DIR__ . '/../../helpers/OrderHelper.php';
                            if (isOrderDeletable($paymentStatus)) {
                                echo getOrderDeleteButton($order['id'], 'btn-sm');
                            }
                            ?>
                        <?php endif; ?>
                        <a href="/orders/<?= $order['id'] ?>" class="view-order-btn">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
<?php require_once __DIR__ . "/../includes/scripts.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle order deletion
    const deleteButtons = document.querySelectorAll('.delete-order-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
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
                    // Remove the order card from DOM
                    const orderCard = this.closest('.order-card');
                    if (orderCard) {
                        orderCard.style.transition = 'opacity 0.3s';
                        orderCard.style.opacity = '0';
                        setTimeout(() => {
                            orderCard.remove();
                            
                            // Check if no orders left
                            const remainingOrders = document.querySelectorAll('.order-card');
                            if (remainingOrders.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
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
    });
});
</script>
