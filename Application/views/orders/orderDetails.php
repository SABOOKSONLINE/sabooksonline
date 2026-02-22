<?php
// Ensure connection is available before including nav
if (!isset($conn)) {
    require_once __DIR__ . "/../../Config/connection.php";
}
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/nav.php";

$order = $orderData['order'];
$items = $orderData['items'] ?? [];
$address = $order;
$status = $order['order_status'] ?? 'pending';
$statusSteps = [
    'pending' => 1,
    'processing' => 2,
    'packed' => 3,
    'shipped' => 4,
    'out_for_delivery' => 5,
    'delivered' => 6
];
$currentStep = $statusSteps[$status] ?? 1;
?>

<style>
.order-details-page {
    padding: 2rem 0;
    min-height: 60vh;
}

.order-details-header {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.order-info-row:last-child {
    margin-bottom: 0;
}

.order-label {
    font-weight: 600;
    color: #333;
}

.order-value {
    color: #666;
}

.tracking-section {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tracking-timeline {
    position: relative;
    padding-left: 2rem;
    margin-top: 1.5rem;
}

.tracking-timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.tracking-step {
    position: relative;
    padding-bottom: 2rem;
}

.tracking-step:last-child {
    padding-bottom: 0;
}

.tracking-step::before {
    content: '';
    position: absolute;
    left: -1.75rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #e0e0e0;
    border: 2px solid #fff;
    z-index: 1;
}

.tracking-step.active::before {
    background: #007bff;
    border-color: #007bff;
}

.tracking-step.completed::before {
    background: #28a745;
    border-color: #28a745;
}

.tracking-step-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.tracking-step.completed .tracking-step-title {
    color: #28a745;
}

.tracking-step.active .tracking-step-title {
    color: #007bff;
}

.tracking-step-date {
    font-size: 0.85rem;
    color: #666;
}

.items-section {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.items-table {
    width: 100%;
    border-collapse: collapse;
}

.items-table th {
    text-align: left;
    padding: 1rem;
    border-bottom: 2px solid #e0e0e0;
    font-weight: 600;
    color: #333;
}

.items-table td {
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.item-image {
    width: 60px;
    height: 85px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.item-info {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.item-details h5 {
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
    color: #333;
}

.item-details p {
    margin: 0;
    font-size: 0.85rem;
    color: #666;
}

.address-section {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.address-section h3 {
    margin-bottom: 1rem;
    color: #333;
}

.address-details {
    line-height: 1.8;
    color: #666;
}

.summary-section {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-row:last-child {
    border-bottom: none;
    font-weight: 600;
    font-size: 1.1rem;
    margin-top: 0.5rem;
    padding-top: 1rem;
    border-top: 2px solid #e0e0e0;
}

.back-link {
    display: inline-block;
    margin-bottom: 1.5rem;
    color: #007bff;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}

.tracking-number {
    background: #f8f9fa;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-family: monospace;
    display: inline-block;
    margin-top: 0.5rem;
}
</style>

<div class="container order-details-page">
    <a href="/orders" class="back-link">
        <i class="fas fa-arrow-left me-2"></i> Back to Orders
    </a>

    <div class="order-details-header">
        <div class="order-info-row">
            <div>
                <div class="order-label">Order Number</div>
                <div class="order-value">#<?= htmlspecialchars($order['order_number'] ?? $order['id']) ?></div>
            </div>
            <div>
                <div class="order-label">Order Status</div>
                <span class="order-status status-<?= $status ?>">
                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                </span>
            </div>
        </div>
        <div class="order-info-row">
            <div>
                <div class="order-label">Order Date</div>
                <div class="order-value"><?= date('F j, Y g:i A', strtotime($order['created_at'])) ?></div>
            </div>
            <?php if (!empty($order['tracking_number'])): ?>
                <div>
                    <div class="order-label">Tracking Number</div>
                    <div class="tracking-number"><?= htmlspecialchars($order['tracking_number']) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="tracking-section">
        <h3>Order Tracking</h3>
        <div class="tracking-timeline">
            <div class="tracking-step <?= $currentStep >= 1 ? ($currentStep == 1 ? 'active' : 'completed') : '' ?>">
                <div class="tracking-step-title">Order Placed</div>
                <div class="tracking-step-date"><?= date('M j, Y', strtotime($order['created_at'])) ?></div>
            </div>
            <div class="tracking-step <?= $currentStep >= 2 ? ($currentStep == 2 ? 'active' : 'completed') : '' ?>">
                <div class="tracking-step-title">Processing</div>
                <?php if ($currentStep >= 2): ?>
                    <div class="tracking-step-date">In progress</div>
                <?php endif; ?>
            </div>
            <div class="tracking-step <?= $currentStep >= 3 ? ($currentStep == 3 ? 'active' : 'completed') : '' ?>">
                <div class="tracking-step-title">Packed</div>
                <?php if ($currentStep >= 3): ?>
                    <div class="tracking-step-date">Ready for shipping</div>
                <?php endif; ?>
            </div>
            <div class="tracking-step <?= $currentStep >= 4 ? ($currentStep == 4 ? 'active' : 'completed') : '' ?>">
                <div class="tracking-step-title">Shipped</div>
                <?php if ($currentStep >= 4): ?>
                    <div class="tracking-step-date">On the way</div>
                <?php endif; ?>
            </div>
            <div class="tracking-step <?= $currentStep >= 5 ? ($currentStep == 5 ? 'active' : 'completed') : '' ?>">
                <div class="tracking-step-title">Out for Delivery</div>
                <?php if ($currentStep >= 5): ?>
                    <div class="tracking-step-date">Arriving soon</div>
                <?php endif; ?>
            </div>
            <div class="tracking-step <?= $currentStep >= 6 ? 'completed' : '' ?>">
                <div class="tracking-step-title">Delivered</div>
                <?php if ($currentStep >= 6): ?>
                    <div class="tracking-step-date"><?= date('M j, Y', strtotime($order['updated_at'])) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="items-section">
        <h3>Order Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): 
                    $coverPath = ($item['cover_path'] ?? '/cms-data/book-covers/') . ($item['cover'] ?? '');
                    $title = $item['title'] ?? 'Unknown Book';
                    $author = $item['author'] ?? 'Unknown Author';
                    $quantity = $item['quantity'] ?? 1;
                    $unitPrice = $item['unit_price'] ?? 0;
                    $totalPrice = $item['total_price'] ?? ($unitPrice * $quantity);
                ?>
                    <tr>
                        <td>
                            <div class="item-info">
                                <img src="<?= $coverPath ?>" 
                                     alt="<?= htmlspecialchars($title) ?>" 
                                     class="item-image"
                                     onerror="this.src='/assets/img/default-book.png'">
                                <div class="item-details">
                                    <h5><?= htmlspecialchars($title) ?></h5>
                                    <p><?= htmlspecialchars($author) ?></p>
                                    <?php if (($item['book_type'] ?? 'regular') === 'academic'): ?>
                                        <span class="badge bg-info" style="font-size: 0.75rem;">Academic</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?= $quantity ?></td>
                        <td>R<?= number_format($unitPrice, 2) ?></td>
                        <td>R<?= number_format($totalPrice, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="address-section">
                <h3>Delivery Address</h3>
                <div class="address-details">
                    <strong><?= htmlspecialchars($address['full_name'] ?? '') ?></strong><br>
                    <?php if (!empty($address['company'])): ?>
                        <?= htmlspecialchars($address['company']) ?><br>
                    <?php endif; ?>
                    <?= htmlspecialchars($address['street_address'] ?? '') ?><br>
                    <?php if (!empty($address['street_address2'])): ?>
                        <?= htmlspecialchars($address['street_address2']) ?><br>
                    <?php endif; ?>
                    <?= htmlspecialchars($address['local_area'] ?? '') ?><br>
                    <?= htmlspecialchars($address['zone'] ?? '') ?><br>
                    <?= htmlspecialchars($address['postal_code'] ?? '') ?><br>
                    <?= htmlspecialchars($address['country'] ?? 'South Africa') ?><br><br>
                    <strong>Phone:</strong> <?= htmlspecialchars($address['phone'] ?? '') ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($address['email'] ?? '') ?><br>
                    <strong>Delivery Type:</strong> <?= htmlspecialchars($address['delivery_type'] ?? 'Standard') ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="summary-section">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>R<?= number_format(($order['total_amount'] ?? 0) - ($order['shipping_fee'] ?? 0), 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>R<?= number_format($order['shipping_fee'] ?? 0, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Payment Method</span>
                    <span><?= ucfirst($order['payment_method'] ?? 'Not specified') ?></span>
                </div>
                <div class="summary-row">
                    <span>Payment Status</span>
                    <span><?= ucfirst($order['payment_status'] ?? 'pending') ?></span>
                </div>
                <div class="summary-row">
                    <span>Total</span>
                    <span>R<?= number_format($order['total_amount'] ?? 0, 2) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
<?php require_once __DIR__ . "/../includes/scripts.php"; ?>
