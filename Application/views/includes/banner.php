<div id="myCarousel" class="carousel slide banner" data-bs-ride="carousel">
    <div class="carousel-inner" style="border-radius: 25px;">
        <?php foreach ($banners as $index => $banner): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <a href="<?= $banner['UPLOADED'] ?>">
                    <img src="https://admin-dashboard.sabooksonline.co.za/banners/<?= $banner['IMAGE'] ?>"
                        class="d-block w-100"
                        alt="<?= $banner['SLIDE'] ?>"
                        style="border-radius: 25px">
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>