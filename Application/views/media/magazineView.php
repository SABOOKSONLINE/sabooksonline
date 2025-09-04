<?php
require_once __DIR__ . "/../includes/header.php";

require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../../models/MediaModel.php";
require_once __DIR__ . "/../../controllers/MediaController.php";

require_once __DIR__ . "/../layout/sectionHeading.php";

$controller = new MediaController($conn);
$magazineData = $controller->getMagazineByPublicKey($_GET['publicKey']);

$id = $magazineData['id'];
$magazineId = $magazineData['public_key'];

$title = $magazineData['title'];
$description = $magazineData['description'];

$cover_image_path = $magazineData['cover_image_path'];
$category = $magazineData['category'];

$frequency = $magazineData['frequency'];
$issn = $magazineData['issn'];

$editor = $magazineData['editor'];
$language = $magazineData['language'];

$country = $magazineData['country'];
$publisher_id = $magazineData['publisher_id'];

$publish_date = $magazineData['publish_date'];
$price = $magazineData['price'];

$user = $controller->getUserById($publisher_id);
$publisher = $user['ADMIN_NAME'];

$publisherPublicKey = $user['ADMIN_USERKEY'];
$link = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>


<body>
    <?php require_once __DIR__ . "/../includes/nav.php";
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
            <!-- Magazine Cover -->
            <div class="bv-img">
                <img src="/cms-data/magazine/covers/<?= $cover_image_path ?>" alt="Magazine Cover">
            </div>

            <!-- Magazine Details -->
            <div class="bv-details">
                <div class="">
                    <h4 class="bv-heading"><?= $title ?></h4>
                    <?php if ($editor): ?>
                        <p class="bv-text-meta">
                            <b>Editor:</b> <span class=""><?= $editor ?></span>
                        </p>
                    <?php endif; ?>
                    <?php if ($publisher_id): ?>
                        <!-- <p class="bv-text-meta"><b>Publisher:</b> <a class="bv-text-meta text-lowercase text-capitalize" href="/creators/creator/<?= $publisherPublicKey ?>"><?= $publisher ?></a></p> -->
                    <?php endif; ?>
                    <?php if ($publish_date):
                        $date = date_create($publish_date);
                    ?>
                        <p class="bv-text-meta"><b>Published:</b> <?= date_format($date, 'd F Y') ?></p>
                    <?php endif; ?>
                    <?php if ($issn): ?>
                        <p class="bv-text-meta"><b>ISSN:</b> <?= $issn ?></p>
                    <?php endif; ?>
                    <?php if ($issn): ?>
                        <p class="bv-text-meta"><b>Country:</b> <?= $country ?></p>
                    <?php endif; ?>
                    <?php if ($description): ?>
                        <p class="bv-text-para"><?= $description ?></p>
                    <?php endif; ?>

                    <div class="bk-tags mt-4 justify-content-between">
                        <div class="bk-tags">
                            <?php if ($frequency): ?>
                                <span class="bk-tag bk-tag-black"><?= $frequency ?></span>
                            <?php endif; ?>
                            <?php if ($category): ?>
                                <span class="bk-tag"><?= $category ?></span>
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
                    <span class="bv-purchase-select" price="<?= $price ?>" available="1">
                        <span class="bv-purchase-select-h">Digital Version</span>
                        <?php if ((float)$price !== 0.00): ?>
                            <span class="bv-purchase-select-hL">R<?= $price ?></span>
                        <!-- <?php else: ?>
                            <span class="bv-purchase-select-hL">FREE</span> -->
                        <?php endif; ?>
                    </span>

                    <!-- Print Version (if available) -->
                    <!-- <span class="bv-purchase-select" price="<?= (float)$price + 50 ?>" available="0">
                        <span class="bv-purchase-select-h d-block mb-1">Print Version</span>
                        <span>Not available</span>
                    </span> -->

                    <div class="bv-purchase-details">
                        <span class="bv-price"><span></span><small>00</small></span>
                        <span class="bv-note-muted">This price applies to the digital version.</span>

                        <!-- Digital purchase button -->
                        <div class="">
                            <?php if ((float)$price > 0): ?>
                                <!-- BUY FORM -->
                                <form method="POST" action="/checkout" id="digital-version" class="w-100 mt-3">
                                    <input type="hidden" name="magazineId" value="<?= $magazineId ?>">
                                    <input type="hidden" name="format" value="digital">
                                    <button type="submit" class="btn btn-green w-100  d-flex justify-content-center align-items-center">
                                        <i class="fas fa-shopping-cart me-2"></i> Purchase Digital Issue
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- READ BUTTON -->   

                                <a href="/read/<?=$magazineId?>?category=magazine"  id="digital-version" class="btn btn-green w-100 mt-3  d-flex justify-content-center align-items-center">
                                    Read Now
                                </a>
                            <?php endif; ?>
                        </div>
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
    });
</script>

    <?php
    include __DIR__ . "/../includes/footer.php";
    include __DIR__ .  "/../includes/scripts.php";
    ?>
</body>