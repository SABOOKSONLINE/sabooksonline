<section class="section">
    <div class="container">
        <?php renderSectionHeading("Featured Magazines", "Discover the latest issues and trending publications.", "", "") ?>

        <div class="book-cards mt-4">
            <div class="book-card-slide">
                <?php
                $today = date('Y-m-d');
                $hasContent = false; // flag to track if we found any magazines

                foreach ($magazines as $magazine):
                    $publishDateRaw = $magazine['publish_date'];

                    if ($publishDateRaw <= $today):
                        $hasContent = true; // found at least one
                        $publisherId = $magazine['publisher_id'];
                        $publicKey = $magazine['public_key'];
                        $cover = html_entity_decode($magazine['cover_image_path']);
                        $title = html_entity_decode($magazine['title']);

                        $longDescription = html_entity_decode($magazine['description']);
                        $description = strlen($longDescription) > 200 ? substr($longDescription, 0, 200) . '...' : $longDescription;

                        $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
                        $publisherId = $magazine['publisher_id'];

                        $language = html_entity_decode($magazine['language']);
                        $category = html_entity_decode($magazine['category']);
                        $price = number_format($magazine['price'], 2);
                        $publishDate = date('D M Y', strtotime($publishDateRaw));

                        $user = $controller->getUserById($publisherId);
                        $publisher = $user['ADMIN_NAME'];
                        $publisherPublicKey = $user['ADMIN_USERKEY'];
                ?>
                        <div class="bk-card">
                            <span class="book-card-num"></span>
                            <div class="bk-img">
                                <a href="/media/magazines/<?= $publicKey ?>">
                                    <img src="/cms-data/magazine/covers/<?= $cover ?>" alt="<?= $title ?>">
                                </a>
                            </div>
                            <div class="bk-details">
                                <p class="bk-heading-md">
                                    <?= $shortTitle ?>
                                </p>
                                <!-- <p class="bk-text-meta">
                                    Published by: <a class="text-muted text-lowercase text-capitalize" href="/creators/creator/<?= $publisherPublicKey ?>"><?= $publisher ?></a>
                                </p> -->
                            </div>
                        </div>
                    <?php
                    endif;
                endforeach;

                // if no magazines were displayed
                if (!$hasContent): ?>
                    <div class="alert alert-info mt-3" role="alert">
                        No featured magazines are available at the moment. Please check back later.
                    </div>
                <?php endif; ?>
            </div>

            <div class="book-card-btn btn-right">
                <div>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>
    </div>
</section>