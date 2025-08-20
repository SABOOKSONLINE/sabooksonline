<section class="section">
    <div class="container">
        <?php renderSectionHeading("Latest Newspapers", "Stay informed with today's most important stories.", "", "") ?>

        <div class="book-cards mt-4">
            <div class="book-card-slide">
                <?php
                $today = date('Y-m-d');
                $hasContent = false;

                foreach ($newspapers as $newspaper):
                    $publishDateRaw = $newspaper['publish_date'];

                    if ($publishDateRaw <= $today):
                        $hasContent = true;
                        $publicKey = $newspaper['public_key'];
                        $cover = htmlspecialchars($newspaper['cover_image_path']);
                        $title = htmlspecialchars($newspaper['title']);

                        $longDescription = htmlspecialchars($newspaper['description']);
                        $description = strlen($longDescription) > 200 ? substr($longDescription, 0, 200) . '...' : $longDescription;

                        $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
                        $publisherId = $newspaper['publisher_id'];

                        $price = number_format($newspaper['price'], 2);
                        $publishDate = date('M j, Y', strtotime($publishDateRaw));

                        $user = $controller->getUserById($publisherId);
                        $publisher = $user['ADMIN_NAME'];
                        $publisherPublicKey = $user['ADMIN_USERKEY'];
                ?>
                        <div class="bk-card">
                            <span class="book-card-num"></span>
                            <div class="bk-img">
                                <a href="/media/newspapers/<?= $publicKey ?>">
                                    <img src="/cms-data/newspaper/covers/<?= $cover ?>" alt="<?= $title ?>">
                                </a>
                            </div>
                            <div class="bk-details">
                                <p class="bk-heading-md">
                                    <?= $shortTitle ?>
                                </p>
                                <p class="bk-text-meta">
                                    Published by: <a class="text-muted text-lowercase text-capitalize" href="/creators/creator/<?= $publisherPublicKey ?>"><?= $publisher ?></a>
                                </p>
                            </div>
                        </div>
                    <?php
                    endif;
                endforeach;

                // show alert if no newspapers matched
                if (!$hasContent): ?>
                    <div class="alert alert-info mt-3" role="alert">
                        No newspapers are available at the moment. Please check back later.
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