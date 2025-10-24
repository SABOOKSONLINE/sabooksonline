<?php

$title = "sell";
ob_start();

include __DIR__ . "/layout/renderHero.php";
include __DIR__ . "/layout/sectionHeading.php";


echo renderHero(
    "Start selling with SABO",
    "Join South Africa’s growing online bookstore marketplace. Whether you’re an author, publisher, or bookstore owner, our platform makes it easy to list, manage, and sell your books nationwide.",
    "Start Selling",
    "#membership-pricing"
);
?>

<section class="section">
    <div class="container">
        <?php
        renderSectionHeading(
            "Why Sell With Us",
            "Reach readers, grow sales, and manage everything in one place.",
            "",
            "",
            "center"
        );
        ?>

        <div class="row g-4 justify-content-center align-items-stretch">
            <div class="col-md-3 d-flex">
                <div class="card flex-fill p-3 text-center d-flex flex-column justify-content-center align-items-center rounded-5 bs-card">
                    <div class="card-body">
                        <h3 class="card-title border-bottom pb-4 mb-4 fw-bold">Expand Your Reach</h3>
                        <h5 class="card-text text-muted">
                            Reach thousands of readers every month and get visibility through promotions and featured listings.
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 d-flex">
                <div class="card flex-fill p-3 text-center d-flex flex-column justify-content-center align-items-center rounded-5 bs-card">
                    <div class="card-body">
                        <h3 class="card-title border-bottom pb-4 mb-4 fw-bold">Simplify Your Workflow</h3>
                        <h5 class="card-text text-muted">
                            Manage your books, orders, and payments all in one easy-to-use platform.
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3 d-flex">
                <div class="card flex-fill p-3 text-center d-flex flex-column justify-content-center align-items-center rounded-5 bs-card">
                    <div class="card-body">
                        <h3 class="card-title border-bottom pb-4 mb-4 fw-bold">Earn More & Grow</h3>
                        <h5 class="card-text text-muted">
                            Earn fair commissions and take advantage of tools designed for bookstores and publishers alike.
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container py-4">
        <div class="p-5 mb-4 bg-body-tertiary rounded-5 border shadow-md">
            <?php
            renderSectionHeading(
                "How It Works",
                "Follow these simple steps to start selling your books online.",
                "",
                "",
            );
            ?>

            <div class="row g-4 mt-3">
                <!-- Step 1 -->
                <div class="col-md-3">
                    <div class="step d-flex flex-column justify-content-center align-items-center h-100 p-4 bg-black text-light rounded-5 shadow-sm text-center">
                        <div class="step-icon mb-3">
                            <i class="fas fa-user-plus" style="font-size: 2rem;"></i>
                        </div>
                        <h5>1. Register</h5>
                        <p>Create your seller account and set up your profile.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-3">
                    <div class="step d-flex flex-column justify-content-center align-items-center h-100 p-4 bg-black text-light rounded-5 shadow-sm text-center">
                        <div class="step-icon mb-3">
                            <i class="fas fa-book-open" style="font-size: 2rem;"></i>
                        </div>
                        <h5>2. List Your Books</h5>
                        <p>Upload your book titles, cover images, and pricing details.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-3">
                    <div class="step d-flex flex-column justify-content-center align-items-center h-100 p-4 bg-black text-light rounded-5 shadow-sm text-center">
                        <div class="step-icon mb-3">
                            <i class="fas fa-shopping-cart" style="font-size: 2rem;"></i>
                        </div>
                        <h5>3. Start Selling</h5>
                        <p>Your books go live on the marketplace and are ready for readers to buy.</p>
                    </div>
                </div>

                <!-- Step 4 (Updated) -->
                <div class="col-md-3">
                    <div class="step d-flex flex-column justify-content-center align-items-center h-100 p-4 bg-black text-light rounded-5 shadow-sm text-center">
                        <div class="step-icon mb-3">
                            <i class="fas fa-wallet" style="font-size: 2rem;"></i>
                        </div>
                        <h5>4. Bi-Annual Payouts</h5>
                        <p>Participate in our bi-annual rotary payout declaration to receive your earnings every six months.</p>
                    </div>
                </div>
            </div>




            <div class="mt-5">
                <a href="#membership-pricing" class="btn btn-black btn-lg rounded-pill">
                    Start Selling Today <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . "/includes/newsLetter.php" ?>

<section class="section" id="membership-pricing">
    <div class="container text-center">
        <?php
        renderSectionHeading(
            "Our Pricing Plans",
            "Choose the plan that fits your needs and start selling your books today.",
            "",
            "",
            "center"
        );
        ?>

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

<?php
$content = ob_get_clean();
include __DIR__ . '/layout/base.php';
