<section class="section">
    <div class="container-fluid">
        <div class="container">
            <?php renderSectionHeading(
                "Latest Newspapers",
                "Stay informed with today's most important stories.",
                "",
                ""
            ); ?>
        </div>

        <div class="book-cards mt-4" id="newspapers">
            <div class="book-card-slide scroll-right">
                <?php foreach ($newspapers as $newspaper):
                    $publicKey = $newspaper['public_key'];
                    $cover = htmlspecialchars($newspaper['cover_image_path']);
                    $title = htmlspecialchars($newspaper['title']);

                    $longDescription = htmlspecialchars($newspaper['description']);
                    $description = strlen($longDescription) > 200 ? substr($longDescription, 0, 200) . '...' : $longDescription;

                    $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
                    $publisherId = $newspaper['publisher_id'];

                    $price = number_format($newspaper['price'], 2);
                    $publishDate = date('M j, Y', strtotime($newspaper['publish_date']));

                    $user = $controller->getUserById($publisherId);
                    $publisher = $user['ADMIN_NAME'];
                    $publisherPublicKey = $user['ADMIN_USERKEY'];
                ?>
                    <div class="bk-card bk-card-lg">
                        <div class="bk-img">
                            <a href="/media/newspapers/<?= $publicKey ?>">
                                <img src="/cms-data/newspaper/covers/<?= $cover ?>" alt="<?= $title ?>" loading="lazy">
                            </a>
                        </div>
                        <div class="bk-details">
                            <div>
                                <a href="/media/newspapers/<?= $publicKey ?>" class="text-decoration-none">
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
                                <?php if ($newspaper['category']): ?>
                                    <span class="bk-tag bk-tag-black">
                                        <?= html_entity_decode($newspaper['category']) ?>
                                    </span>
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