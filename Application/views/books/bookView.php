<?php

function formatDateComponents($inputDate)
{
    // Clean input (remove "st", "nd", "rd", "th")
    $cleanedDate = preg_replace('/(\d+)(st|nd|rd|th)/i', '$1', $inputDate);

    try {
        $date = new DateTime($cleanedDate);
    } catch (Exception $e) {

        $date2 = DateTime::createFromFormat('l j \o\f F Y', $cleanedDate);

        if ($date2) {

            $day = $date2->format('j');      // e.g. 8
            $month = $date2->format('F');    // e.g. July
            $year = $date2->format('Y');
        }
        return [
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ];
    }

    return [
        'year' => $date->format('Y'),
        'month' => $date->format('F'),
        'day' => $date->format('j'), // no leading zero
    ];
}


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
$retailPrice = $book['RETAILPRICE'];
$language = $book['LANGUAGES'];
$eBookPrice = $book['EBOOKPRICE'] ?? '0';
$aBookPrice = $book['ABOOKPRICE'] ?? '0';
$date = $book['DATEPOSTED'];

$date = formatDateComponents($date);
$ebook = $book['PDFURL'] ?? '';

$bookId = $_GET['q'] ?? null;
$audiobookId = $book['a_id'] ?? null;

require __DIR__ . "/../../models/UserModel.php";
require __DIR__ . "/../../models/ActionModel.php";
require __DIR__ . "/../../Config/connection.php";

$actionModel = new ActionModel($conn);
$userId = $_SESSION['ADMIN_USERKEY'] ?? null;

if ($userId) {
    $liked = $actionModel->hasAction($userId, $bookId, 'like');
    $wishlisted = $actionModel->hasAction($userId, $bookId, 'wishlist');
    $inLibrary = $actionModel->hasAction($userId, $bookId, 'library');
} else {
    $liked = $wishlisted = $inLibrary = false;
}

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

