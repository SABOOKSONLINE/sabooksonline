<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="jumbotron jumbotron-md">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4">Become a Member Today!</h1>
                <p class="lead mb-4">Get direct market access to generate more sales.</p>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-red me-2" href="#membership-pricing" role="button">
                        Create Membership Account
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="section" id="membership-pricing">
        <div class="container text-center">
            <h1 class="fw-bold mb-5">Our Pricing Plans</h1>

            <!-- Billing Toggle -->
            <div class="d-flex justify-content-center align-items-center mb-5">
                <span class="me-3 fw-bold">Monthly</span>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="billingToggle" style="width: 3em; height: 1.5em;">
                    <label class="form-check-label" for="billingToggle"></label>
                </div>
                <span class="ms-3 fw-bold">Yearly <span class="badge bg-success">Save 20%</span></span>
            </div>

            <div class="row priced-plans py-5">
                <!-- Standard Plan -->
                <div class="col-md-4">
                    <div class="plan-title">
                        <h3>Standard Plan</h3>
                    </div>
                    <div class="monthly-price">
                        <p class="plan-price">R150</p>
                        <p><b>Monthly Subscription</b></p>
                    </div>
                    <div class="yearly-price d-none">
                        <p class="plan-price">R1440</p>
                        <p><b>Yearly Subscription (Save R360)</b></p>
                    </div>

                    <a href="register/?plan=Standard" class="btn btn-red mb-4">Try free 30 day trial</a>

                    <ul class="plan-features list-unstyled">
                        <li>Unlimited Free Book Listings</li>
                        <li><strong>10 Priced</strong> Books</li>
                        <li><strong>3 Service</strong> Listing</li>
                        <li><strong>AD 10 events</strong> per month</li>
                        <li><strong>1 Email</strong> addresses</li>
                        <li>Website With payment gateway</li>
                        <li>Analytics</li>
                        <li>Analytics</li>
                    </ul>
                </div>

                <!-- Premium Plan -->
                <div class="col-md-4">
                    <div class="plan-title">
                        <h3>Premium Plan</h3>
                    </div>
                    <div class="monthly-price">
                        <p class="plan-price">R350</p>
                        <p><b>Monthly Subscription</b></p>
                    </div>
                    <div class="yearly-price d-none">
                        <p class="plan-price">R3360</p>
                        <p><b>Yearly Subscription (Save R840)</b></p>
                    </div>

                    <a href="register/?plan=Premium" class="btn btn-red mb-4">Try free 30 day trial</a>

                    <ul class="plan-features list-unstyled">
                        <li>Unlimited Free Book Listings</li>
                        <li><strong>10 Priced</strong> Books</li>
                        <li><strong>3 Service</strong> Listing</li>
                        <li><strong>AD 10 events</strong> per month</li>
                        <li><strong>1 Email</strong> addresses</li>
                        <li>Website With payment gateway</li>
                        <li>Analytics</li>
                    </ul>
                </div>

                <!-- Deluxe Plan -->
                <div class="col-md-4">
                    <div class="plan-title">
                        <h3>Deluxe Plan</h3>
                    </div>
                    <div class="monthly-price">
                        <p class="plan-price">R500</p>
                        <p><b>Monthly Subscription</b></p>
                    </div>
                    <div class="yearly-price d-none">
                        <p class="plan-price">R4800</p>
                        <p><b>Yearly Subscription (Save R1200)</b></p>
                    </div>

                    <a href="register/?plan=Deluxe" class="btn btn-red mb-4">Try free 30 day trial</a>

                    <ul class="plan-features list-unstyled">
                        <li>Unlimited Free Book Listings</li>
                        <li><strong>10 Priced</strong> Books</li>
                        <li><strong>3 Service</strong> Listing</li>
                        <li><strong>AD 10 events</strong> per month</li>
                        <li><strong>1 Email</strong> addresses</li>
                        <li>Website With payment gateway</li>
                        <li>Analytics</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>