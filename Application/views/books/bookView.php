<?php
$contentId = strtolower($book['USERID']);
$cover = htmlspecialchars($book['COVER']);
$title = htmlspecialchars($book['TITLE']);
$publisher = ucwords(htmlspecialchars($book['PUBLISHER']));
$authors = htmlspecialchars($book['AUTHORS']);
$description = htmlspecialchars($book['DESCRIPTION']);
$isbn = htmlspecialchars($book['ISBN']);
$website = htmlspecialchars($book['WEBSITE']);
$retailPrice = htmlspecialchars($book['RETAILPRICE']);
?>

<div class="container pt-4 pb-5">
    <div class="row">
        <!-- Book Cover -->
        <div class="col-md-3 book-view-cover">
            <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>" class="img-fluid" alt="Book Cover">
        </div>

        <!-- Book Info -->
        <div class="col-md-9">
            <h2 class="fw-bold text-capitalize"><?= $title ?></h2>
            <p class="mb-1 text-capitalize">
                <span class="muted">Published by:</span>
                <a href="/creators/creator/<?= $contentId ?>" class="fw-semibold"><?= $publisher ?></a>
            </p>
            <p class="mb-3 text-capitalize">
                <span class="muted">Author/s:</span>
                <span class="fw-semibold"><?= $authors ?></span>
            </p>

            <h6 class="mb-2">Book Synopsis:</h6>
            <p><?= $description ?></p>

            <div class="category-container mb-3 py-3">
                <div class="">
                    <span class="category-link"><b>ISBN NUMBER:</b> <?= $isbn ?></span>
                    <a href="/creators/creator/<?= $contentId ?>" class="category-link"><b>About</b> <?= $publisher ?></a>
                    <a href="<?= $website ?>" class="category-link">Publish Website</a>
                </div>
            </div>

            <!-- Ratings -->
            <div class="mb-3">
                Ratings:
                <span class="text-warning">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </span>
                <span class="ms-2 text-muted">4.0</span>
            </div>

            <!-- Action Buttons & Price -->
            <div class="row">
                <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
                    <a href="#" class="btn btn-green">READ NOW</a>
                    <span class="ms-2 fw-bold">COMING SOON</span>
                </div>
                <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
                    <a href="#" class="btn btn-yellow"><i class="bi bi-headphones"></i> LISTEN TO AUDIOBOOK</a>
                    <span class="ms-2 fw-bold">COMING SOON</span>
                </div>
                <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
                    <a href="#" class="btn btn-blue">BUY COPY</a>
                    <span class="ms-2 fw-bold">R<?= $retailPrice ?>.00</span>
                </div>
            </div>
        </div>
    </div>
</div>