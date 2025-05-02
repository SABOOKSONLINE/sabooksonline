<?php

function paginatedBooks($books)
{
    $book_pages = [];
    $page = 1;
    $book_pages[$page] = [];

    foreach ($books as $book) {
        if (count($book_pages[$page]) === 18) {
            $page++;
            $book_pages[$page] = [];
        }
        $book_pages[$page][] = $book;
    }

    return $book_pages;
}

function booksByPage($books, $page)
{
    $paginatedBooks = paginatedBooks($books);

    if (!isset($paginatedBooks[$page])) {
        echo "<p>No books found on this page.</p>";
        return;
    }

    foreach ($paginatedBooks[$page] as $book) {
        $contentId = strtolower(htmlspecialchars($book['CONTENTID']));
        $cover = htmlspecialchars($book['COVER']);
        $title = htmlspecialchars($book['TITLE']);
        $category = htmlspecialchars($book['CATEGORY']);
        $description = htmlspecialchars($book['DESCRIPTION']);
        $userId = strtolower(htmlspecialchars($book['USERID']));
        $publisher = ucwords(htmlspecialchars($book['PUBLISHER']));

?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="library-book-card">
                <div class="library-book-card-img">
                    <a href="/library/book/<?= $contentId ?>">
                        <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>" alt="<?= strtolower($title) ?>">
                    </a>
                </div>
                <div class="w-100">
                    <a class="book-card-little text-capitalize" href="/library/book/<?= $contentId ?>">
                        <?= strlen($title) > 15 ? htmlspecialchars(substr($title, 0, 15)) . '...' : $title ?>
                    </a>
                    <p>
                        <?= strlen($description) > 85 ? htmlspecialchars(substr($description, 0, 85)) . '...' : $description ?>
                    </p>
                    <span class="book-card-pub">
                        Published by: <a class="text-muted" href="/creators/creator/<?= $userId ?>"><?= $publisher ?></a>
                    </span>
                    <a href="?category=<?= urlencode($category) ?>" class="book-tag">
                        <?= $category ?>
                    </a>
                </div>
            </div>
        </div>
<?php
    }
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
booksByPage($books, $page);
?>