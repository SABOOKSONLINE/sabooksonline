<?php

if (isset($_SESSION['ADMIN_ID'])) {
    $userImgUrl = $profile;
    $userId = $_SESSION['ADMIN_ID'];
}

?>


<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php if (isset($userId)): ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Add Your Review</h5>
                    <button type="button" class="btn-close mr-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="commentForm" action="/reviews-handler" method="POST">
                        <input type="text" class="form-control" name="user_id" value="<?= $userId ?>" hidden>
                        <input type="text" class="form-control" name="book_id" value="<?= $_GET['q'] ?>" hidden>
                        <input type="text" class="form-control" name="user_img_url" value="<?= $userImgUrl ?>" hidden>

                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" class="form-control" name="name" placeholder="e.g John Cena" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Your Rating</label>
                            <div class="rating-stars mb-2">
                                <i class="far fa-star rating-star" data-rating="1"></i>
                                <i class="far fa-star rating-star" data-rating="2"></i>
                                <i class="far fa-star rating-star" data-rating="3"></i>
                                <i class="far fa-star rating-star" data-rating="4"></i>
                                <i class="far fa-star rating-star" data-rating="5"></i>
                            </div>
                            <input type="hidden" name="rating_value" id="rating_value" required>
                        </div>

                        <div class="mb-3">
                            <label for="commentText" class="form-label">Your Review</label>
                            <textarea class="form-control" name="comment" rows="5" required></textarea>
                        </div>

                        <!-- <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="verifiedPurchase">
                        <label class="form-check-label" for="verifiedPurchase">I purchased this product</label>
                    </div> -->

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-black">Submit Review</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <h5 class="modal-title" id="commentModalLabel">Login to leave a comment</h5>
            <?php endif; ?>
        </div>
    </div>
</div>