<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo '
<div class="row">
    <!-- Book Cover -->
    <div class="col-md-3">
        <div class="book-view-cover">
            <img src="https://sabooksonline.co.za/cms-data/profile-images/' . $creator['ADMIN_PROFILE_IMAGE'] . '" class="img-fluid  bg-white" alt="' . $creator['ADMIN_NAME'] . ' Profile image">
        </div>
    </div>

    <!-- Book Info -->
    <div class="col-md-9">
        <h2 class="fw-bold text-capitalize">' . $creator['ADMIN_NAME'] . '</h2>
        <p class="muted text-capitalize">' . $creator['ADMIN_TYPE'] . ' joined - ' . $creator['ADMIN_DATE'] . '</p>
        <br>
        <h5 class="fw-bold text-capitalize">' . $creator['ADMIN_TYPE'] . ' Information</h5>
        <p>' . $creator['ADMIN_BIO'] . '</p>

        <div class="category-container mb-3 py-3">
            <div class="">
                <a href="#book_collection" class="category-link">Book Collection</a>
                <a href="' . $creator['ADMIN_WEBSITE'] . '" class="category-link">Publish Website</a>
                <a href="mailto:' . $creator['ADMIN_EMAIL'] . '" class="category-link">Email: ' . $creator['ADMIN_EMAIL'] . '</a>
                
                <a href="' . $creator['ADMIN_FACEBOOK'] . '" class="category-link">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="' . $creator['ADMIN_INSTAGRAM'] . '" class="category-link">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="' . $creator['ADMIN_TWITTER'] . '" class="category-link">
                    <i class="fab fa-x-twitter"></i>
                </a>
            </div>
        </div>
    </div>
</div>
';
