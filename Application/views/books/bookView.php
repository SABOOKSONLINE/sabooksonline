<?php
$contentId = strtolower($book['USERID']);
$bookId = strtolower($book['CONTENTID']);
$cover = html_entity_decode($book['COVER']);
$title = html_entity_decode($book['TITLE']);
$category = html_entity_decode($book['CATEGORY']);
$publisher = ucwords(html_entity_decode($book['PUBLISHER']));
$authors = html_entity_decode($book['AUTHORS']);
$description = html_entity_decode($book['DESCRIPTION']);
$isbn = html_entity_decode($book['ISBN']);
$website = html_entity_decode($book['WEBSITE']);
// $retailPrice = html_entity_decode($book['RETAILPRICE']);
$retailPrice = $book['RETAILPRICE'];

$ebook = $book['PDFURL'] ?? '';

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
            <div class="row gy-1">
                <?php if (!empty($ebook)): ?>
                    <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                        <a href="/library/readBook/<?= $bookId ?>" class="btn btn-green me-2">
                            <i class="fas fa-book-open"></i> READ E-BOOK NOW
                        </a>
                    </div>
                <?php else: ?>
                    <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                        <span class="btn btn-yellow me-2 disabled">
                            <i class="fas fa-book-open"></i> E-BOOK NOT AVAILABLE
                        </span>
                    </div>
                <?php endif; ?>

                <!-- LISTEN TO AUDIOBOOK -->
                <?php if ($audiobookId): ?>
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
                <?php else: ?>
                    <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                        <span class="btn btn-yellow me-2 disabled">
                            <i class="fas fa-headphones"></i> AUDIOBOOK NOT AVAILABLE
                        </span>
                    </div>
                <?php endif; ?>

                <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                    <form method="POST" action="/checkout">
                        <input type="hidden" name="bookId" value="<?= $bookId ?>">

                        <button type="submit" class="btn btn-blue me-2">BUY COPY</button>
                    </form>
                    <span class="fw-bold align-content-end">
                        <small class="text-muted fw-normal">RETAIL PRICE</small> <br>R<?= $retailPrice ?>.00
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>