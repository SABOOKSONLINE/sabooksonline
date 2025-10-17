<!-- 💳 Payment Methods Footer -->
<footer style="background:#f9f9f9; padding:30px 0; border-top:1px solid #eee;">
    <div style="max-width:1200px; margin:0 auto; text-align:center;">
        <h3 style="font-weight:800; font-size:30px; margin-bottom:55px; color:#333;">
            We Offer Secure Payment Methods
        </h3>

        <div class="payment-carousel">
            <div class="payment-track">
                <?php
                // Path to your PayFast icons
                $paymentIcons = glob(__DIR__ . "/../../../cms-data/payfast/*.{png,jpg,jpeg,svg}", GLOB_BRACE);
                foreach ($paymentIcons as $iconPath):
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
</footer>

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
