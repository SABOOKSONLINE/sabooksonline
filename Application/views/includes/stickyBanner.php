<?php
$uri = $_SERVER["REQUEST_URI"];
$stickyBanners = [];

if (isset($homeController)) {
    $bannersData = $homeController->banners();
    $stickyBanners = $bannersData['banners']['sticky_banners'] ?? [];
}

if ($uri !== "/media" && $uri !== "/library/academic" && !empty($stickyBanners)): ?>
    <nav class="navbar" id="sticky-banner">
        <div class="sticky-slider">
            <?php foreach ($stickyBanners as $banner): ?>
                <div class="">
                    <div>
                        <h5 class="typo-heading banner-heading mb-1">
                            <?= htmlspecialchars($banner['heading']) ?>
                        </h5>
                        <p class="typo-subheading banner-subheading mb-0">
                            <?= htmlspecialchars($banner['subheading']) ?>
                        </p>
                    </div>

                    <div class="">
                        <a href="<?= htmlspecialchars($banner['button_link']) ?>" class="btn btn-white">
                            <?= htmlspecialchars($banner['button_text']) ?> <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </nav>
<?php endif; ?>