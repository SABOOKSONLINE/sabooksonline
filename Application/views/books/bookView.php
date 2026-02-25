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

$hc_price            = isset($book['hc_price']) ? (float)$book['hc_price'] : 0.0;
$hc_discount_percent = isset($book['hc_discount_percent']) ? (int)$book['hc_discount_percent'] : 0;
$hc_country          = htmlspecialchars($book['hc_country'] ?? '');
$hc_pages            = isset($book['hc_pages']) ? (int)$book['hc_pages'] : 0;
$hc_weight_kg        = isset($book['hc_weight_kg']) ? (float)$book['hc_weight_kg'] : 0.0;
$hc_height_cm        = isset($book['hc_height_cm']) ? (float)$book['hc_height_cm'] : 0.0;
$hc_width_cm         = isset($book['hc_width_cm']) ? (float)$book['hc_width_cm'] : 0.0;
$hc_release_date     = htmlspecialchars($book['hc_release_date'] ?? '');
$hc_contributors     = htmlspecialchars($book['hc_contributors'] ?? '');
$hc_stock_count      = isset($book['hc_stock_count']) ? (int)$book['hc_stock_count'] : 0;


$date = formatDateComponents($date);
$ebook = $book['PDFURL'] ?? '';

$bookId = $_GET['q'] ?? null;
$audiobookId = $book['a_id'] ?? null;

$audiobook_sample = html_entity_decode($book['sample_url'] ?? "");

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

$_SESSION['action'] = $_SERVER['REQUEST_URI'];

$email = $_SESSION['ADMIN_EMAIL'] ?? '';

$userBooks = $bookModel->getPurchasedBooksByUserEmail($email);

// Check if the user purchased this book
$format = ' ';

foreach ($userBooks as $purchasedBook) {
    if ($purchasedBook['ID'] == $Id) {
        $format = $purchasedBook['FORMAT'];
        break;
    }
}

