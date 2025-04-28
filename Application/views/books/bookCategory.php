<?php foreach ($books as $book):
    $contentId = strtolower($book['CONTENTID']);
    $cover = htmlspecialchars($book['COVER']);
    $title = htmlspecialchars($book['TITLE']);
    $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
    $userId = strtolower($book['USERID']);
    $publisher = ucwords(htmlspecialchars($book['PUBLISHER']));
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