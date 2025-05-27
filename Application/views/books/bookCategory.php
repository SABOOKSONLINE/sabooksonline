<?php foreach ($books as $book):
    $contentId = strtolower($book['CONTENTID']);
    $cover = html_entity_decode($book['COVER']);
    $title = html_entity_decode($book['TITLE']);
    $shortTitle = html_entity_decode(strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title);
    $userId = html_entity_decode(strtolower($book['USERID']));
    $publisher = ucwords(html_entity_decode($book['PUBLISHER']));
?>
    <div class="book-card">
        <span class="book-card-num"></span>
        <a class="book-card-cover" href="/library/book/<?= $contentId ?>">
            <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>" alt="<?= $title ?>">
        </a>
        <div class="book-card-info">
            <a class="book-card-little" href="/library/book/<?= $contentId ?>">
                <?= $shortTitle ?>
            </a>
            <span class="book-card-pub">
                Published by: <a class="text-muted" href="/creators/creator/<?= $userId ?>"><?= $publisher ?></a>
            </span>
        </div>
    </div>
<?php endforeach; ?>