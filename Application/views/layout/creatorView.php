<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$name         = htmlspecialchars($creator['ADMIN_NAME']);
$type         = htmlspecialchars($creator['ADMIN_TYPE']);
$profileImage = htmlspecialchars($creator['ADMIN_PROFILE_IMAGE']);
$joinDate     = htmlspecialchars($creator['ADMIN_DATE']);
$bio          = nl2br(htmlspecialchars($creator['ADMIN_BIO']));
$website      = htmlspecialchars($creator['ADMIN_WEBSITE']);
$email        = htmlspecialchars($creator['ADMIN_EMAIL']);
$facebook     = htmlspecialchars($creator['ADMIN_FACEBOOK']);
$instagram    = htmlspecialchars($creator['ADMIN_INSTAGRAM']);
$twitter      = htmlspecialchars($creator['ADMIN_TWITTER']);
?>

<div class="row">
    <!-- Book Cover -->
    <div class="col-md-3 mb-3">
        <div class="book-view-cover">
            <img
                src="https://sabooksonline.co.za/cms-data/profile-images/<?php echo $profileImage; ?>"
                class="img-fluid bg-white"
                alt="<?php echo $name; ?> Profile image">
        </div>
    </div>

    <!-- Book Info -->
    <div class="col-md-9">
        <h2 class="fw-bold text-capitalize"><?php echo $name; ?></h2>

        <p class="muted text-capitalize"><?php echo $type; ?> joined - <?php echo $joinDate; ?></p>

        <br>

        <h5 class="fw-bold text-capitalize"><?php echo $type; ?> Information</h5>
        <p><?php echo $bio; ?></p>

        <div class="category-container mb-3 py-3">
            <div>
                <a href="#book_collection" class="category-link">Book Collection</a>
                <a href="<?php echo $website; ?>" class="category-link">Publish Website</a>
                <a href="mailto:<?php echo $email; ?>" class="category-link">Email: <?php echo $email; ?></a>

                <a href="<?php echo $facebook; ?>" class="category-link">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="<?php echo $instagram; ?>" class="category-link">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="<?php echo $twitter; ?>" class="category-link">
                    <i class="fab fa-x-twitter"></i>
                </a>
            </div>
        </div>
    </div>
</div>