<div class="container">
    <div class="bk-view row">
        <!-- Book Cover -->
        <div class="bv-img">
            <img src="/cms-data/book-covers/<?= $cover ?>" alt="Book Cover">
        </div>


        <!-- Book Details -->
        <div class="bv-details">
            <div class="">
                <h4 class="bv-heading"><?= $title ?></h4>
                <?php if ($authors): ?>
                    <p class="bv-text-meta">
                        <b>Author:</b> <span class=""><?= $authors ?></span>
                    </p>
                <?php endif; ?>
                <?php if ($publisher): ?>
                    <p class="bv-text-meta"><b>Publisher:</b> <?= $publisher ?></p>
                <?php endif; ?>
                <?php if ($date): ?>
                    <p class="bv-text-meta"><b>Released:</b> <?= $date['day'] ?> <?= $date['month'] ?> <?= $date['year'] ?></p>
                <?php endif; ?>
                <?php if ($isbn): ?>
                    <p class="bv-text-meta"><b>ISBN:</b> <?= $isbn ?></p>
                <?php endif; ?>
                <?php if ($description): ?>
                    <p class="bv-text-para"><?= $description ?></p>
                <?php endif; ?>

                <div class="bk-tags mt-4">
                    <?php if ($language): ?>
                        <span class="bk-tag bk-tag-black"><?= $language ?></span>
                    <?php endif; ?>
                    <?php if ($category): ?>
                        <a href="/library?category=<?= $category ?>" class="bk-tag"><?= $category ?></a>
                    <?php endif; ?>
                    <button class="bk-tag" data-bs-toggle="modal" data-bs-target="#shareCard">
                        <i class="fas fa-share"></i> Share
                    </button>
                </div>


                <!-- <div class="book-actions" data-book-id="<?= htmlspecialchars($bookId) ?>">
                    <button type="button"
                        class="btn px-2 action-like <?= $liked ? 'btn-danger active' : 'btn-outline-danger' ?>"
                        <?= !$userId ? 'disabled' : '' ?>
                        onclick="performAction(this, 'like')">
                        <i class="fas fa-heart me-2"></i> Like
                    </button>

                    <button type="button"
                        class="btn px-2 action-library <?= $inLibrary ? 'btn-primary active' : 'btn-outline-primary' ?>"
                        <?= !$userId ? 'disabled' : '' ?>
                        onclick="performAction(this, 'library')">
                        <i class="fas fa-book me-2"></i> Add to Library
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#shareCard">
                        <i class="fas fa-share"></i> Share
                    </button>
                </div> -->

                <!-- <div class="container border-top pt-4 mt-4">
                    <div class="row text-center align-items-center justify-content-between row-cols-2 row-cols-md-6 g-3">

                        <div class="col">
                            <div class="text-muted small">GENRE</div>
                            <div class="fw-bold fs-5"><?= $category ?></div>
                        </div>

                        <div class="col">
                            <div class="text-muted small">PUBLISHER</div>
                            <div class="fw-bold fs-5"><?= implode('.', array_map(fn($w) => strtoupper($w[0]), explode(' ', trim($publisher)))) . '.' ?></div>
                            <div class="small text-secondary">
                                <a href="/creators/creator/<?= $contentId ?>" class="text-decoration-none text-secondary">
                                    <?= $publisher ?>
                                </a>
                            </div>
                        </div>


                        <div class="col">
                            <div class="text-muted small">RELEASED</div>
                            <div class="fw-bold fs-5"><?= $date['year'] ?></div>
                            <div class="small text-secondary"><?= "{$date['month']} {$date['day']}" ?></div>
                        </div>


                        <div class="col">
                            <div class="text-muted small">LANGUAGE</div>
                            <div class="fw-bold fs-5">ZA</div>
                            <div class="small text-secondary">English 🇿🇦</div>
                        </div>
                    </div>
                </div> -->

            </div>
        </div>

        <div class="">
            <div class="bv-purchase">
                <span class="bv-purchase-select bv-active">
                    <span class="bv-purchase-select-h">Hardcopy</span>
                    <span class="bv-purchase-select-hL">R199<small>00</small></span>
                </span>

                <span class="bv-purchase-select">
                    <span class="bv-purchase-select-h">E-Book</span>
                    <span class="bv-purchase-select-hL">R149<small>00</small></span>
                </span>

                <span class="bv-purchase-select">
                    <span class="bv-purchase-select-h">Audiobook</span>
                    <span class="bv-purchase-select-hL">R299<small>00</small></span>
                </span>

                <div class="bv-purchase-details">
                    <span class="bv-price">R149<small>00</small></span>
                    <span class="bv-note-muted">This price applies to the format shown.</span>
                    <a href="" class="btn btn-green bv-buy-btn">Buy Now</a>
                    <span class="bv-note-muted"><b>Disclaimer:</b> Physical book purchases are fulfilled by third-party sellers. SA Books Online is not responsible for payments, delivery, or product condition. Please contact the seller directly for support.</span>
                </div>
            </div>

            <div class="card-body d-grid gap-3">

                <!-- ✅ Read E-Book -->
                <!-- <?php if ((int)$eBookPrice === 0 && !empty($ebook)): ?>
                        <a href="/library/readBook/<?= $bookId ?>" class="btn btn-success w-30 rounded-pill d-flex justify-content-between align-items-center px-5">
                            <span><i class="fas fa-book-open me-2"></i> Read E-Book</span>
                            <span class="text-white fw-semibold">Free</span>
                        </a>
                    <?php elseif ($userOwnsThisBook && !empty($ebook)): ?>
                        <a href="/library/readBook/<?= $bookId ?>" class="btn btn-success w-30 rounded-pill d-flex justify-content-between align-items-center px-5">
                            <span><i class="fas fa-book-open me-2"></i> Read E-Book</span>
                            <span class="text-white fw-semibold">Owned</span>
                        </a>
                    <?php endif; ?> -->

                <!-- ✅ Buy E-Book -->
                <!-- <?php if ((int)$eBookPrice !== 0): ?>
                        <?php if ((float)$eBookPrice >= 10 && !empty($ebook)): ?>
                            <form method="POST" action="/checkout" id="buyEbookForm" class="w-100">
                                <input type="hidden" name="bookId" value="<?= $bookId ?>">

                                <button type="submit" id="buyEbookButton" class="btn btn-primary w-100 rounded-pill d-flex justify-content-between align-items-center px-5">
                                    <span><i class="fas fa-shopping-cart me-2"></i> Buy E-Book</span>
                                    <span class="fw-semibold">R<?= number_format($eBookPrice, 2) ?></span>
                                </button>
                            </form>
                            <?php if (isset($_SESSION['buy']) && $_SESSION['buy'] === 'yes'): ?>
                                <script>
                                    window.addEventListener('DOMContentLoaded', () => {
                                        const btn = document.getElementById('buyEbookButton');
                                        if (btn) btn.click();
                                    });
                                </script>
                                <?php unset($_SESSION['buy']); ?>
                            <?php endif; ?>
                        <?php elseif ((int)$eBookPrice !== 0 || empty($eBookPrice)): ?>
                            <button class="btn btn-outline-secondary w-100 rounded-pill disabled">
                                <i class="fas fa-book me-2"></i> E-Book Not Available
                            </button>
                        <?php endif; ?>
                    <?php endif; ?> -->


                <!-- ✅ Audiobook -->
                <!-- <?php if ($audiobookId): ?>
                        <a href="/library/audiobook/<?= $bookId ?>" target="_blank" class="btn btn-secondary w-100 rounded-pill d-flex justify-content-between align-items-center px-5">
                            <span><i class="fas fa-headphones me-2"></i> Listen to Audiobook</span>
                            <span class="fw-semibold">R<?= number_format($aBookPrice, 2) ?></span>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary w-100 rounded-pill disabled">
                            <i class="fas fa-headphones me-2"></i> Audiobook Not Available
                        </button>
                    <?php endif; ?> -->

                <!-- ✅ Physical Book -->
                <!-- <?php if ($website): ?>
                        <a href="<?= htmlspecialchars($website) ?>" target="_blank" class="btn btn-dark w-100 rounded-pill d-flex justify-content-between align-items-center px-5">
                            <span><i class="fas fa-store me-2"></i> Buy Physical Book</span>
                            <span class="fw-semibold">R<?= number_format($retailPrice, 2) ?></span>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary w-30 rounded-pill disabled">
                            <i class="fas fa-link me-2"></i> Purchase Link Not Available
                        </button>
                    <?php endif; ?> -->

            </div>
        </div>

        <!-- Disclaimer -->
        <!-- <div class="col-12">
            <div class="alert alert-warning mt-3 small shadow-sm">
                <strong class="text-danger">Disclaimer:</strong> Physical book purchases are fulfilled by third-party sellers. SA Books Online is not responsible for payments, delivery, or product condition. Please contact the seller directly for support.
            </div>
        </div> -->
    </div>
