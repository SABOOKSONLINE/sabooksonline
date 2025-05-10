<!-- views/payment/purchaseForm.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Purchase Book - <?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    .book-card {
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 20px;
      background-color: #fff;
    }
    .secure-badge {
      display: flex;
      align-items: center;
      color: green;
      font-weight: 600;
    }
    .secure-badge img {
      margin-right: 8px;
      height: 24px;
    }
  </style>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="book-card">

        <div class="text-center mb-4">
          <img src="<?= htmlspecialchars($cover) ?>" alt="Book Cover" class="img-fluid" style="max-height: 300px;">
        </div>

        <h3><?= htmlspecialchars($title) ?></h3>
        <p><strong>Publisher:</strong> <?= htmlspecialchars($publisher) ?></p>
        <p><?= nl2br(htmlspecialchars($description)) ?></p>

        <hr>

        <div class="mb-3">
          <h5>Your Details</h5>
          <div class="d-flex align-items-center">
            <img src="<?= htmlspecialchars($profile) ?>" alt="Profile Image" width="50" height="50" class="rounded-circle me-2">
            <div>
              <strong><?= htmlspecialchars($userName) ?></strong><br>
              <small><?= htmlspecialchars($userEmail) ?></small>
            </div>
          </div>
        </div>

        <hr>

        <div class="mb-4">
          <h5>Total Price</h5>
          <p style="font-size: 1.3rem; color: #0d6efd;"><strong>R<?= number_format($retailPrice, 2) ?></strong></p>
        </div>

        <div class="mb-4 secure-badge">
          <img src="https://img.icons8.com/color/48/000000/lock--v1.png" alt="Secure">
          <span>Secure payment powered by PayFast</span>
        </div>

        <?= $paymentForm ?>

        <div class="text-center mt-3">
          <img src="https://my.sabooksonline.co.za/img/Payfast By Network_dark.svg" width="180" alt="PayFast">
        </div>

      </div>

    </div>
  </div>
</div>

</body>
</html>
