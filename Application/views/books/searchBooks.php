<h5 class="mt-3">Search results for: <strong><?= htmlspecialchars($keyword) ?></strong></h5>

<?php if (empty($books)): ?>
    <p>No results found.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($books as $book): 
            $contentId = strtolower(htmlspecialchars($book['CONTENTID']));
            $cover = htmlspecialchars($book['COVER']);
            $title = htmlspecialchars($book['TITLE']);
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
                            <?= strlen($title) > 30 ? htmlspecialchars(substr($title, 0, 30)) . '...' : $title ?>
                        </a>
                        <p>
                            <?= strlen($description) > 125 ? htmlspecialchars(substr($description, 0, 125)) . '...' : $description ?>
                        </p>
                        <span class="book-card-pub">
                            Published by: <a class="text-muted" href="/creators/creator/<?= $userId ?>"><?= $publisher ?></a>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
