<!-- 💳 Secure Payment Methods Footer -->
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
                // Path to your PayFast icons
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

<style>
/* Payment Methods Footer Styles */
.payment-methods-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 50px 20px;
    border-top: 2px solid #e9ecef;
    margin-top: 60px;
}

.payment-container {
    max-width: 1200px;
    margin: 0 auto;
}

.payment-header {
    text-align: center;
    margin-bottom: 40px;
}

.payment-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
}

.payment-title i {
    color: #28a745;
    font-size: 1.5rem;
}

.payment-subtitle {
    font-size: 0.95rem;
    color: #6c757d;
    line-height: 1.6;
    max-width: 700px;
    margin: 0 auto;
    font-weight: 400;
}

/* Payment carousel styles - EXACT ORIGINAL WORKING CODE */
.payment-carousel {
    overflow: hidden;
    position: relative;
    width: 100%;
    margin: 30px 0;
}

.payment-track {
    display: flex;
    gap: 60px;
    animation: scroll-left 25s linear infinite;
    padding: 32px;
}

.payment-logo {
    flex-shrink: 0;
}

.payment-logo img {
    height: 50px;
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

/* Auto-scroll keyframes - EXACT ORIGINAL WORKING CODE */
@keyframes scroll-left {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-50%);
    }
}

/* Security Badge */
.payment-security-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 1px solid #e9ecef;
    font-size: 0.875rem;
    color: #495057;
    font-weight: 500;
}

.payment-security-badge i {
    color: #28a745;
    font-size: 1rem;
}

.payment-security-badge .separator {
    color: #dee2e6;
    margin: 0 5px;
}

/* Responsive */
@media (max-width: 768px) {
    .payment-methods-footer {
        padding: 35px 15px;
    }

    .payment-title {
        font-size: 1.4rem;
    }

    .payment-subtitle {
        font-size: 0.875rem;
        padding: 0 10px;
    }

    .payment-logo img {
        height: 30px;
    }

    .payment-security-badge {
        flex-direction: column;
        gap: 10px;
        font-size: 0.8rem;
    }

    .payment-security-badge .separator {
        display: none;
    }
}

@media (max-width: 480px) {
    .payment-title {
        font-size: 1.2rem;
    }
}
</style>
