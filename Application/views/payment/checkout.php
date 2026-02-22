<?php
// views/payment/checkout.php
// Expected: $book, $user, $format, $data, $price

// Ensure all required variables are set
if (!isset($book) || !isset($user) || !isset($format) || !isset($data) || !isset($price)) {
    die("Missing required checkout data. Please try again.");
}

// Handle both regular books (uppercase keys) and academic books (lowercase keys)
$title = htmlspecialchars($book['TITLE'] ?? $book['title'] ?? 'Untitled Book');
$author = htmlspecialchars($book['AUTHORS'] ?? $book['author'] ?? 'Unknown Author');
$category = htmlspecialchars($book['CATEGORY'] ?? $book['category'] ?? 'General');

// Handle cover image - regular books use COVER, academic books use cover_image_path
$cover = '';
if (isset($book['COVER']) && !empty($book['COVER'])) {
    $cover = '/cms-data/book-covers/' . htmlspecialchars($book['COVER']);
} elseif (isset($book['cover_image_path']) && !empty($book['cover_image_path'])) {
    $cover = '/cms-data/academic/covers/' . htmlspecialchars($book['cover_image_path']);
} else {
    $cover = '/assets/img/default-book.png';
}

$formatLabel = ucfirst($format ?? 'Ebook');
$priceFormatted = number_format((float)($price ?? 0), 2);
$userEmail = htmlspecialchars($user['ADMIN_EMAIL'] ?? '');
$userName = htmlspecialchars($user['ADMIN_NAME'] ?? 'Customer');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout – <?= $title ?> | SA Books Online</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #f5f7fa;
      color: #1a1a1a;
      line-height: 1.6;
    }

    .checkout-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    .checkout-header {
      text-align: center;
      margin-bottom: 40px;
      padding-bottom: 20px;
      border-bottom: 2px solid #e5e7eb;
    }

    .checkout-header h1 {
      font-size: 32px;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 8px;
    }

    .checkout-header p {
      font-size: 16px;
      color: #6b7280;
    }

    .checkout-grid {
      display: grid;
      grid-template-columns: 1fr 400px;
      gap: 30px;
      margin-top: 30px;
    }

    @media (max-width: 968px) {
      .checkout-grid {
        grid-template-columns: 1fr;
      }
    }

    .checkout-main {
      background: #ffffff;
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .checkout-sidebar {
      background: #ffffff;
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      height: fit-content;
      position: sticky;
      top: 20px;
    }

    .section-title {
      font-size: 20px;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 24px;
      padding-bottom: 12px;
      border-bottom: 1px solid #e5e7eb;
    }

    .order-item {
      display: flex;
      gap: 16px;
      margin-bottom: 24px;
      padding-bottom: 24px;
      border-bottom: 1px solid #e5e7eb;
    }

    .order-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .order-item-image {
      width: 80px;
      height: 120px;
      object-fit: cover;
      border-radius: 8px;
      flex-shrink: 0;
    }

    .order-item-details {
      flex: 1;
    }

    .order-item-title {
      font-size: 16px;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 4px;
    }

    .order-item-author {
      font-size: 14px;
      color: #6b7280;
      margin-bottom: 8px;
    }

    .order-item-format {
      display: inline-block;
      font-size: 12px;
      padding: 4px 12px;
      background: #f3f4f6;
      color: #4b5563;
      border-radius: 6px;
      margin-top: 8px;
    }

    .order-summary {
      margin-top: 24px;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      font-size: 15px;
    }

    .summary-row.subtotal {
      border-bottom: 1px solid #e5e7eb;
      margin-bottom: 12px;
    }

    .summary-row.total {
      font-size: 18px;
      font-weight: 700;
      color: #1a1a1a;
      padding-top: 16px;
      border-top: 2px solid #1a1a1a;
      margin-top: 12px;
    }

    .summary-label {
      color: #6b7280;
    }

    .summary-value {
      color: #1a1a1a;
      font-weight: 500;
    }

    .payment-section {
      margin-top: 32px;
    }

    .payment-method {
      background: #f9fafb;
      border: 2px solid #e5e7eb;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 24px;
      transition: all 0.2s;
    }

    .payment-method.selected {
      border-color: #2563eb;
      background: #eff6ff;
    }

    .payment-method-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }

    .payment-method-name {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 16px;
      font-weight: 600;
      color: #1a1a1a;
    }

    .payment-method-logos {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .payment-method-logos img {
      height: 24px;
      width: auto;
    }

    .payment-method-description {
      font-size: 14px;
      color: #6b7280;
      line-height: 1.5;
    }

    .billing-info {
      background: #f9fafb;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 24px;
    }

    .billing-info h3 {
      font-size: 16px;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 12px;
    }

    .billing-info p {
      font-size: 14px;
      color: #4b5563;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .billing-info i {
      color: #10b981;
    }

    .pay-button {
      width: 100%;
      background: #2563eb;
      color: #ffffff;
      border: none;
      border-radius: 10px;
      padding: 16px 24px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .pay-button:hover {
      background: #1d4ed8;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .pay-button:active {
      transform: translateY(0);
    }

    .security-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      margin-top: 24px;
      padding-top: 24px;
      border-top: 1px solid #e5e7eb;
    }

    .security-badge {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: #6b7280;
    }

    .security-badge i {
      color: #10b981;
      font-size: 16px;
    }

    .legal-links {
      text-align: center;
      margin-top: 24px;
      padding-top: 24px;
      border-top: 1px solid #e5e7eb;
    }

    .legal-links a {
      color: #2563eb;
      text-decoration: none;
      font-size: 13px;
      margin: 0 8px;
      transition: color 0.2s;
    }

    .legal-links a:hover {
      color: #1d4ed8;
      text-decoration: underline;
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #6b7280;
    }
  </style>
</head>
<body>
  <?php require_once __DIR__ . "/../includes/navv.php"; ?>

  <div class="checkout-container">
    <div class="checkout-header">
      <h1>Checkout</h1>
      <p>Complete your purchase securely</p>
    </div>

    <div class="checkout-grid">
      <!-- Main Checkout Section -->
      <div class="checkout-main">
        <h2 class="section-title">Payment Method</h2>

        <div class="payment-section">
          <div class="payment-method selected">
            <div class="payment-method-header">
              <div class="payment-method-name">
                <i class="fas fa-lock" style="color: #10b981;"></i>
                <span>PayFast</span>
              </div>
              <div class="payment-method-logos">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/EFTPOS_logo.svg" alt="EFT">
              </div>
            </div>
            <div class="payment-method-description">
              <i class="fas fa-shield-alt" style="color: #10b981; margin-right: 6px;"></i>
              Secure payment gateway. You will be redirected to PayFast to complete your purchase.
            </div>
          </div>

          <div class="billing-info">
            <h3><i class="fas fa-user"></i> Billing Information</h3>
            <p>
              <i class="fas fa-check-circle"></i>
              <strong>Name:</strong> <?= $userName ?>
            </p>
            <p>
              <i class="fas fa-envelope"></i>
              <strong>Email:</strong> <?= $userEmail ?>
            </p>
          </div>

          <form id="payfastForm" action="https://www.payfast.co.za/eng/process" method="post">
            <?php foreach ($data as $name => $value): ?>
              <input type="hidden" name="<?= htmlspecialchars($name) ?>" value="<?= htmlspecialchars($value) ?>">
            <?php endforeach; ?>
            <button type="submit" class="pay-button">
              <i class="fas fa-lock"></i>
              Complete Payment - R<?= $priceFormatted ?>
            </button>
          </form>

          <div class="security-badges">
            <div class="security-badge">
              <i class="fas fa-lock"></i>
              <span>256-bit SSL Encrypted</span>
            </div>
            <div class="security-badge">
              <i class="fas fa-shield-alt"></i>
              <span>PCI DSS Compliant</span>
            </div>
            <div class="security-badge">
              <i class="fas fa-user-shield"></i>
              <span>Secure & Protected</span>
            </div>
          </div>

          <div class="legal-links">
            <a href="https://sabooksonline.co.za/public/documents/terms" target="_blank">Terms & Conditions</a> |
            <a href="https://sabooksonline.co.za/public/documents/ReturnPolicy.pdf" target="_blank">Refund Policy</a> |
            <a href="https://sabooksonline.co.za/public/documents/Delivery.pdf" target="_blank">Delivery Info</a>
          </div>
        </div>
      </div>

      <!-- Order Summary Sidebar -->
      <div class="checkout-sidebar">
        <h2 class="section-title">Order Summary</h2>

        <div class="order-item">
          <img src="<?= $cover ?>" alt="<?= $title ?>" class="order-item-image" onerror="this.src='/assets/img/default-book.png'">
          <div class="order-item-details">
            <div class="order-item-title"><?= $title ?></div>
            <div class="order-item-author"><?= $author ?></div>
            <span class="order-item-format"><?= $formatLabel ?></span>
          </div>
        </div>

        <div class="order-summary">
          <div class="summary-row subtotal">
            <span class="summary-label">Subtotal</span>
            <span class="summary-value">R<?= $priceFormatted ?></span>
          </div>
          <div class="summary-row">
            <span class="summary-label">Tax</span>
            <span class="summary-value">Included</span>
          </div>
          <div class="summary-row total">
            <span>Total</span>
            <span>R<?= $priceFormatted ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Payment method selection (if multiple methods in future)
    document.querySelectorAll('.payment-method').forEach(method => {
      method.addEventListener('click', () => {
        document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
        method.classList.add('selected');
      });
    });
  </script>

  <?php include __DIR__ . "/../includes/scripts.php"; ?>
</body>
</html>
