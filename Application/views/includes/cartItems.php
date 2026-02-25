<?php if (empty($cartItems)): ?>

    <div class="alert alert-warning rounded-4 text-center d-block" role="alert">
        <span class="d-block">Your shopping cart is empty.</span>

        <a href="/library" class="btn btn-dark mt-2">Explore Collection</a>
    </div>

<?php else: ?>

    <div class="row">
        <!-- ================= CART ITEMS ================ -->
        <div class="col-lg-8 mb-4">

            <?php foreach ($cartItems as $item): ?>

                <?php
                $bookId     = $item['book_id'];
                $bookType   = $item['item_type'] ?? $item['book_type'] ?? 'regular';
                $title      = htmlspecialchars($item['title']);
                $authors    = htmlspecialchars($item['authors'] ?? '');
                $isbn       = htmlspecialchars($item['isbn'] ?? '');
                $qty        = (int)$item['cart_item_count'];
                $cover      = $item['cover_image'] ?? '';
                $maxQty     = $item['hc_stock_count'] ?? 999;
                $bookPublicKey = $item['book_public_key'] ?? $bookId;
                
                // Build URL based on book type
                if ($bookType === 'academic') {
                    $bookUrl = "/library/academic/{$bookPublicKey}";
                } else {
                    $bookUrl = "/library/book/{$bookPublicKey}";
                }
                
                // Determine cover image path based on book type
                if ($bookType === 'academic') {
                    $coverPath = $cover ? "/cms-data/academic/covers/{$cover}" : "assets/images/no-cover.png";
                } else {
                    $coverPath = $cover ? "/cms-data/book-covers/{$cover}" : "assets/images/no-cover.png";
                }
                
                // Decide price - academic books use ebook_price, regular books use hc_price
                $price = isset($item['hc_price']) ? (float)$item['hc_price'] : 0;
                $priceFormatted = "R" . number_format($price, 2);
                ?>

                <div class="card mb-3 shadow-sm border-0 rounded-4 p-3 cart-item" data-book-type="<?= $bookType ?>">
                    <div class="row g-0">

                        <!-- Cover -->
                        <div class="col-md-2 d-flex justify-content-center mb-3 mb-md-0" style="max-width: 200px;">
                            <a href="<?= $bookUrl ?>" class="text-decoration-none">
                                <img src="<?= $coverPath ?>" class="img-fluid rounded h-100 object-fit-cover mx-auto"
                                    alt="<?= $title ?>">
                            </a>
                        </div>

                        <!-- Details -->
                        <div class="col-md-10">
                            <div class="card-body d-flex flex-column h-100">

                                <h5 class="card-title mb-1">
                                    <a href="<?= $bookUrl ?>" class="text-decoration-none text-dark"><?= $title ?></a>
                                </h5>

                                <p class="text-muted mb-2 small">
                                    <?= $authors ?> • <?= $isbn ?>
                                </p>

                                <p class="fw-semibold fs-5 item-price" data-price="<?= $price ?>">
                                    <?= $priceFormatted ?>
                                </p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">

                                    <div class="d-flex align-items-center gap-2">
                                        <label class="small text-muted mb-0">Qty:</label>
                                        <input type="number"
                                            class="form-control form-control-sm cart-item-qty"
                                            style="width: 70px;"
                                            value="<?= $qty ?>"
                                            min="1"
                                            max="<?= $maxQty ?>"
                                            data-book-id="<?= $bookId ?>"
                                            data-book-type="<?= $bookType ?>">
                                    </div>

                                    <button class="badge text-bg-danger border-danger remove-cart-item"
                                        data-book-id="<?= $bookId ?>"
                                        data-book-type="<?= $bookType ?>">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <!-- ================= SUMMARY ================ -->
        <?php
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cartItems as $item) {
            $qty   = (int)$item['cart_item_count'];
            $price = isset($item['hc_price']) ? (float)$item['hc_price'] : 0;

            $subtotal   += $price * $qty;
            $totalItems += $qty;
        }
        ?>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">

                    <h4 class="mb-3">Summary</h4>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="summary-subtotal">R<?= number_format($subtotal, 2) ?></span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                        <span>
                            Total <small class="text-muted fw-normal total-item-count">(<?= $totalItems ?> items)</small>
                        </span>
                        <span class="summary-total">R<?= number_format($subtotal, 2) ?></span>
                    </div>

                    <a href="cart/checkout" class="btn btn-blue w-100 py-2">Checkout</a>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // ------------------ UPDATE QUANTITY ------------------
            const qtyInputs = document.querySelectorAll(".cart-item-qty");

            qtyInputs.forEach(input => {
                input.addEventListener("change", async function() {
                    const bookId = this.dataset.bookId;
                    const bookType = this.dataset.bookType || 'regular';
                    let qty = parseInt(this.value);

                    if (isNaN(qty) || qty < 1) {
                        this.value = 1;
                        qty = 1;
                    }

                    try {
                        const response = await fetch("/cart/update", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                book_id: bookId,
                                book_type: bookType,
                                qty: qty
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            updateCartSummary(bookId, qty);
                        } else {
                            alert("Failed to update cart item.");
                        }
                    } catch (err) {
                        console.error(err);
                        alert("Error updating cart.");
                    }
                });
            });

            const removeButtons = document.querySelectorAll(".remove-cart-item");

            removeButtons.forEach(btn => {
                btn.addEventListener("click", async function() {
                    const bookId = this.dataset.bookId;
                    const bookType = this.dataset.bookType || 'regular';

                    console.log(bookId, bookType);
                    if (!confirm("Remove this item from your cart?")) return;

                    try {
                        const response = await fetch("/cart/remove", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                book_id: bookId,
                                book_type: bookType
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            const card = this.closest(".cart-item");
                            if (card) card.remove();
                            updateCartSummary(bookId, 0, true);
                        } else {
                            alert("Failed to remove item.");
                        }
                    } catch (err) {
                        console.error(err);
                        alert("Error removing cart item.");
                    }
                });
            });

            function updateCartSummary(bookId, newQty, removed = false) {
                const card = document.querySelector(`.cart-item input[data-book-id="${bookId}"]`)?.closest(".cart-item");
                if (!card) return;

                const priceEl = card.querySelector(".item-price");
                if (!priceEl) return;

                const price = parseFloat(priceEl.dataset.price);
                if (isNaN(price)) return;

                let subtotal = 0;
                let totalItems = 0;

                document.querySelectorAll(".cart-item").forEach(item => {
                    const qtyInput = item.querySelector(".cart-item-qty");
                    const itemPriceEl = item.querySelector(".item-price");
                    if (!qtyInput || !itemPriceEl) return;

                    const qty = parseInt(qtyInput.value);
                    const price = parseFloat(itemPriceEl.dataset.price);
                    subtotal += qty * price;
                    totalItems += qty;
                });

                document.querySelector(".summary-subtotal").textContent = "R" + subtotal.toFixed(2);
                document.querySelector(".summary-total").textContent = "R" + subtotal.toFixed(2);
                document.querySelector(".total-item-count").textContent = `(${totalItems} items)`;

                if (removed && totalItems === 0) {
                    const container = document.querySelector(".row");
                    if (container) container.innerHTML = `
                <div class="alert alert-warning rounded-5 text-center d-block" role="alert">
                    <span>Your shopping cart is empty.</span>
                    <a href="/library" class="btn btn-dark mt-2">Explore Collection</a>
                </div>
            `;
                }
            }

        });
    </script>

<?php endif; ?>