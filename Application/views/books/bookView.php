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
$eBookPrice = $book['EBOOKPRICE'];
$aBookPrice = $book['ABOOKPRICE'];
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
                    <p class="bv-text-meta"><b>Publisher:</b> <a class="bv-text-meta" href="/creators/creator/<?= $contentId ?>"><?= $publisher ?></a></p>
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

                <div class="bk-tags mt-4 justify-content-between">
                    <div class="bk-tags">
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

                    <button type="button" class="bk-tag text-md-end" data-bs-toggle="modal" data-bs-target="#commentModal">
                        <i class="fas fa-plus"></i>
                        Add Review
                    </button>
                </div>

            </div>
        </div>

        <div class="">
            <div class="bv-purchase">
                <!-- E-Book -->
                <span class="bv-purchase-select" price="<?= $eBookPrice ?>" available="<?= !empty($ebook) ?>">
                    <span class="bv-purchase-select-h">E-Book</span>
                    <?php if ((int)$eBookPrice !== 0 && $ebook): ?>
                        <span class="bv-purchase-select-hL">R<?= $eBookPrice ?><small>.00</small></span>
                    <?php elseif ((int)$eBookPrice === 0 && $ebook): ?>
                        <span class="bv-purchase-select-hL">FREE</span>
                    <?php else: ?>
                        <span>Not available</span>
                    <?php endif; ?>
                </span>

                <!-- Audiobook -->
                <span class="bv-purchase-select" price="<?= $aBookPrice ?>" available="<?= isset($audiobookId) ?>">
                    <span class="bv-purchase-select-h">Audiobook</span>
                    <?php if ((int)$aBookPrice !== 0 && $audiobookId): ?>
                        <span class="bv-purchase-select-hL">R<?= $aBookPrice ?><small>.00</small></span>
                    <?php elseif ((int)$aBookPrice === 0 && $audiobookId): ?>
                        <span class="bv-purchase-select-hL">FREE</span>
                    <?php else: ?>
                        <span>Not available</span>
                    <?php endif; ?>
                </span>

                <!-- Hardcopy -->
                <span class="bv-purchase-select" price="<?= $retailPrice ?>" available="<?= !empty($website) ?>">
                    <span class="bv-purchase-select-h">Hardcopy</span>
                    <?php if ((int)$retailPrice !== 0 && !empty($website)): ?>
                        <span class="bv-purchase-select-hL">R<?= $retailPrice ?><small>.00</small></span>
                    <?php elseif ((int)$retailPrice === 0 && !empty($website)): ?>
                        <span class="bv-purchase-select-hL">FREE</span>
                    <?php else: ?>
                        <span>Not available</span>
                    <?php endif; ?>
                </span>

                <div class="bv-purchase-details">
                    <span class="bv-price"><span></span><small>00</small></span>
                    <span class="bv-note-muted">This price applies to the format shown..</span>

                    <!-- ebook button -->
                    <div class="hide">
                        <?php if (((int)$eBookPrice > 0) && !$userOwnsThisBook): ?>
                            <!-- BUY FORM -->
                            <form method="POST" action="/checkout" id="e-book" class="w-100 mt-3">
                                <input type="hidden" name="bookId" value="<?= $bookId ?>">
                                <button type="submit" class="btn btn-green w-100  d-flex justify-content-center align-items-center">
                                    <i class="fas fa-shopping-cart me-2"></i> Buy
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- READ BUTTON -->
                            <a href="/library/readBook/<?= $bookId ?>" id="e-book" class="btn btn-green bv-buy-btn">
                                <span>Read</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- audiobook button -->
                    <div class="hide">
                        <?php if (((int)$aBookPrice > 0) && !$userOwnsThisBook): ?>
                            <!-- BUY FORM -->
                            <form method="POST" action="https://www.sabooksonline.co.za/checkout" id="audiobook" class="w-100 mt-3">
                                <input type="hidden" name="audiobookId" value="<?= $bookId ?>">
                                <button type="submit" class="btn btn-green w-100  d-flex justify-content-center align-items-center">
                                    <i class="fas fa-shopping-cart me-2"></i> Buy
                                </button>
                            </form>
                        <?php else: ?>
                            <!-- READ BUTTON -->
                            <a href="/library/audiobook/<?= $bookId ?>" id="audiobook" class="btn btn-green bv-buy-btn">
                                <span>listen</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- hardcopy button -->
                    <div class="hide">
                        <a href="<?= $website ?>" id="hardcopy" class="btn btn-green bv-buy-btn">purchase Link<span></span></a>
                        <span class="bv-note-muted" id="hardcopy"><b>Disclaimer:</b> Physical book purchases are fulfilled by third-party sellers. SA Books Online is not responsible for payments, delivery, or product condition. Please contact the seller directly for support.</span>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const bvSelectBtn = document.querySelectorAll(".bv-purchase-select");
    const bvDetails = document.querySelector(".bv-purchase-details");

    const print = (value) => {
        console.log(value);
    };

    let selectedPrice = document.querySelector(".bv-price");
    let bvBuyBtn = document.querySelector(".bv-buy-btn");

    const updatePrice = (value) => {
        const price = parseInt(value);

        if (price == 0) {
            selectedPrice.innerHTML = "FREE";
            selectedPrice.classList.add("bv-text-green");
        } else {
            selectedPrice.innerText = "R" + price;
        }
    };

    const selectFirstBvBtn = () => {
        for (let i = 0; i < bvSelectBtn.length; i++) {
            bvSelectBtn[i].classList.add("bv-active");

            const price = bvSelectBtn[i].getAttribute("price");
            updatePrice(price);
            updateBvBuyBtn(bvSelectBtn[i]);
            removePriceDetail(bvSelectBtn[i]);
            showPurchaseOption(
                bvSelectBtn[i].firstElementChild.innerText.toLowerCase() + ""
            );
            // showClickedBtn(bvSelectBtn[i]);
            break;
        }
    };

    const removePriceDetail = (btn) => {
        const contentAvailable = btn.getAttribute("available");
        if (!contentAvailable) {
            bvDetails.style.display = "none";
        } else {
            bvDetails.style.display = "grid";
        }
    };

    const updateBvBuyBtn = (btn) => {
        bvBuyBtn.childNodes[1].innerText = btn.firstElementChild.innerText;
    };

    // const bvMainBtns = document.querySelectorAll(".bv-main-btn");
    // const resetBvMainBtn = () => {
    //     bvMainBtns.forEach((btn) => {
    //         if (!btn.classList.contains("bv-main-btn")) {
    //             btn.classList.add("bv-main-btn");
    //         }
    //     });
    // };

    // const showClickedBtn = (btn) => {
    //     resetBvMainBtn();
    //     const bvMainBtn = document.querySelector(
    //         "#" + btn.firstElementChild.innerText.toLowerCase()
    //     );
    //     // bvMainBtn.classList.remove("bv-main-btn");
    //     print(bvMainBtn);
    // };

    const resetBvBuyBtn = () => {
        const bvBuyBtns = document.querySelectorAll(".bv-purchase-details > div");

        bvBuyBtns.forEach((btn) => {
            btn.classList.add("hide");
        });
    };

    const showPurchaseOption = (optionId) => {
        const btn = document.getElementById(optionId);

        resetBvBuyBtn();

        if (btn && btn.parentElement.classList.contains("hide")) {
            btn.parentElement.classList.remove("hide");
        }
    };

    selectFirstBvBtn();

    const removeBvActive = () => {
        bvSelectBtn.forEach((otherBtns) => {
            otherBtns.classList.remove("bv-active");
        });
    };

    bvSelectBtn.forEach((btn) => {
        btn.addEventListener("click", () => {
            removeBvActive();
            btn.classList.add("bv-active");

            removePriceDetail(btn);
            updatePrice(btn.getAttribute("price"));
            updateBvBuyBtn(btn);
            showPurchaseOption(btn.firstElementChild.innerText.toLowerCase() + "");
            // showBtn(btn);
        });
    });
</script>

</script>


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