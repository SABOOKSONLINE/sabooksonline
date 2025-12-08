<?php
require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../../models/CartModel.php";
require_once __DIR__ . "/../../controllers/CartController.php";

$cartModel = new CartModel($conn);
$userId = $_SESSION['ADMIN_ID'];
$address = $cartModel->getDeliveryAddress($userId);  // <-- Load saved address
?>


<div class="container">

    <div class="row align-items-center justify-content-between text-start mb-3">
        <div class="col-md-8 col-12 mb-2 mb-md-0 text-start">
            <h1 class="typo-heading">Checkout</h1>
        </div>
        <div class="col-md-auto col-12 text-md-end">
            <a href="/cart" class="badge text-bg-secondary text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i> Back to Cart <span class=""><i class="fas fa-cart-shopping"></i></span>
            </a>
        </div>
    </div>

    <div class="row">

        <!-- LEFT COLUMN — DELIVERY & PAYMENT -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-3">Delivery Details</h4>
                    <div class="row g-3">
                        <!-- Delivery inputs -->
                        <div class="col-md-6">
                            <label class="form-label">Company (Optional)</label>
                            <input type="text" id="company" class="form-control"
                                value="<?= $address['company'] ?? '' ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="full_name" class="form-control" required
                                value="<?= $address['full_name'] ?? '' ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" id="mobile_number" class="form-control" required
                                value="<?= $address['phone'] ?? '' ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" class="form-control" required
                                value="<?= $address['email'] ?? ($_SESSION['ADMIN_EMAIL'] ?? '') ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Street Address <span class="text-danger">*</span></label>
                            <input type="text" id="street_address" class="form-control" required
                                value="<?= $address['street_address'] ?? '' ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Delivery Type <span class="text-danger">*</span></label>
                            <select id="delivery_type" class="form-select" required>
                                <option value="business" <?= (isset($address['delivery_type']) && $address['delivery_type'] === 'business') ? 'selected' : '' ?>>
                                    Business
                                </option>
                                <option value="residential" <?= (isset($address['delivery_type']) && $address['delivery_type'] === 'residential') ? 'selected' : '' ?>>
                                    Residential
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Suburb / Local Area <span class="text-danger">*</span></label>
                            <input type="text" id="local_area" class="form-control" required
                                value="<?= $address['local_area'] ?? '' ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Province / Zone <span class="text-danger">*</span></label>
                            <select id="zone" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php
                                $zones = [
                                    "Gauteng",
                                    "KwaZulu-Natal",
                                    "Western Cape",
                                    "Eastern Cape",
                                    "Free State",
                                    "Limpopo",
                                    "Mpumalanga",
                                    "Northern Cape",
                                    "North West"
                                ];
                                $selectedZone = $address['zone'] ?? '';
                                foreach ($zones as $zone):
                                ?>
                                    <option value="<?= $zone ?>" <?= $zone === $selectedZone ? 'selected' : '' ?>>
                                        <?= $zone ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Postal / ZIP Code <span class="text-danger">*</span></label>
                            <input type="text" id="code" class="form-control" required
                                value="<?= $address['postal_code'] ?? '' ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" id="country" class="form-control text-muted bg-light"
                                value="<?= $address['country'] ?? 'ZA' ?>" readonly>
                        </div>
                    </div>

                    <!-- SAVE DELIVERY BUTTON -->
                    <button type="button" id="saveDeliveryBtn" class="btn btn-green py-2 mt-4">
                        Save Delivery Address
                    </button>

                    <hr class="my-4">

                    <h4 class="mb-3">Payment Method</h4>
                    <label class="w-100 border rounded-3 p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="payment_method" value="payfast" checked>
                                <strong>Payfast</strong>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa" style="height: 20px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="Mastercard" style="height: 20px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/EFTPOS_logo.svg" alt="EFT" style="height: 20px;">
                            </div>
                        </div>
                    </label>
                </div>

            </div>
        </div>


        <!-- RIGHT COLUMN — ORDER SUMMARY -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h4 class="mb-3">Order Summary</h4>
                    <div id="checkout-summary-items" class="mb-3">
                        <?php
                        $subtotal = 0;
                        $totalItems = 0;
                        foreach ($cartItems as $item):
                            $title = htmlspecialchars($item['title']);
                            $qty = (int)$item['cart_item_count'];
                            $price = isset($item['hc_price']) ? (float)$item['hc_price'] : 0;
                            $lineTotal = $qty * $price;
                            $subtotal += $lineTotal;
                            $totalItems += $qty;
                        ?>
                            <div class="d-flex justify-content-between align-items-start mb-2 pb-2">
                                <div class="me-2">
                                    <strong><?= $title ?></strong><br>
                                    <small class="text-muted">Qty: <?= $qty ?></small>
                                </div>
                                <div>
                                    <span>R<?= number_format($lineTotal, 2) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 border-top pt-2">
                        <strong>Total (<?= $totalItems ?> items)</strong>
                        <strong>R<?= number_format($subtotal, 2) ?></strong>
                    </div>

                    <!-- CONFIRM & PAY BUTTON -->
                    <button type="button" id="confirmCheckoutBtn" class="btn btn-primary w-100 py-2" disabled>
                        Confirm & Pay
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const saveBtn = document.getElementById("saveDeliveryBtn");
        const payBtn = document.getElementById("confirmCheckoutBtn");

        const deliveryData = {
            company: document.getElementById('company').value.trim(),
            full_name: document.getElementById('full_name').value.trim(),
            phone: document.getElementById('mobile_number').value.trim(),
            email: document.getElementById('email').value.trim(),
            street_address: document.getElementById('street_address').value.trim(),
            delivery_type: document.getElementById('delivery_type').value,
            local_area: document.getElementById('local_area').value.trim(),
            zone: document.getElementById('zone').value,
            postal_code: document.getElementById('code').value.trim(),
            country: document.getElementById('country').value.trim()
        };

        const requiredFields = ['full_name', 'phone', 'email', 'street_address', 'delivery_type', 'local_area', 'zone', 'postal_code', 'country'];
        const allFilled = requiredFields.every(key => deliveryData[key] !== '');

        payBtn.disabled = !allFilled;

        saveBtn.addEventListener("click", async () => {
            try {
                const response = await fetch("/cart-checkout/address", { // <-- updated route
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(deliveryData)
                });
                const data = await response.json();
                if (data.success) {
                    payBtn.disabled = false;
                } else {
                    alert("Failed to save delivery address: " + (data.error || "Unknown error"));
                }
            } catch (err) {
                console.error(err);
                alert("An error occurred while saving delivery address.");
            }
        });

        payBtn.addEventListener("click", async () => {
            alert("Checkout button clicked — proceed to payment.");
        });
    });
</script>