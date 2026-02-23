<?php

$academicBookId = $book['public_key'] ?? '';
$title = $book['title'] ?? '';
$author = $book['author'] ?? '';
$editor = $book['editor'] ?? '';
$description = $book['description'] ?? '';
$subject = $book['subject'] ?? '';
$level = $book['level'] ?? '';
$language = $book['language'] ?? '';
$edition = $book['edition'] ?? '';
$pages = $book['pages'] ?? '';
$isbn = $book['ISBN'] ?? '';
$publishDate = $book['publish_date'] ?? null;
$coverImagePath = $book['cover_image_path'] ?? '';
$ebookPrice = $book['ebook_price'] ?? 0;
$pdfPath = $book['pdf_path'] ?? '';
$physicalBookPrice = $book['physical_book_price'] ?? 0;
$externalLink = $book['link'] ?? '';

$publisher = $book['ADMIN_NAME'] ?? '';
$publisherPublicKey = $book['ADMIN_USERKEY'] ?? '';

$link = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>



<div class="jumbotron jumbotron-sm">
    <div class="container h-100 d-flex flex-column justify-content-end py-5">
    </div>
</div>

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
                    <!-- WhatsApp -->
                    <a href="https://wa.me/?text=Check out this magazine: <?= urlencode($link) ?>"
                        class="text-success" target="_blank">
                        <i class="fab fa-whatsapp fa-2x"></i>
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($link) ?>"
                        class="text-primary" target="_blank">
                        <i class="fab fa-facebook fa-2x"></i>
                    </a>

                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($link) ?>"
                        class="text-primary" target="_blank">
                        <i class="fab fa-linkedin fa-2x"></i>
                    </a>

                    <!-- Twitter -->
                    <a href="https://twitter.com/intent/tweet?text=Check out this magazine&url=<?= urlencode($link) ?>"
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
            <?php if (!empty($coverImagePath)): ?>
                <img src="/cms-data/academic/covers/<?= htmlspecialchars($coverImagePath) ?>" 
                     alt="Book Cover"
                     onerror="this.onerror=null; this.src='data:image/svg+xml;base64,<?= base64_encode('<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"300\" height=\"450\" viewBox=\"0 0 300 450\"><defs><filter id=\"blur\"><feGaussianBlur in=\"SourceGraphic\" stdDeviation=\"12\"/></filter><linearGradient id=\"redGrad\" x1=\"0%\" y1=\"0%\" x2=\"0%\" y2=\"100%\"><stop offset=\"0%\" style=\"stop-color:#DE3831;stop-opacity:0.9\"/><stop offset=\"100%\" style=\"stop-color:#DE3831;stop-opacity:0.6\"/></linearGradient><linearGradient id=\"blueGrad\" x1=\"0%\" y1=\"0%\" x2=\"0%\" y2=\"100%\"><stop offset=\"0%\" style=\"stop-color:#002395;stop-opacity:0.6\"/><stop offset=\"100%\" style=\"stop-color:#002395;stop-opacity:0.9\"/></linearGradient><linearGradient id=\"greenGrad\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"0%\"><stop offset=\"0%\" style=\"stop-color:#007A4D;stop-opacity:0.8\"/><stop offset=\"100%\" style=\"stop-color:#007A4D;stop-opacity:0.4\"/></linearGradient><linearGradient id=\"yellowGrad\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"0%\"><stop offset=\"0%\" style=\"stop-color:#FFB612;stop-opacity:0.4\"/><stop offset=\"100%\" style=\"stop-color:#FFB612;stop-opacity:0.8\"/></linearGradient></defs><rect width=\"300\" height=\"450\" fill=\"#FFFFFF\"/><rect width=\"300\" height=\"150\" fill=\"url(#redGrad)\" filter=\"url(#blur)\"/><rect y=\"300\" width=\"300\" height=\"150\" fill=\"url(#blueGrad)\" filter=\"url(#blur)\"/><polygon points=\"0,0 0,450 150,225\" fill=\"url(#greenGrad)\" filter=\"url(#blur)\"/><polygon points=\"300,0 300,450 150,225\" fill=\"url(#yellowGrad)\" filter=\"url(#blur)\"/><polygon points=\"0,0 300,0 150,225\" fill=\"#000000\" opacity=\"0.3\" filter=\"url(#blur)\"/><text x=\"50%\" y=\"50%\" font-family=\"Arial, sans-serif\" font-size=\"20\" fill=\"#FFFFFF\" text-anchor=\"middle\" dominant-baseline=\"middle\" font-weight=\"bold\" opacity=\"0.9\">No Cover</text></svg>') ?>'; this.style.border='1px solid #dee2e6';">
            <?php else: ?>
                <div style="width: 100%; height: 100%; min-height: 450px; border: 1px solid #dee2e6; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; overflow: hidden; background: #FFFFFF;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 300 450" style="position: absolute; top: 0; left: 0; z-index: 0;">
                        <defs>
                            <filter id="blurLarge"><feGaussianBlur in="SourceGraphic" stdDeviation="12"/></filter>
                            <linearGradient id="redGradLarge" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#DE3831;stop-opacity:0.9"/>
                                <stop offset="100%" style="stop-color:#DE3831;stop-opacity:0.6"/>
                            </linearGradient>
                            <linearGradient id="blueGradLarge" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#002395;stop-opacity:0.6"/>
                                <stop offset="100%" style="stop-color:#002395;stop-opacity:0.9"/>
                            </linearGradient>
                            <linearGradient id="greenGradLarge" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#007A4D;stop-opacity:0.8"/>
                                <stop offset="100%" style="stop-color:#007A4D;stop-opacity:0.4"/>
                            </linearGradient>
                            <linearGradient id="yellowGradLarge" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#FFB612;stop-opacity:0.4"/>
                                <stop offset="100%" style="stop-color:#FFB612;stop-opacity:0.8"/>
                            </linearGradient>
                        </defs>
                        <rect width="300" height="450" fill="#FFFFFF"/>
                        <rect width="300" height="150" fill="url(#redGradLarge)" filter="url(#blurLarge)"/>
                        <rect y="300" width="300" height="150" fill="url(#blueGradLarge)" filter="url(#blurLarge)"/>
                        <polygon points="0,0 0,450 150,225" fill="url(#greenGradLarge)" filter="url(#blurLarge)"/>
                        <polygon points="300,0 300,450 150,225" fill="url(#yellowGradLarge)" filter="url(#blurLarge)"/>
                        <polygon points="0,0 300,0 150,225" fill="#000000" opacity="0.3" filter="url(#blurLarge)"/>
                    </svg>
                    <div style="position: relative; z-index: 1; text-align: center; color: #FFFFFF;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 10px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <p style="margin: 0; font-size: 18px; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">No Cover Image</p>
                        <small style="color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Cover image not available</small>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Magazine Details -->
        <div class="bv-details">
            <div class="">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="bv-heading mb-0"><?= $title ?></h4>
                    <?php
                    // Check if logged-in user owns this book
                    $isOwner = false;
                    if (isset($_SESSION['ADMIN_ID']) && isset($book['publisher_id'])) {
                        $isOwner = (int)$_SESSION['ADMIN_ID'] === (int)$book['publisher_id'];
                    }
                    if ($isOwner && isset($book['id'])):
                    ?>
                        <a href="/dashboards/update/academic/<?= $book['id'] ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    <?php endif; ?>
                </div>
                <?php if ($author): ?>
                    <p class="bv-text-meta">
                        <b>Author:</b> <span class=""><?= $author ?></span>
                    </p>
                <?php endif; ?>
                <?php if ($editor): ?>
                    <p class="bv-text-meta">
                        <b>Editor:</b> <span class=""><?= $editor ?></span>
                    </p>
                <?php endif; ?>
                <?php if ($publisher): ?>
                    <p class="bv-text-meta"><b>Publisher:</b> <a class="bv-text-meta text-lowercase text-capitalize" href="/creators/creator/<?= $publisherPublicKey ?>"><?= $publisher ?></a></p>
                <?php endif; ?>
                <?php if ($publishDate):
                    $date = date_create($publishDate);
                ?>
                    <p class="bv-text-meta"><b>Published:</b> <?= date_format($date, 'd F Y') ?></p>
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
                        <?php if ($subject): ?>
                            <span class="bk-tag"><?= $subject ?></span>
                        <?php endif; ?>
                        <?php if ($level): ?>
                            <span class="bk-tag"><?= $level ?></span>
                        <?php endif; ?>
                        <button class="bk-tag" data-bs-toggle="modal" data-bs-target="#shareCard">
                            <i class="fas fa-share"></i> Share
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div class="">
            <div class="bv-purchase">
                <!-- Digital Version -->
                <span class="bv-purchase-select" price="<?= $ebookPrice ?>" available="1">
                    <span class="bv-purchase-select-h">Digital Version</span>
                    <?php if ((float)$ebookPrice !== 0.00): ?>
                        <span class="bv-purchase-select-hL">R<?= $ebookPrice ?></span>
                    <?php else: ?>
                        <span class="bv-purchase-select-hL">FREE</span>
                    <?php endif; ?>
                </span>

                <!-- Hardcopy Version (if physical price exists) -->
                <?php if ((float)$physicalBookPrice > 0): ?>
                    <span class="bv-purchase-select" price="<?= $physicalBookPrice ?>" available="1">
                        <span class="bv-purchase-select-h">Hardcopy</span>
                        <span class="bv-purchase-select-hL">R<?= $physicalBookPrice ?></span>
                    </span>
                <?php endif; ?>

                <div class="bv-purchase-details">
                    <span class="bv-price"><span></span><small>00</small></span>
                    <span class="bv-note-muted">This price applies to the format shown.</span>

                    <!-- Digital Version purchase buttons -->
                    <div id="digital version" class="hide">
                        <?php if ((float)$ebookPrice > 0): ?>
                            <!-- Paid Digital Version - Purchase Now -->
                            <?php if (isset($_SESSION['ADMIN_ID'])): ?>
                                <form method="POST" action="/checkout" class="w-100 mt-3">
                                    <input type="hidden" name="academicBookId" value="<?= $academicBookId ?>">
                                    <input type="hidden" name="format" value="digital">
                                    <button type="submit" class="btn btn-green w-100  d-flex justify-content-center align-items-center">
                                        <i class="fas fa-credit-card me-2"></i> Purchase Now
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="/login" class="btn btn-green w-100 mt-3  d-flex justify-content-center align-items-center">
                                    <i class="fas fa-credit-card me-2"></i> Purchase Now
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- FREE Digital Version - Show Read Now -->
                            <?php if (isset($_SESSION['ADMIN_ID'])): ?>
                                <a href="/read/<?=$academicBookId ?>?category=academic" class="btn btn-green w-100 mt-3  d-flex justify-content-center align-items-center">
                                    <i class="fas fa-book me-2"></i> Read Now  
                                </a>
                            <?php else: ?>
                                <a href="/login" class="btn btn-green w-100 mt-3  d-flex justify-content-center align-items-center">
                                    <i class="fas fa-book me-2"></i> Read Now  
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Hardcopy Version purchase buttons -->
                    <?php if ((float)$physicalBookPrice > 0): ?>
                        <div id="hardcopy" class="hide">
                            <!-- Hardcopy always has price - Show Add to Cart and Purchase Now -->
                            <?php if (isset($_SESSION['ADMIN_ID'])): ?>
                                <button class="btn btn-blue w-100 mt-3 d-flex justify-content-center align-items-center add-to-cart-academic"
                                    data-book-id="<?= $academicBookId ?>"
                                    data-book-type="academic"
                                    data-format="hardcopy">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                </button>
                            <?php else: ?>
                                <a href="/login" class="btn btn-blue w-100 mt-3 d-flex justify-content-center align-items-center">
                                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to get query parameters from the URL
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        const buyFormat = getQueryParam('buy');

        if (buyFormat) {
            let targetFormId = "digital-version";


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

        // Format selector functionality (similar to bookView.php)
        const bvSelectBtn = document.querySelectorAll(".bv-purchase-select");
        const bvDetails = document.querySelector(".bv-purchase-details");
        const selectedPrice = document.querySelector(".bv-price");

        const updatePrice = (value) => {
            let price = parseFloat(value);

            if (isNaN(price) || price === 0 || price === "") {
                selectedPrice.innerHTML = "FREE";
                selectedPrice.classList.add("bv-text-green");
            } else {
                selectedPrice.innerHTML = "R" + price.toFixed(2);
                selectedPrice.classList.remove("bv-text-green");
            }
        };

        const removePriceDetail = (btn) => {
            const contentAvailable = btn.getAttribute("available");
            if (!contentAvailable || contentAvailable === "0") {
                bvDetails.style.display = "none";
            } else {
                bvDetails.style.display = "grid";
            }
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
            if (btn && btn.classList.contains("hide")) {
                btn.classList.remove("hide");
            }
        };

        const selectFirstBvBtn = () => {
            for (let i = 0; i < bvSelectBtn.length; i++) {
                bvSelectBtn[i].classList.add("bv-active");
                const price = bvSelectBtn[i].getAttribute("price");
                updatePrice(price);
                removePriceDetail(bvSelectBtn[i]);
                const formatText = bvSelectBtn[i].querySelector(".bv-purchase-select-h").innerText.toLowerCase().trim();
                // Map format names to div IDs: "digital version" -> "digital version", "hardcopy" -> "hardcopy"
                const formatName = formatText === "hardcopy" ? "hardcopy" : "digital version";
                showPurchaseOption(formatName);
                break;
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
                const formatText = btn.querySelector(".bv-purchase-select-h").innerText.toLowerCase().trim();
                // Map format names to div IDs: "digital version" -> "digital version", "hardcopy" -> "hardcopy"
                const formatName = formatText === "hardcopy" ? "hardcopy" : "digital version";
                showPurchaseOption(formatName);
            });
        });

        // Add to cart functionality for academic books
        const addToCartBtns = document.querySelectorAll('.add-to-cart-academic');
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', async function() {
                const bookId = this.dataset.bookId;
                const bookType = this.dataset.bookType || 'academic';
                const format = this.dataset.format || 'digital';

                try {
                    const response = await fetch("/cart/add", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            book_id: bookId,
                            book_type: bookType,
                            format: format,
                            qty: 1
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert("Added to cart!");
                        // Optionally update cart count in header if it exists
                        if (typeof updateCartCount === 'function') {
                            updateCartCount();
                        }
                    } else {
                        alert("Failed to add to cart. " + (data.error || ""));
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                    alert("Something went wrong while adding to cart.");
                }
            });
        });
    });
</script>

<?php
include __DIR__ . "/../includes/payfast.php";
include __DIR__ . "/../includes/footer.php";
include __DIR__ .  "/../includes/scripts.php";
?>
</body>