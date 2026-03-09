<footer class="payment-methods-footer">
    <div class="payment-container">
        <div class="payment-header">
            <h3 class="payment-title">
                <i class="fas fa-shield-alt me-2"></i>
                Secure & Trusted Payment Methods
            </h3>
            <p class="payment-subtitle">
                We accept a wide range of secure payment options to ensure your transactions are safe and convenient.
                All payments are processed through industry-leading secure payment gateways with SSL encryption.
            </p>
        </div>

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

        <div class="payment-security-badge">
            <i class="fas fa-lock me-2"></i>
            <span>256-bit SSL Encrypted Transactions</span>
            <span class="separator">•</span>
            <i class="fas fa-check-circle me-2"></i>
            <span>PCI DSS Compliant</span>
            <span class="separator">•</span>
            <i class="fas fa-user-shield me-2"></i>
            <span>Your Data is Protected</span>
        </div>
    </div>
</footer>