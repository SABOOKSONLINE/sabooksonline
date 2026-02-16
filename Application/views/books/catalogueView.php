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
        $bookPath = "";
        if (isset($book['CONTENTID'])) {
            $contentId = strtolower(html_entity_decode($book['CONTENTID'] ?? ''));
            $cover = html_entity_decode($book['COVER'] ?? '');
            $title = html_entity_decode($book['TITLE'] ?? '');
            $category = html_entity_decode($book['CATEGORY'] ?? '');
            $description = html_entity_decode($book['DESCRIPTION'] ?? '');
            $userId = strtolower(html_entity_decode($book['USERID'] ?? ''));
            $publisher = ucwords(html_entity_decode($book['PUBLISHER'] ?? ''));

            $coverPath = "/cms-data/book-covers/$cover";
            $bookPath = "/library/book/$contentId";
        } else {
            $contentId = strtolower(html_entity_decode($book['public_key'] ?? ''));
            $cover = html_entity_decode($book['cover_image_path'] ?? '');
            $title = html_entity_decode($book['title'] ?? '');
            $category = html_entity_decode($book['subject'] ?? '');
            $description = html_entity_decode($book['description'] ?? '');

            $userId = $book['ADMIN_USERKEY'] ?? '';
            $publisher = $book['ADMIN_NAME'] ?? '';

            if (!empty($cover)) {
                $coverPath = "/cms-data/academic/covers/$cover";
            } else {
                // Use placeholder if no cover image - SA Flag colors with blur effect
                $coverPath = "data:image/svg+xml;base64," . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="300" viewBox="0 0 200 300"><defs><filter id="blur"><feGaussianBlur in="SourceGraphic" stdDeviation="8"/></filter><linearGradient id="redGrad" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#DE3831;stop-opacity:0.9"/><stop offset="100%" style="stop-color:#DE3831;stop-opacity:0.6"/></linearGradient><linearGradient id="blueGrad" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#002395;stop-opacity:0.6"/><stop offset="100%" style="stop-color:#002395;stop-opacity:0.9"/></linearGradient><linearGradient id="greenGrad" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#007A4D;stop-opacity:0.8"/><stop offset="100%" style="stop-color:#007A4D;stop-opacity:0.4"/></linearGradient><linearGradient id="yellowGrad" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#FFB612;stop-opacity:0.4"/><stop offset="100%" style="stop-color:#FFB612;stop-opacity:0.8"/></linearGradient></defs><rect width="200" height="300" fill="#FFFFFF"/><rect width="200" height="100" fill="url(#redGrad)" filter="url(#blur)"/><rect y="200" width="200" height="100" fill="url(#blueGrad)" filter="url(#blur)"/><polygon points="0,0 0,300 100,150" fill="url(#greenGrad)" filter="url(#blur)"/><polygon points="200,0 200,300 100,150" fill="url(#yellowGrad)" filter="url(#blur)"/><polygon points="0,0 200,0 100,150" fill="#000000" opacity="0.3" filter="url(#blur)"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14" fill="#FFFFFF" text-anchor="middle" dominant-baseline="middle" font-weight="bold" opacity="0.9">No Cover</text></svg>');
            }
            $bookPath = "/library/academic/$contentId";
        }

        // Check if regular book cover exists
        if (isset($book['CONTENTID']) && empty($cover)) {
            $coverPath = "data:image/svg+xml;base64," . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="300" viewBox="0 0 200 300"><defs><filter id="blur"><feGaussianBlur in="SourceGraphic" stdDeviation="8"/></filter><linearGradient id="redGrad" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#DE3831;stop-opacity:0.9"/><stop offset="100%" style="stop-color:#DE3831;stop-opacity:0.6"/></linearGradient><linearGradient id="blueGrad" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" style="stop-color:#002395;stop-opacity:0.6"/><stop offset="100%" style="stop-color:#002395;stop-opacity:0.9"/></linearGradient><linearGradient id="greenGrad" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#007A4D;stop-opacity:0.8"/><stop offset="100%" style="stop-color:#007A4D;stop-opacity:0.4"/></linearGradient><linearGradient id="yellowGrad" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:#FFB612;stop-opacity:0.4"/><stop offset="100%" style="stop-color:#FFB612;stop-opacity:0.8"/></linearGradient></defs><rect width="200" height="300" fill="#FFFFFF"/><rect width="200" height="100" fill="url(#redGrad)" filter="url(#blur)"/><rect y="200" width="200" height="100" fill="url(#blueGrad)" filter="url(#blur)"/><polygon points="0,0 0,300 100,150" fill="url(#greenGrad)" filter="url(#blur)"/><polygon points="200,0 200,300 100,150" fill="url(#yellowGrad)" filter="url(#blur)"/><polygon points="0,0 200,0 100,150" fill="#000000" opacity="0.3" filter="url(#blur)"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14" fill="#FFFFFF" text-anchor="middle" dominant-baseline="middle" font-weight="bold" opacity="0.9">No Cover</text></svg>');
        }

