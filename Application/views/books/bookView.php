<?php
$contentId = strtolower($book['USERID']);
$Id = strtolower($book['ID']);

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
$eBookPrice = $book['EBOOKPRICE'] ?? '0';
$aBookPrice = $book['ABOOKPRICE'] ?? '0';


$ebook = $book['PDFURL'] ?? '';

$bookId = $_GET['q'] ?? null;

$audiobookId = $book['a_id'] ?? null;

require __DIR__ . "/../../models/UserModel.php";
require __DIR__ . "/../../Config/connection.php";


$bookModel = new UserModel($conn);

// Use logged-in user's email (assumes session is set)
$email = $_SESSION['ADMIN_EMAIL'] ?? '';

$_SESSION['action'] = $_SERVER['REQUEST_URI'];


$userBooks = $bookModel->getPurchasedBooksByUserEmail($email);

// Check if the user purchased this book
$userOwnsThisBook = false;

foreach ($userBooks as $purchasedBook) {
    if ($purchasedBook['ID'] == $Id) {
        $userOwnsThisBook = true;
        break;
    }
}

//this is for the share button
$link = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<div class="modal fade" id="shareCard" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header border-0 position-relative">
                <h5 class="modal-title w-100 text-center">Share This</h5>
                <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="input-group mb-4">
                    <input type="text" class="form-control" value="<?= $link ?>" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="copyLink">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-center gap-4">
                    <a href="https://wa.me/?text=Check%20out%20this%20book%20published%20by%20<?= urlencode($publisher) ?>%20at%20sabooksonline%20<?= urlencode($link) ?>"
                        class="text-success" target="_blank">
                        <i class="fab fa-whatsapp fa-2x"></i>
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.sabooksonline.co.za/library/book/<?= $_GET['q'] ?>"
                        class="text-primary" target="_blank">
                        <i class="fab fa-facebook fa-2x"></i>
                    </a>

                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($link) ?>&title=Book%20published%20by%20<?= urlencode($publisher) ?>&summary=Check%20out%20this%20book%20at%20sabooksonline"
                        class="text-primary" target="_blank">
                        <i class="fab fa-linkedin fa-2x"></i>
                    </a>

                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20this%20book%20published%20by%20<?= urlencode($publisher) ?>%20at%20sabooksonline&url=<?= urlencode($link) ?>"
                        class="text-info" target="_blank">
                        <i class="fab fa-twitter fa-2x"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <button class="category-link" data-bs-toggle="modal" data-bs-target="#shareCard"><i class="fas fa-share"></i> Share</button>
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
                <!-- READ EBOOK BUTTON -->
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5><?= $title ?></h5>

                        <?php if ((int)$retailPrice === 0): ?>
                            <!-- FREE BOOK -->
                            <?php if (!empty($ebook)): ?>
                                <a href="/library/readBook/<?= $bookId ?>" class="btn btn-success">
                                    <i class="fas fa-book-open"></i> READ E-BOOK NOW (Free)
                                </a>
                            <?php endif; ?>
                        <?php elseif ($userOwnsThisBook): ?>
                            <!-- PAID BOOK: USER OWNS IT -->
                            <?php if (!empty($ebook)): ?>
                                <a href="/library/readBook/<?= $bookId ?>" class="btn btn-success">
                                    <i class="fas fa-book-open"></i> READ E-BOOK NOW
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>


                    </div>




                <!-- LISTEN TO AUDIOBOOK -->
                <?php if ($audiobookId): ?>
                    <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                        <a href="/library/audiobook/<?= $bookId ?>" target="_blank" class="btn btn-yellow me-2">
                            <i class="fas fa-headphones"></i> LISTEN TO AUDIOBOOK
                        </a>
                        <span class="fw-bold align-content-end text-end">
                            <small class="text-muted fw-normal">
                                Price
                            </small><br>R<?= number_format($aBookPrice, 2) ?>
                        </span>
                    </div>
                <?php else: ?>
                    <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                        <span class="btn btn-yellow me-2 disabled">
                            <i class="fas fa-headphones"></i> AUDIOBOOK NOT AVAILABLE
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ((float)$eBookPrice >= 10 && !empty($ebook)): ?>
                    <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                        <form method="POST" action="/checkout" id="buyEbookForm">
                            <input type="hidden" name="bookId" value="<?= $bookId ?>">
                            <button type="submit" class="btn btn-blue me-2" id="buyEbookButton">BUY Ebook</button>
                        </form>
                        <span class="fw-bold align-content-end">
                            <small class="text-muted fw-normal">PRICE</small> <br>R<?= number_format($eBookPrice, 2) ?>
                        </span>
                    </div>

                    <?php if (isset($_SESSION['buy']) && $_SESSION['buy'] === 'yes'): ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                const btn = document.getElementById('buyEbookButton');
                                if (btn) btn.click();
                            });
                        </script>
                        <?php unset($_SESSION['buy']); ?>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                        <span class="btn btn-yellow me-2 disabled">
                            <i class="fas fa-book"></i> EBOOK NOT AVAILABLE
                        </span>
                    </div>
                <?php endif; ?>


                <?php if ($website): ?>
                <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                    <a href="<?= htmlspecialchars($website) ?>" target="_blank" class="btn btn-primary">
                        Physical book – Purchase link
                    </a>
                    <span class="fw-bold align-content-end">
                            <small class="text-muted fw-normal">PRICE</small> <br>R<?= number_format($retailPrice, 2) ?>
                        </span>
                </div>
            <?php else: ?>
             <div class="col-12 d-flex justify-content-between align-items-center p-3 py-2 rounded bg-light">
                        <span class="btn btn-yellow me-2 disabled">
                            <i class="fas fa-link"></i> Purchase LINK NOT AVAILABLE
                        </span>
                    </div>
                <?php endif; ?>


                <span class="small text-muted mt-3 d-block">
                    <small><span class="text-danger">Disclaimer: </span>Physical Book Purchases are the
                        Responsibility of Third-Party Sellers.</small>
                    <small class="d-block">SA Books Online serves as a digital platform facilitating the discovery and promotion of
                        books. However, the responsibility for physical book sales—including monetary collection,
                        warehousing, delivery, and quality control—rests solely with the third-party sellers (authors,
                        publishers, or retailers). SA Books Online does not engage in or guarantee the ful lment,
                        shipment, or condition of physical books purchased through or as a result of activity on our
                        platform. Customers are encouraged to engage directly with the relevant seller for any
                        enquiries or support related to their purchase.</small>
                </span>
            </div>
        </div>
    </div>
</div>
</div>