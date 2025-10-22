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
require_once __DIR__ . "/../includes/header.php";
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

    .checkout-header h1 {
      margin: 0;
      font-size: 2rem;
      font-weight: 600;
    }
    .checkout-header p {
      margin-top: 0px;
      font-size: 1rem;
      opacity: 0.95;
    }

    .checkout-header {
  color: black;
  padding: 20px 15px 10px;
  text-align: center;
  margin-top: 0px; /* Adjust to fit under navv header */
}

.checkout-wrapper {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
  padding: 20px 15px 80px;
  max-width: 1100px;
  margin: 0 auto;
}


    .checkout-left {
      flex: 1 1 580px;
      background: #fff;
      padding: 40px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.05);
    }

    .checkout-right {
      flex: 0 0 320px;
      /*background: #fff;*/
      padding: 30px;
      /*box-shadow: 0 3px 12px rgba(0,0,0,0.05);*/
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

    h4 {
      font-size: 1rem;
      font-weight: 400;
      color: #666;
    }

    .payment-card {
      display: block;
      border: 1.5px solid #ddd;
      border-radius: 10px;
      margin-bottom: 15px;
      background: #fafafa;
      transition: all 0.25s ease;
      cursor: pointer;
    }

    .payment-card:hover {
      border-color: #0070f3;
    }

    .payment-card.selected {
      border-color: #0070f3;
      background: #f5faff;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      border-bottom: 1px solid #eee;
    }

    .card-radio {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 15px;
    }

    .card-logos img {
      height: 22px;
      margin-left: 6px;
    }

    .plus {
      background: #0070f3;
      color: #fff;
      border-radius: 5px;
      font-size: 11px;
      padding: 2px 5px;
      margin-left: 6px;
    }

    .card-body {
      padding: 20px;
      text-align: center;
    }

    .browser-icon {
      width: 60px;
      opacity: 0.6;
      display: block;
      margin: 0 auto 10px auto;
    }

    .billing-section {
      margin-top: 40px;
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

    .book-thumb {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 20px;
    }

    .book-thumb img {
      width: 100%;
      max-width: 250px;
      height: auto;
      /*border-radius: 8px;*/
      object-fit: cover;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .book-info strong {
      font-size: 1.1rem;
      display: block;
      margin-bottom: 4px;
    }

    .book-info small {
      color: #777;
      font-size: 0.95rem;
    }

    hr {
      border: none;
      border-top: 1px solid #eee;
      margin: 15px 0;
    }

    .footer-links {
      margin: 60px 0 20px;
      text-align: center;
      font-size: 13px;
      color: #888;
    }

    .footer-links a {
      color: #0070f3;
      text-decoration: none;
      margin: 0 8px;
    }
     .legal-links {
      text-align: center;
      font-size: 13px;
      margin-top: 40px;
      color: #888;
    }
    .legal-links a {
      color: #0070f3;
      text-decoration: none;
      margin: 0 6px;
    }
  </style>
</head>

<body>
  <?php require_once __DIR__ . "/../includes/navv.php"; ?>


<div class="checkout-header">
    <h1>Checkout</h1>
    <p>You’re almost there — complete your purchase securely.</p>
      <?php
    include __DIR__ . "/../includes/payements.php";
  ?>
  </div>
  <div class="checkout-wrapper">
    <!-- LEFT SIDE -->
    <div class="checkout-left">
      
      <h2>Payment</h2>
      <h4>All transactions are secure and encrypted.</h4>

      <label class="payment-card selected">
        <div class="card-header">
          <div class="card-radio">
            <input type="radio" name="payment_method" value="payfast" checked>
            <strong>Payfast</strong>
          </div>
          <div class="card-logos">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/EFTPOS_logo.svg" alt="EFT">
            <span class="plus">+2</span>
          </div>
        </div>
        <div class="card-body">
          <img src="https://cdn-icons-png.flaticon.com/512/64/64113.png" alt="Browser" class="browser-icon">
          <p>After clicking <strong>“Pay now”</strong>, you will be redirected to Payfast to complete your purchase securely.</p>
        </div>
      </label>

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
       <div class="legal-links">
      <a href="https://sabooksonline.co.za/public/documents/terms">Terms & Conditions</a> |
      <a href="https://sabooksonline.co.za/public/documents/ReturnPolicy.pdf">Refunds</a> |
      <a href="https://sabooksonline.co.za/public/documents/Delivery.pdf ">Delivery</a>
    </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="checkout-right">
      <div class="book-thumb">
        <img src="/cms-data/book-covers/<?= $cover ?>" alt="Book Cover">
      </div>
      <div class="book-info">
        <strong><?= $title ?></strong>
        <small><?= $author ?></small>
      </div>

      <div class="summary-item">
        <span>Format</span>
        <span><?= $formatLabel ?></span>
      </div>
      <div class="summary-item">
        <span>Subtotal</span>
        <span>R <?= $priceFormatted ?></span>
      </div>

      <hr>
      <div class="summary-item">
        <strong>Total</strong>
        <strong>R <?= $priceFormatted ?></strong>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.payment-card').forEach(card => {
      card.addEventListener('click', () => {
        document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        card.querySelector('input[type="radio"]').checked = true;
      });
    });
  </script>

  <?php
    include __DIR__ . "/../includes/footer.php";
    include __DIR__ .  "/../includes/scripts.php";
  ?>
</body>
</html>
