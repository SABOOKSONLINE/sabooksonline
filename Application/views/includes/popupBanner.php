<?php
$uri = $_SERVER["REQUEST_URI"];
$popupBanners = $homeController->banners()['banners']['popup_banners'];

if ($popupBanners):
    foreach ($popupBanners as $banner):
        if (!str_ends_with($banner["link"], $uri)):
?>

            <div class="popup-banner-bg hide-banner-bg"></div>

            <div class="popup-banner hide-banner">
                <div class="popup-banner-container">
                    <div class="popup-banner-cover">
                        <img
                            src="/cms-data/book-covers/<?= $banner['COVER'] ?>"
                            alt="<?= $banner['TITLE'] ?>"
                            class="popup-banner-img-bg">
                        <img
                            src="/cms-data/book-covers/<?= $banner['COVER'] ?>"
                            alt="<?= $banner['TITLE'] ?>"
                            class="popup-banner-img">
                    </div>

                    <div class="popup-banner-info">
                        <div>
                            <span class="bk-tag popup-sponsored">Sponsored Ad</span>
                        </div>
                        <span class="popup-tag">Mental Wellness Month</span>

                        <h1 class="typo-heading"><?= $banner['TITLE'] ?></h1>

                        <i class="popup-tag text-lowercase text-capitalize">Published By: <?= $banner['PUBLISHER'] ?></i>

                        <p class="popup-desc">
                            <?= $banner['description'] ?>
                        </p>

                        <div class="popup-subtext">
                            <p><?= $banner['subtext'] ?></p>
                        </div>
                        <div>
                            <a href="<?= $banner['link'] ?>" class="btn btn-white"><?= $banner['button_text'] ?> <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>

                </div>
                <button
                    type="button"
                    id="close-popup-banner"
                    aria-label="Close banner"
                    class="btn-close-icon">
                    <i class="fas fa-times"></i>
                </button>
            </div>

<?php
        endif;
    endforeach;
endif;
?>