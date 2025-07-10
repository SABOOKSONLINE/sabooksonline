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
    <div class="bv-view row">
        <!-- Book Cover -->
        <div class="bv-img">
            <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>" alt="Book Cover">
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
            </div>
        </div>

        <div class="">
            <div class="bv-purchase">
                <span class="bv-purchase-select" price="<?= $eBookPrice ?>" available="<?= !empty($ebook) ?>">
                    <span class="bv-purchase-select-h">E-Book</span>
                    <?php if ((int)$eBookPrice !== 0 && $ebook): ?>
                        <span class="bv-purchase-select-hL">R<?= $eBookPrice ?><small>.00</small></span>
                    <?php elseif ((int)$eBookPrice === 0 && $ebook): ?>
                        <span class="bv-purchase-select-hL">FREE</span>
                    <?php endif; ?>
                </span>

                <span class="bv-purchase-select" price="<?= $aBookPrice ?>" available="<?= isset($audiobookId) ?>">
                    <span class="bv-purchase-select-h">Audiobook</span>
                    <?php if ((int)$aBookPrice !== 0 && $audiobookId): ?>
                        <span class="bv-purchase-select-hL">R<?= isset($aBookPrice) ?><small>.00</small></span>
                    <?php elseif ((int)$aBookPrice === 0 && $audiobookId): ?>
                        <span class="bv-purchase-select-hL">FREE</span>
                    <?php endif; ?>
                </span>

                <span class="bv-purchase-select" price="<?= $retailPrice ?>" available="<?= !empty($website) ?>">
                    <span class="bv-purchase-select-h">Hardcopy</span>
                    <?php if ((int)$retailPrice !== 0 && !empty($website)): ?>
                        <span class="bv-purchase-select-hL">R<?= $retailPrice ?><small>.00</small></span>
                    <?php elseif ((int)$retailPrice === 0 && !empty($website)): ?>
                        <span class="bv-purchase-select-hL">FREE</span>
                    <?php endif; ?>
                </span>

                <div class="bv-purchase-details">
                    <span class="bv-price"><span></span><small>00</small></span>
                    <span class="bv-note-muted">This price applies to the format shown..</span>


                    <!-- ebook button -->
                    <div class="hide">
                        <a href="/library/readBook/<?= $bookId ?>" id="e-book" class="btn btn-green bv-buy-btn">Read<span></span></a>
                    </div>

                    <!-- audiobook button -->
                    <div class="hide">
                        <a href="/library/audiobook/<?= $bookId ?>" id="audiobook" class="btn btn-green bv-buy-btn">Read<span></span></a>
                    </div>

                    <!-- hardcopy button -->
                    <div class="hide">
                        <a href="<?= $website ?>" id="hardcopy" class="btn btn-green bv-buy-btn">Read<span></span></a>
                    </div>

                    <span class="bv-note-muted"><b>Disclaimer:</b> Physical book purchases are fulfilled by third-party sellers. SA Books Online is not responsible for payments, delivery, or product condition. Please contact the seller directly for support.</span>
                </div>
            </div>
        </div>
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