?>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="library-book-card">
                <div class="library-book-card-img">
                    <a href="<?= $bookPath ?>">
                        <img src="<?= $coverPath ?>" alt="<?= htmlspecialchars($title) ?>" 
                             onerror="this.onerror=null; this.src='data:image/svg+xml;base64,<?= base64_encode('<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"200\" height=\"300\" viewBox=\"0 0 200 300\"><defs><filter id=\"blur\"><feGaussianBlur in=\"SourceGraphic\" stdDeviation=\"8\"/></filter><linearGradient id=\"redGrad\" x1=\"0%\" y1=\"0%\" x2=\"0%\" y2=\"100%\"><stop offset=\"0%\" style=\"stop-color:#DE3831;stop-opacity:0.9\"/><stop offset=\"100%\" style=\"stop-color:#DE3831;stop-opacity:0.6\"/></linearGradient><linearGradient id=\"blueGrad\" x1=\"0%\" y1=\"0%\" x2=\"0%\" y2=\"100%\"><stop offset=\"0%\" style=\"stop-color:#002395;stop-opacity:0.6\"/><stop offset=\"100%\" style=\"stop-color:#002395;stop-opacity:0.9\"/></linearGradient><linearGradient id=\"greenGrad\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"0%\"><stop offset=\"0%\" style=\"stop-color:#007A4D;stop-opacity:0.8\"/><stop offset=\"100%\" style=\"stop-color:#007A4D;stop-opacity:0.4\"/></linearGradient><linearGradient id=\"yellowGrad\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"0%\"><stop offset=\"0%\" style=\"stop-color:#FFB612;stop-opacity:0.4\"/><stop offset=\"100%\" style=\"stop-color:#FFB612;stop-opacity:0.8\"/></linearGradient></defs><rect width=\"200\" height=\"300\" fill=\"#FFFFFF\"/><rect width=\"200\" height=\"100\" fill=\"url(#redGrad)\" filter=\"url(#blur)\"/><rect y=\"200\" width=\"200\" height=\"100\" fill=\"url(#blueGrad)\" filter=\"url(#blur)\"/><polygon points=\"0,0 0,300 100,150\" fill=\"url(#greenGrad)\" filter=\"url(#blur)\"/><polygon points=\"200,0 200,300 100,150\" fill=\"url(#yellowGrad)\" filter=\"url(#blur)\"/><polygon points=\"0,0 200,0 100,150\" fill=\"#000000\" opacity=\"0.3\" filter=\"url(#blur)\"/><text x=\"50%\" y=\"50%\" font-family=\"Arial, sans-serif\" font-size=\"14\" fill=\"#FFFFFF\" text-anchor=\"middle\" dominant-baseline=\"middle\" font-weight=\"bold\" opacity=\"0.9\">No Cover</text></svg>') ?>';">
                    </a>
                </div>
                <div class="w-100">
                    <a class="book-card-little text-capitalize" href="<?= $bookPath ?>">
                        <?= strlen($title) > 15 ? substr($title, 0, 15) . '...' : $title ?>
                    </a>
                    <p>
                        <?= strlen($description) > 85 ? (substr($description, 0, 85)) . '...' : $description ?>
                    </p>
                    <?php if (isset($book['CONTENTID'])): ?>
                        <span class="book-card-pub">
                            Published by: <a class="text-muted" href="/creators/creator/<?= $userId ?>"><?= $publisher ?></a>
                        </span> <br>
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