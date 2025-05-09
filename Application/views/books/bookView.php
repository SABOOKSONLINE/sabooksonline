<?php
$contentId = strtolower($book['USERID']);
$bookId = strtolower($book['CONTENTID']);
$cover = htmlspecialchars($book['COVER']);
$title = htmlspecialchars($book['TITLE']);
$category = htmlspecialchars($book['CATEGORY']);
$publisher = ucwords(htmlspecialchars($book['PUBLISHER']));
$authors = htmlspecialchars($book['AUTHORS']);
$description = htmlspecialchars($book['DESCRIPTION']);
$isbn = htmlspecialchars($book['ISBN']);
$website = htmlspecialchars($book['WEBSITE']);
$retailPrice = htmlspecialchars($book['RETAILPRICE']);

$bookId = $_GET['q'] ?? null;

$audiobookId = $book['a_id'] ?? null;
?>


<div class="container pt-4 pb-5">
    <div class="row">
        <!-- Book Cover -->
        <div class="col-md-3 book-view-cover mb-3">
            <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>" class="img-fluid" alt="Book Cover">
        </div>

        <!-- Book Info -->
        <div class="col-md-9">
            <h2 class="fw-bold text-capitalize"><?= $title ?></h2>
            <?php if ($publisher): ?>
                <p class="mb-1 text-capitalize">
                    <span class="muted">Published by:</span>
                    <a href="/creators/creator/<?= $contentId ?>" class="fw-semibold"><?= $publisher ?></a>
                </p>
            <?php endif; ?>

            <?php if ($authors): ?>
                <p class="mb-1 text-capitalize">
                    <span class="muted">Author/s:</span>
                    <span class="fw-semibold"><?= $authors ?></span>
                </p>
            <?php endif; ?>

            <p class="mb-3 text-capitalize">
                <span class="muted">Category:</span>
                <a href="/library?category=<?= $category ?>" class="fw-semibold"><?= $category ?></a>
            </p>

            <h6 class="mb-2">Book Synopsis:</h6>
            <p><?= $description ?></p>

            <div class="category-container mb-3 py-3">
                <div class="">
                    <span class="category-link"><b>ISBN NUMBER:</b> <?= $isbn ?></span>
                    <a href="/creators/creator/<?= $contentId ?>" class="category-link"><b>About</b> <?= $publisher ?></a>
                    <!-- <a href="<?= $website ?>" class="category-link">Publish Website</a> -->
                </div>
            </div>

            <!-- Ratings Comming soon -->
            <!-- <div class="mb-3">
                Ratings:
                <span class="text-warning">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </span>
                <span class="ms-2 text-muted">4.0</span>
            </div> -->

            <!-- Action Buttons & Price -->
            <div class="row gy-3">
                <!-- READ NOW -->
                <!-- <div class="col-12 d-flex flex-wrap align-items-center">
                    <span class="btn btn-green me-2">READ NOW</span>
                    <span class="text-muted mb-2">COMING SOON</span>
                </div> -->

                <div class="col-12 d-flex flex-wrap align-items-center">
                    <a href="/readBook/<?= $bookId ?>" class="btn btn-green me-2">READ NOW</a>
                </div>


                <!-- LISTEN TO AUDIOBOOK -->
                <!-- <?php // if ($audiobookId): 
                        ?> -->
                <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                    <a href="/library/audiobook/<?= $bookId ?>" target="_blank" class="btn btn-yellow me-2">
                        <i class="fas fa-headphones"></i> LISTEN TO AUDIOBOOK
                    </a>
                    <span class="fw-bold align-content-end text-end">
                        <small class="text-muted fw-normal">
                            FREE
                        </small>
                    </span>
                </div>
                <!-- <?php // endif; 
                        ?> -->

                <!-- BUY COPY -->
                <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                    <a href="<?= $website ?>" target="_blank" class="btn btn-blue me-2">BUY COPY</a>
                    <span class="fw-bold align-content-end"><small class="text-muted fw-normal">RETAIL PRICE</small> <br>R<?= $retailPrice ?>.00</span>
                </div>

                <!-- <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                <form method="POST" action="/checkout">
                    <input type="hidden" name="contentId" value="<?= strtolower($book['USERID']) ?>">
                    <input type="hidden" name="cover" value="<?= htmlspecialchars($book['COVER']) ?>">
                    <input type="hidden" name="title" value="<?= htmlspecialchars($book['TITLE']) ?>">
                    <input type="hidden" name="publisher" value="<?= ucwords(htmlspecialchars($book['PUBLISHER'])) ?>">
                    <input type="hidden" name="retailPrice" value="<?= htmlspecialchars($book['RETAILPRICE']) ?>">

                    <button type="submit" class="btn btn-blue me-2">BUY COPY</button>
                </form>
                <span class="fw-bold align-content-end">
                    <small class="text-muted fw-normal">RETAIL PRICE</small> <br>R<?= $retailPrice ?>.00
                </span>
                </div> -->
            </div>
        </div>
    </div>
</div>