//this is for the share button
$link = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Check if internal hardcopy has meaningful data
$hasValidInternalHardcopy = !empty($book['hc_id']) && (
    (float)$hc_price > 0 ||
    (int)$hc_stock_count > 0 ||
    !empty($hc_country) ||
    (int)$hc_pages > 0
);
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
            <img src="/cms-data/book-covers/<?= $cover ?>" alt="Book Cover">
        </div>

        <!-- Book Details -->
        <div class="bv-details">
            <div class="">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="bv-heading mb-0"><?= $title ?></h4>
                    <?php
                    // Check if logged-in user owns this book
                    $isOwner = false;
                    if (isset($_SESSION['ADMIN_USERKEY']) && isset($book['USERID'])) {
                        $isOwner = strtolower($_SESSION['ADMIN_USERKEY']) === strtolower($book['USERID']);
                    }
                    if ($isOwner && isset($book['CONTENTID'])):
                    ?>
                        <a href="/dashboards/listings/<?= $book['CONTENTID'] ?>"
                            class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    <?php endif; ?>
                </div>
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
                    <p class="bv-text-para" id="book-description">
                        <?= strlen($description) > 500 ? substr($description, 0, 500) . '...' : $description ?>
                    </p>

                    <?php if (strlen($description) > 500): ?>
                        <button id="toggleDescription" class="bk-tag">Show more <i class="fas fa-angle-down"></i></button>
                    <?php endif; ?>
                <?php endif; ?>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const toggleBtn = document.getElementById('toggleDescription');
                        const descEl = document.getElementById('book-description');
                        if (!toggleBtn || !descEl) return;

                        let expanded = false;
                        const fullText = `<?= addslashes($description) ?>`;
                        const shortText = fullText.substring(0, 500) + '...';

                        toggleBtn.addEventListener('click', () => {
                            if (!expanded) {
                                descEl.textContent = fullText;
                                toggleBtn.innerHTML = 'Show less <i class="fas fa-angle-up"></i>';
                            } else {
                                descEl.textContent = shortText;
                                toggleBtn.innerHTML = 'Show more <i class="fas fa-angle-down"></i>';
                            }
                            expanded = !expanded;
                        });
                    });
                </script>

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
                <span class="bv-purchase-select" price="<?= (int)$eBookPrice ?>" available="<?= !empty($ebook) ?>">
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
                    <?php if ($audiobook_sample): ?>
                        <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                            <audio id="bg-music">
                                <source src="/cms-data/audiobooks/samples/<?= $audiobook_sample ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            <div class="position-relative">
                                <div class="bk-tags">
                                    <button class="bk-tag play-sample" onclick="playMusic()">
                                        <i class="fas fa-play"></i> Play Sample
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <span class="bv-purchase-select-h d-block mb-1">Audiobook</span>
                    <?php if ((int)$aBookPrice !== 0 && $audiobookId): ?>
                        <span class="bv-purchase-select-hL">R<?= $aBookPrice ?><small>.00</small></span>
                    <?php elseif ((int)$aBookPrice === 0 && $audiobookId): ?>
                        <span class="bv-purchase-select-hL">FREE</span>
                    <?php else: ?>
                        <span>Not available</span>
                    <?php endif; ?>
                </span>

                <!-- Hardcopy tab — only shown if hc_id exists -->
                <?php if (!empty($book['hc_id'])): ?>
                    <?php if ($hasValidInternalHardcopy): ?>
                        <!-- Internal Hardcopy (SABO stock) -->
                        <span class="bv-purchase-select" price="<?= $hc_price ?>" available="true">
                            <span class="bv-purchase-select-h">Hardcopy</span>
                            <?php if ((float)$hc_price > 0): ?>
                                <span class="bv-purchase-select-hL">R<?= number_format($hc_price, 2) ?></span>
                            <?php elseif ((int)$hc_stock_count > 0): ?>
                                <span class="bv-purchase-select-hL">FREE</span>
                            <?php else: ?>
                                <span>Not available</span>
                            <?php endif; ?>
                        </span>
                    <?php else: ?>
                        <!-- External Hardcopy (website link) -->
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
                    <?php endif; ?>
                <?php endif; ?>

                <div class="bv-purchase-details">
                    <span class="bv-price"></span>
                    <span class="bv-note-muted">This price applies to the format shown..</span>

                    <!-- ebook button -->
                    <div class="hide">
                        <?php if (((int)$eBookPrice > 0) && $format !== 'Ebook'): ?>
                            <form method="POST" action="/checkout" id="e-book" class="w-100 mt-3">
                                <input type="hidden" name="bookId" value="<?= $bookId ?>">
                                <button type="submit" class="btn btn-green w-100 d-flex justify-content-center align-items-center">
                                    <i class="fas fa-shopping-cart me-2"></i> Buy
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="/read/<?= $bookId ?>" id="e-book" class="btn btn-green w-100 mt-3 d-flex justify-content-center align-items-center">Read</a>
                        <?php endif; ?>
                    </div>

                    <!-- audiobook button -->
                    <div class="hide">
                        <?php if (((int)$aBookPrice > 0) && $format !== 'Audiobook'): ?>
                            <form method="POST" action="https://www.sabooksonline.co.za/checkout" id="audiobook" class="w-100 mt-3">
                                <input type="hidden" name="audiobookId" value="<?= $bookId ?>">
                                <button type="submit" class="btn btn-green w-100 d-flex justify-content-center align-items-center">
                                    <i class="fas fa-shopping-cart me-2"></i> Buy
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="/library/audiobook/<?= $bookId ?>" id="audiobook" class="btn btn-green w-100 mt-3 d-flex justify-content-center align-items-center">Play</a>
                        <?php endif; ?>
                    </div>

                    <!-- hardcopy button — only rendered if hc_id exists -->
                    <?php if (!empty($book['hc_id'])): ?>
                        <div class="hide">
                            <?php if ($userId): ?>
                                <button id="hardcopy" class="btn btn-green bv-buy-btn add-to-cart"
                                    data-book-id="<?= $book['ID']; ?>">
                                    Add to cart
                                </button>
                            <?php else: ?>
                                <a id="hardcopy" href="/login" class="btn btn-green w-100">Add to cart</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($book['hc_id'])): ?>
                    <span class="bv-note-muted p-2 py-0"><b>Note:</b> External purchase links are no longer supported. Only hardcopies sold directly through SA Books Online are available.</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.querySelector(".add-to-cart");

        // Guard: button only exists when hc_id is present
        if (!btn) return;

        btn.addEventListener("click", async function() {
            const bookId = this.getAttribute("data-book-id");

            const response = await fetch("/cart/add", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    book_id: bookId,
                    qty: 1
                }),
            });

            const data = await response.json();
            console.log(data);

            if (data.success) {
                alert("Added to cart!");
            } else {
                alert("Failed to add item.");
            }
        });
    });
