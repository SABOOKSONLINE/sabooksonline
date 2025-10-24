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
                </div>
                <span class="ms-3 fw-bold">Yearly <span class="badge bg-success">Save 20%</span></span>
            </div>

            <!-- Plans -->
            <div class="row priced-plans py-5 align-content-center justify-content-center">
                <!-- PRO PLAN -->
                <div class="col-md-4">
                    <form method="POST" action="/checkout" class="subscription-form">
                        <div class="plan-title w-75 mx-auto">
                            <h3>Pro</h3>
                            <p class="small">Ideal for publishers and authors trading within Africa.</p>
                        </div>

                        <div class="monthly-price">
                            <p class="plan-price">R199</p>
                            <p><b>Monthly Subscription</b></p>
                        </div>
                        <div class="yearly-price d-none">
                            <p class="plan-price">R1911</p>
                            <p><b>Yearly Subscription (Save R480)</b></p>
                        </div>

                        <!-- Additional Content -->
                        <ul class="list-unstyled w-75 mx-auto">
                            <li class="mb-3 d-flex align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold">African Territory Trade Levy</h6>
                                    <p class="mb-0 small">
                                        Seamless sales commission structure applicable to all transactions within African territories.
                                    </p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold">African Continent Analytics</h6>
                                    <p class="mb-0 small">
                                        Access to performance insights and sales data across African markets to track reach and optimise engagement.
                                    </p>
                                </div>
                            </li>
                        </ul>

                        <!-- Payment Option -->
                        <div class="mb-3">
                            <label class="form-label">Payment Option:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentOption" value="upfront" checked>
                                <label class="form-check-label">Pay Now</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentOption" value="later">
                                <label class="form-check-label">Pay Later</label>
                            </div>
                        </div>

                        <!-- Clean values -->
                        <input type="hidden" name="planType" class="plan-type-input" value="Pro-Monthly">

                        <button type="submit" class="btn btn-red mb-4">Select Pro</button>
                    </form>
                </div>

                <!-- PREMIUM PLAN -->
                <div class="col-md-4">
                    <form method="POST" action="/checkout" class="subscription-form">
                        <div class="plan-title w-75 mx-auto">
                            <h3>Premium</h3>
                            <p class="small">Best suited for publishers and authors with international aspirations.</p>
                        </div>

                        <div class="monthly-price">
                            <p class="plan-price">R499</p>
                            <p><b>Monthly Subscription</b></p>
                        </div>
                        <div class="yearly-price d-none">
                            <p class="plan-price">R4791</p>
                            <p><b>Yearly Subscription (Save R1200)</b></p>
                        </div>

                        <!-- Additional Content -->
                        <ul class="list-unstyled w-75 mx-auto">
                            <li class="mb-3 d-flex align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold">Global Territory Trade Levy</h6>
                                    <p class="mb-0 small">
                                        Inclusive commission framework covering international distribution and royalties management.
                                    </p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold">Global Analytics Dashboard</h6>
                                    <p class="mb-0 small">
                                        Comprehensive reporting tools offering global sales trends, market segmentation, and audience engagement insights.
                                    </p>
                                </div>
                            </li>
                        </ul>

                        <!-- Payment Option -->
                        <div class="mb-3">
                            <label class="form-label">Payment Option:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentOption" value="upfront" checked>
                                <label class="form-check-label">Pay Now</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentOption" value="later">
                                <label class="form-check-label">Pay Later</label>
                            </div>
                        </div>

                        <!-- Clean values -->
                        <input type="hidden" name="planType" class="plan-type-input" value="Premium-Monthly">

                        <button type="submit" class="btn btn-red mb-4">Select Premium</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php require_once __DIR__ . "/includes/payfast.php" ?>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>