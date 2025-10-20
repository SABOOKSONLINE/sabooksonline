<!-- 💳 Payment Methods Footer -->
<section class="section">
  <div class="container">
    <div class="text-center">
      <?php renderSectionHeading("We Offer Secure Payment Methods", "", "", "", "center") ?>
    </div>
  </div>

  <div class="container">

    <div class="payment-carousel">
      <div class="payment-track">
        <?php
        // Load payment icons
        $paymentIcons = glob(__DIR__ . "/../../../cms-data/payfast/*.{png,jpg,jpeg,svg}", GLOB_BRACE);
        foreach (array_merge($paymentIcons, $paymentIcons) as $iconPath): // Duplicate for smooth loop
            $iconName = basename($iconPath);
            $iconUrl = "/cms-data/payfast/" . $iconName;
        ?>
          <div class="payment-logo">
            <img src="<?= $iconUrl ?>" alt="<?= pathinfo($iconName, PATHINFO_FILENAME) ?>" />
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<style>
  /* Payment carousel styles */
  .payment-carousel {
    overflow: hidden;
    position: relative;
    width: 100%;
  }

  .payment-track {
    display: flex;
    gap: 60px;
    animation: scroll-left 25s linear infinite;
    padding: 32px;
  }

  .payment-logo img {
    height: 70px;
    object-fit: contain;
    filter: grayscale(0.2);
    opacity: 0.9;
    transition: 0.3s ease;
  }

  .payment-logo img:hover {
    filter: grayscale(0);
    opacity: 1;
    transform: scale(1.05);
        filter: grayscale(75%);
  }

  /* Auto-scroll keyframes */
  @keyframes scroll-left {
    from {
      transform: translateX(0);
    }

    to {
      transform: translateX(-50%);
    }
  }

  /* Responsive */
  @media (max-width: 768px) {
    .payment-logo img {
      height: 30px;
    }
  }
</style>