<?php if ($reviews): ?>
    <section class="section">
        <div class="container">
            <?php renderSectionHeading("Reviews", "Every Book Has a Story, So Does Every Reader.", "", "") ?>

            <div class="row">
                <div class="col-lg-8">
                    <?php foreach ($reviews as $review): ?>
                        <div class="bv-review">
                            <div class="bv-review-profile">
                                <img src="<?= $review['user_img_url'] ?>" alt="<?= $review['user_img_url'] ?>" width="40px">
                                <p class="bv-text-para"><b><?= $review['name'] ?></b></p>
                            </div>
                            <div class="bv-review-details">
                                <div class="bv-review-rating">
                                    <div class="bv-review-stars">
                                        <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="bv-text-para">
                                        <b><?= $review['rating'] ?> Star<?= $review['rating'] > 1 ? 's' : '' ?></b>
                                    </p>
                                </div>
                                <p class="bv-text-para"><?= $review['comment'] ?></p>
                                <p class="bv-text-meta mt-3">
                                    <b>Reviewed:</b>
                                    <?= date("d M Y \a\\t h:i A", strtotime($review['created_at'])) ?>
                                </p>
                                <div class="bk-tags">
                                    <span class="bk-tag bk-tag-black">Unverified Purchase</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($reviews) > 5): ?>
                    <div class="mt-4 col-lg-8 text-md-end">
                        <span class="typo-link">
                            Load more
                            <div class="fas fa-arrow-down"></div>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>