<?php
$pageBanners = $homeController->banners()['banners']['page_banners'];

if ($pageBanners):
?>

    <div id="myCarousel" class="carousel slide my-4" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php foreach ($pageBanners as $index => $banner): ?>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="<?= $index ?>"
                    class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                    aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner" style="border-radius: 25px;">
            <?php foreach ($pageBanners as $index => $banner): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <a href="<?= $banner['link'] ?>">
                        <img src="/cms-data/banners/<?= $banner['banner_image'] ?>"
                            class="d-block w-100"
                            alt="Banner Image"
                            style="border-radius: 25px; object-fit: cover; max-height: 500px;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

<?php endif; ?>