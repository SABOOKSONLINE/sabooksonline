<?php

function renderHero($title, $subTitle, $buttonText, $buttonLink, $buttonType = "black"): void
{
?>

    <div class="container py-4">
        <div class="p-5 mb-4 rounded-5 hero border shadow-md">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold"><?= $title ?></h1>
                <p class="col-md-8 fs-4"><?= $subTitle ?></p> <a href="<?= $buttonLink ?>" class="btn btn-<?= $buttonType ?> btn-lg">
                    <?= $buttonText ?>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

<?php
}
