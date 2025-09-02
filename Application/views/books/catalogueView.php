<?php

require_once __DIR__ . "/../util/helpers.php";

function booksByPage($books, $page)
{
    $paginatedBooks = paginatedBooks($books);

    if (!isset($paginatedBooks[$page])) {
        echo "<p>No books found on this page.</p>";
        return;
    }

    foreach ($paginatedBooks[$page] as $book) {
        $coverPath = "";
        if (isset($book['CONTENTID'])) {
            $contentId = strtolower(html_entity_decode($book['CONTENTID']));
            $cover = html_entity_decode($book['COVER']);
            $title = html_entity_decode($book['TITLE']);
            $category = html_entity_decode($book['CATEGORY']);
            $description = html_entity_decode($book['DESCRIPTION']);
            $userId = strtolower(html_entity_decode($book['USERID']));
            $publisher = ucwords(html_entity_decode($book['PUBLISHER']));

            $coverPath = "/cms-data/book-covers/$cover";
        } else {
            $contentId = strtolower(html_entity_decode($book['public_key']));
            $cover = html_entity_decode($book['cover_image_path']);
            $title = html_entity_decode($book['title']);
            $category = html_entity_decode($book['subject']);
            $description = html_entity_decode($book['description']);
            // $userId = strtolower(html_entity_decode($book['publisher_id']));

            $userId = $book['ADMIN_USERKEY'];
            $publisher = $book['ADMIN_NAME'];

            $coverPath = "/cms-data/academic/covers/$cover";
        }

?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="library-book-card">
                <div class="library-book-card-img">
                    <a href="/library/book/<?= $contentId ?>">
                        <img src="<?= $coverPath ?>" alt="<?= strtolower($title) ?>">
                    </a>
                </div>
                <div class="w-100">
                    <a class="book-card-little text-capitalize" href="/library/book/<?= $contentId ?>">
                        <?= strlen($title) > 15 ? substr($title, 0, 15) . '...' : $title ?>
                    </a>
                    <p>
                        <?= strlen($description) > 85 ? (substr($description, 0, 85)) . '...' : $description ?>
                    </p>
                    <span class="book-card-pub">
                        Published by: <a class="text-muted" href="/creators/creator/<?= $userId ?>"><?= $publisher ?></a>
                    </span> <br>
                    <?php if (isset($book['CONTENTID'])): ?>
                        <a href="?category=<?= urlencode($category) ?>" class="category-link d-block m-0 mt-2">
                            <?= $category ?>
                        </a>
                    <?php else: ?>
                        <span class="category-link d-block m-0 mt-2">
                            <?= $category ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>
        </div>
<?php
    }
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
booksByPage($books, $page);
?>