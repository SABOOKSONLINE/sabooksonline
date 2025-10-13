<?php
// views/payment/checkout.php
// Expected: $book, $user, $format, $data, $price

$title = htmlspecialchars($book['TITLE'] ?? 'Untitled Book');
$author = htmlspecialchars($book['AUTHORS'] ?? 'Unknown Author');
$category = htmlspecialchars($book['CATEGORY'] ?? 'General');
$cover = htmlspecialchars($book['COVER'] ?? '/assets/img/default-book.png');
$formatLabel = ucfirst($format);
$priceFormatted = number_format($price, 2);
$userEmail = htmlspecialchars($user['ADMIN_EMAIL'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout – <?= $title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f9fafb;
      margin: 0;
      color: #222;
    }
    .checkout-wrapper {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 40px;
      padding: 60px 20px;
      max-width: 1100px;
      margin: auto;
    }
    .checkout-left {
      flex: 1 1 580px;
      background: #fff;
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.05);
    }
    .checkout-right {
      flex: 0 0 320px;
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.05);
      align-self: flex-start;
    }
    h2 {
      font-size: 1.6rem;
      font-weight: 600;
      margin-bottom: 25px;
    }
    h3 {
      font-size: 1.2rem;
      font-weight: 500;
      margin-bottom: 15px;
    }
    .payment-method, .billing-section {
      margin-bottom: 40px;
    }
    .payment-option {
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 15px 20px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #fafafa;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .payment-option:hover {
      border-color: #0070f3;
      background: #f0f7ff;
    }
    .payment-option input {
      margin-right: 10px;
    }
    .logos img {
      height: 28px;
      margin-left: 8px;
      opacity: 0.9;
    }
    .pay-btn {
      width: 100%;
      background: #0070f3;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 16px;
      font-size: 16px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.2s;
    }
    .pay-btn:hover {
      background: #005bd1;
    }
    .summary-item {
      display: flex;
      justify-content: space-between;
      margin: 8px 0;
    }
    .summary-item strong {
      font-weight: 600;
    }
    .book-thumb {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 20px;
    }
    .book-thumb img {
      width: 70px;
      height: 100px;
      border-radius: 6px;
      object-fit: cover;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .book-info small {
      display: block;
      color: #777;
      font-size: 13px;
    }
    .footer-links {
      margin-top: 40px;
      text-align: center;
      font-size: 13px;
      color: #888;
    }
    .footer-links a {
      color: #0070f3;
      text-decoration: none;
      margin: 0 8px;
    }
    hr {
      border: none;
      border-top: 1px solid #eee;
      margin: 15px 0;
    }
  </style>
</head>
<body>

<div class="checkout-wrapper">
  <!-- LEFT SIDE -->
  <div class="checkout-left">
    <h2>Payment</h2>

    <div class="payment-method">
      <label class="payment-option">
        <span>
          <input type="radio" name="payment_method" checked>
          PayFast
        </span>
        <span class="logos">
          <img src="https://www.payfast.co.za/wp-content/uploads/2021/06/payfast-logo.svg" alt="PayFast">
          <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa">
          <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="MasterCard">
          <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/EFTPOS_logo.svg" alt="EFT">
        </span>
      </label>

      <label class="payment-option">
        <span>
          <input type="radio" name="payment_method">
          Ozow
        </span>
        <span class="logos">
          <img src="https://www.ozow.com/wp-content/uploads/2021/11/ozow-logo-green.svg" alt="Ozow">
        </span>
      </label>
    </div>

    <div class="billing-section">
      <h3>Billing address</h3>
      <p><input type="radio" checked> Same as account email (<?= $userEmail ?>)</p>
    </div>

    <form id="payfastForm" action="https://www.payfast.co.za/eng/process" method="post">
      <?php foreach ($data as $name => $value): ?>
        <input type="hidden" name="<?= htmlspecialchars($name) ?>" value="<?= htmlspecialchars($value) ?>">
      <?php endforeach; ?>
      <button type="submit" class="pay-btn">Pay now</button>
    </form>
  </div>

  <!-- RIGHT SIDE -->
  <div class="checkout-right">
    <div class="book-thumb">
      <img src="/cms-data/book-covers/<?= $cover ?>" alt="Book Cover">

      <div class="book-info">
        <strong><?= $title ?></strong>
        <small>by <?= $author ?></small>
        <small>Category: <?= $category ?></small>
        <small>Format: <?= $formatLabel ?></small>
      </div>
    </div>

    <div class="summary-item">
      <span>Subtotal</span>
      <span>R <?= $priceFormatted ?></span>
    </div>
    <div class="summary-item">
      <span>Shipping</span>
      <span>Free</span>
    </div>
    <hr>
    <div class="summary-item">
      <strong>Total</strong>
      <strong>R <?= $priceFormatted ?></strong>
    </div>
  </div>
</div>

<div class="footer-links">
  <a href="/refund-policy">Refund policy</a> •
  <a href="/shipping">Shipping</a> •
  <a href="/privacy-policy">Privacy policy</a> •
  <a href="/terms">Terms of service</a>
</div>

</body>
</html>
