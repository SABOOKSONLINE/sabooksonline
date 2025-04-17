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
                    <a class="btn btn-red me-2" href="#" role="button">
                        Create Membership Account
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container text-center">
            <h1 class="fw-bold mb-5">Our Pricing Plans</h1>

            <div class="row priced-plans py-5">
                <div class="col-md-4">
                    <div class="plan-title">
                        <h3>Standard Plan</h3>
                    </div>
                    <p class="plan-price">R150</p>

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
                    <b>**Monthly Subscription**</b>
                </div>

                <div class="col-md-4">
                    <div class="plan-title">
                        <h3>Premium Plan</h3>
                    </div>
                    <p class="plan-price">R350</p>

                    <a href="register/?plan=Standard" class="btn btn-red mb-4">Try free 30 day trial</a>

                    <ul class="plan-features list-unstyled">
                        <li>Unlimited Free Book Listings</li>
                        <li><strong>10 Priced</strong> Books</li>
                        <li><strong>3 Service</strong> Listing</li>
                        <li><strong>AD 10 events</strong> per month</li>
                        <li><strong>1 Email</strong> addresses</li>
                        <li>Website With payment gateway</li>
                        <li>Analytics</li>
                    </ul>
                    <b>**Monthly Subscription**</b>
                </div>

                <div class="col-md-4">
                    <div class="plan-title">
                        <h3>Deluxe Plan</h3>
                    </div>
                    <p class="plan-price">R500</p>

                    <a href="register/?plan=Standard" class="btn btn-red mb-4">Try free 30 day trial</a>

                    <ul class="plan-features list-unstyled">
                        <li>Unlimited Free Book Listings</li>
                        <li><strong>10 Priced</strong> Books</li>
                        <li><strong>3 Service</strong> Listing</li>
                        <li><strong>AD 10 events</strong> per month</li>
                        <li><strong>1 Email</strong> addresses</li>
                        <li>Website With payment gateway</li>
                        <li>Analytics</li>
                    </ul>
                    <b>**Monthly Subscription**</b>
                </div>
            </div>
        </div>

    </section>
    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>