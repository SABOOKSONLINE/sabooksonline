<?php foreach ($books as $book):
    $contentId = strtolower($book['CONTENTID']);
    $cover = html_entity_decode($book['COVER']);
    $title = html_entity_decode($book['TITLE']);
    $shortTitle = html_entity_decode(strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title);
    $userId = html_entity_decode(strtolower($book['USERID']));
    $publisher = ucwords(html_entity_decode($book['PUBLISHER']));
?>
    <div class="bk-card">
        <span class="book-card-num"></span>
        <div class="bk-img">
            <a href="/library/book/<?= $contentId ?>">
                <img src="/cms-data/book-covers/<?= $cover ?>" alt="<?= $title ?>">
            </a>
        </div>
        <div class="bk-details">
            <p class="bk-heading-md">
                <?= $shortTitle ?>
            </p>
            <p class="bk-text-meta">
                Published by: <a class="text-muted" href="/creators/creator/<?= $userId ?>"><?= $publisher ?></a>
            </p>
        </div>
    </div>
<?php endforeach; ?>