</script>

<script>
    const bvSelectBtn = document.querySelectorAll(".bv-purchase-select");
    const bvDetails = document.querySelector(".bv-purchase-details");

    const print = (value) => {
        console.log(value);
    };

    let selectedPrice = document.querySelector(".bv-price");
    // Guard: bvBuyBtn only exists when hc_id is present
    let bvBuyBtn = document.querySelector(".bv-buy-btn");

    const updatePrice = (value) => {
        let price = parseInt(value);

        if (isNaN(price) || price === 0 || price === "") {
            selectedPrice.innerHTML = "FREE";
            selectedPrice.classList.add("bv-text-green");
        } else {
            selectedPrice.innerText = "R" + price;
            selectedPrice.classList.remove("bv-text-green");
        }
    };

    const selectFirstBvBtn = () => {
        for (let i = 0; i < bvSelectBtn.length; i++) {
            bvSelectBtn[i].classList.add("bv-active");

            const price = bvSelectBtn[i].getAttribute("price");
            updatePrice(price);
            if (bvBuyBtn) updateBvBuyBtn(bvSelectBtn[i]);
            removePriceDetail(bvSelectBtn[i]);
            showPurchaseOption(
                bvSelectBtn[i].firstElementChild.innerText.toLowerCase() + ""
            );
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
        if (!bvBuyBtn || !bvBuyBtn.childNodes[1]) return;
        bvBuyBtn.childNodes[1].innerText = btn.firstElementChild.innerText;
    };

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
            if (bvBuyBtn) updateBvBuyBtn(btn);
            showPurchaseOption(btn.firstElementChild.innerText.toLowerCase() + "");
        });
    });
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
                    const allButtons = document.querySelectorAll(`.action-${actionType}`);
                    allButtons.forEach(btn => {
                        btn.classList.remove('active', 'btn-danger', 'btn-warning', 'btn-primary');
                        if (actionType === 'like') btn.classList.add('btn-outline-danger');
                        if (actionType === 'wishlist') btn.classList.add('btn-outline-warning');
                        if (actionType === 'library') btn.classList.add('btn-outline-primary');
                    });

                    if (data.action === 'added') {
                        button.classList.add('active');
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
                } else {
                    alert(data.message || 'Something went wrong');
                }
            })
            .catch(err => {
                alert('Network error: ' + err.message);
            });
    }

    let samplePlaying = false;

    function playMusic() {
        const sampleAudio = document.getElementById('bg-music');
        const playSampleIcon = document.querySelector('.play-sample i');

        if (!samplePlaying) {
            sampleAudio.play().then(() => {
                samplePlaying = true;
                playSampleIcon.classList.remove("fa-play");
                playSampleIcon.classList.add("fa-pause");
            }).catch(error => {
                console.log("User interaction required:", error);
            });
        } else {
            sampleAudio.pause();
            samplePlaying = false;
            playSampleIcon.classList.remove("fa-pause");
            playSampleIcon.classList.add("fa-play");
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        const buyFormat = getQueryParam('buy_format');

        if (buyFormat) {
            let targetFormId = null;

            if (buyFormat === 'ebook') {
                targetFormId = 'e-book';
            } else if (buyFormat === 'audiobook') {
                targetFormId = 'audiobook';
            }

            if (targetFormId) {
                const targetForm = document.getElementById(targetFormId);

                if (targetForm && targetForm.tagName === 'FORM') {
                    targetForm.closest('.hide')?.classList.remove('hide');
                    targetForm.submit();
                    console.log('Initiating purchase for ' + buyFormat + '. Redirecting to payment gateway...');
                } else {
                    console.warn('Could not find a purchasable form for buy_format: ' + buyFormat + '. The item might be already owned or unavailable.');
                }
            }
        }
    });
</script>