</div>

<script>
    function performAction(button, actionType) {
        const bookId = button.closest('.book-actions').dataset.bookId;

        fetch('/api/book-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `book_id=${encodeURIComponent(bookId)}&action_type=${encodeURIComponent(actionType)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Remove active class and filled btn styles from all buttons of the same type
                    const allButtons = document.querySelectorAll(`.action-${actionType}`);
                    allButtons.forEach(btn => {
                        btn.classList.remove('active', 'btn-danger', 'btn-warning', 'btn-primary');
                        // Add back outline styles
                        if (actionType === 'like') btn.classList.add('btn-outline-danger');
                        if (actionType === 'wishlist') btn.classList.add('btn-outline-warning');
                        if (actionType === 'library') btn.classList.add('btn-outline-primary');
                    });

                    if (data.action === 'added') {
                        button.classList.add('active');
                        // Remove outline, add filled style
                        if (actionType === 'like') {
                            button.classList.remove('btn-outline-danger');
                            button.classList.add('btn-danger');
                        }
                        if (actionType === 'wishlist') {
                            button.classList.remove('btn-outline-warning');
                            button.classList.add('btn-warning');
                        }
                        if (actionType === 'library') {
                            button.classList.remove('btn-outline-primary');
                            button.classList.add('btn-primary');
                        }
                    }
                    // If removed, button remains outlined and inactive (already handled above)
                } else {
                    alert(data.message || 'Something went wrong');
                }
            })
            .catch(err => {
                alert('Network error: ' + err.message);
            });
    }
</script>