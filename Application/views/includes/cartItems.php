<?php if (empty($cartItems)): ?>

    <div class="alert alert-warning rounded-5 text-center d-block" role="alert">
        Your shopping cart is empty.
        <a href="/library" class="btn btn-dark mt-2">Explore Collection</a>
    </div>

<?php else: ?>

    <div class="row">
        <!-- ================= CART ITEMS ================ -->
        <div class="col-lg-8 mb-4">

            <?php foreach ($cartItems as $item): ?>

                <?php
                $bookId     = $item['book_id'];
                $title      = htmlspecialchars($item['title']);
                $authors    = htmlspecialchars($item['authors']);
                $isbn       = htmlspecialchars($item['isbn']);
                $qty        = (int)$item['cart_item_count'];
                $cover      = $item['cover_image'] ?: "assets/images/no-cover.png";

                // decide price
                $price      = isset($item['price']) ? (float)$item['price'] : 0;
                $priceFormatted = "R" . number_format($price, 2);
                ?>

                <div class="card mb-3 shadow-sm border-0 rounded-4 p-3 cart-item">
                    <div class="row g-0">

                        <!-- Cover -->
                        <div class="col-md-2 d-flex justify-content-center mb-3 mb-md-0" style="max-width: 200px;">
                            <img src="/cms-data/book-covers/<?= $cover ?>" class="img-fluid rounded h-100 object-fit-cover mx-auto"
                                alt="<?= $title ?>">
                        </div>

                        <!-- Details -->
                        <div class="col-md-10">
                            <div class="card-body d-flex flex-column h-100">

                                <h5 class="card-title mb-1"><?= $title ?></h5>

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
                                            data-book-id="<?= $bookId ?>">
                                    </div>

                                    <button class="badge text-bg-danger border-danger remove-cart-item"
                                        data-book-id="<?= $bookId ?>">
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
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">

                    <h4 class="mb-3">Summary</h4>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="summary-subtotal">R0.00</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                        <span>
                            Total <small class="text-muted fw-normal total-item-count">(0 items)</small>
                        </span>
                        <span class="summary-total">R0.00</span>
                    </div>

                    <button class="btn btn-primary w-100 py-2">Checkout</button>

                </div>
            </div>
        </div>

    </div>

<?php endif; ?>