<section class="section">
    <div class="container-fluid">
        <div class="container">
            <?php renderSectionHeading(
                "Featured Magazines",
                "Discover the latest issues and trending publications.",
                "",
                ""
            ); ?>
        </div>

        <div class="book-cards mt-4" id="magazines">
            <div class="book-card-slide scroll-right">
                <?php foreach ($magazines as $magazine):
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
                    $publishDate = date('D M Y', strtotime($magazine['publish_date']));

                    $user = $controller->getUserById($publisherId);
                    $publisher = $user['ADMIN_NAME'];
                    $publisherPublicKey = $user['ADMIN_USERKEY'];
                ?>
                    <div class="bk-card bk-card-lg">
                        <div class="bk-img">
                            <a href="/media/magazines/<?= $publicKey ?>">
                                <img src="/cms-data/magazine/covers/<?= $cover ?>" alt="<?= $title ?>" loading="lazy">
                            </a>
                        </div>
                        <div class="bk-details">
                            <div>
                                <a href="/media/magazines/<?= $publicKey ?>" class="text-decoration-none">
                                    <p class="bk-heading-xl">
                                        <?= $shortTitle ?>
                                    </p>
                                </a>
                                <p class="bk-text-meta">
                                    Published by: <a class="text-lowercase text-capitalize" href="/creators/creator/<?= $publisherPublicKey ?>"><?= $publisher ?></a>
                                </p>
                                <p class="bk-text-para">
                                    <?= $description ?>
                                </p>
                            </div>

                            <div class="bk-tags">
                                <?php if ($category): ?>
                                    <span class="bk-tag bk-tag-black">
                                        <?= $category ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($magazine['frequency']): ?>
                                    <span class="bk-tag bk-tag-gray"><?= ucfirst($magazine['frequency']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="book-card-btn btn-right">
            <div>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>
</section>