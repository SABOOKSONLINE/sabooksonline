<?php foreach ($books as $book):
    $contentId = strtolower($book['CONTENTID']);
    $cover = html_entity_decode($book['COVER']);
    $title = html_entity_decode($book['TITLE']);

    $longDescription = html_entity_decode($book['DESCRIPTION']);
    $description = html_entity_decode(strlen($longDescription) > 200 ? substr($longDescription, 0, 200) . '...' : $longDescription);

    $shortTitle = html_entity_decode(strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title);
    $userId = html_entity_decode(strtolower($book['USERID']));
    $publisher = ucwords(html_entity_decode($book['PUBLISHER']));

    $language = html_entity_decode($book['LANGUAGES']);
    $category = html_entity_decode($book['CATEGORY']);
?>

    <?php if ($reverse): ?>
        <div class="bk-card bk-card-lg">
            <div class="bk-details text-end">
                <div>
                    <a href="/library/book/<?= $contentId ?>" class="text-decoration-none">
                        <p class="bk-heading-xl">
                            <?= $shortTitle ?>
                        </p>
                    </a>
                    <p class="bk-text-meta">
                        Published by: <a class="text-muted" href="/creators/creator/<?= $userId ?>"><?= $publisher ?></a>
                    </p>
                    <p class="bk-text-para">
                        <?= $description ?>
                    </p>
                </div>

                <div class="bk-tags justify-content-end align-content-end">
                    <?php if ($language): ?>
                        <span class="bk-tag bk-tag-black"><?= $language ?></span>
                    <?php endif; ?>

                    <?php if ($category): ?>
                        <a href="/library/?category=<?= urlencode($category) ?>" class="bk-tag">
                            <?= $category ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="bk-img">
                <a href="/library/book/<?= $contentId ?>">
                    <img src="/cms-data/book-covers/<?= $cover ?>" alt="<?= $title ?>">
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="bk-card bk-card-lg">
            <div class="bk-img">
                <a href="/library/book/<?= $contentId ?>">
                    <img src="/cms-data/book-covers/<?= $cover ?>" alt="<?= $title ?>">
                </a>
            </div>
            <div class="bk-details">
                <div>
                    <a href="/library/book/<?= $contentId ?>" class="text-decoration-none">
                        <p class="bk-heading-xl">
                            <?= $shortTitle ?>
                        </p>
                    </a>
                    <p class="bk-text-meta">
                        Published by: <a class="text-muted" href="/creators/creator/<?= $userId ?>"><?= $publisher ?></a>
                    </p>
                    <p class="bk-text-para">
                        <?= $description ?>
                    </p>
                </div>

                <div class="bk-tags">
                    <?php if ($language): ?>
                        <span class="bk-tag bk-tag-black"><?= $language ?></span>
                    <?php endif; ?>

                    <?php if ($category): ?>
                        <a href="/library/?category=<?= urlencode($category) ?>" class="bk-tag">
                            <?= $category ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php endforeach; ?>