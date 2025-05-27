<h5 class="mt-3">Search results for: <strong><?= html_entity_decode($keyword) ?></strong></h5>

<?php if (empty($books)): ?>
    <p>No results found.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($books as $book):
            $contentId = strtolower(html_entity_decode($book['CONTENTID']));
            $cover = html_entity_decode($book['COVER']);
            $title = html_entity_decode($book['TITLE']);
            $description = html_entity_decode($book['DESCRIPTION']);
            $userId = strtolower(html_entity_decode($book['USERID']));
            $publisher = ucwords(html_entity_decode($book['PUBLISHER']));
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
                            <?= strlen($title) > 30 ? html_entity_decode(substr($title, 0, 30)) . '...' : $title ?>
                        </a>
                        <p>
                            <?= strlen($description) > 125 ? html_entity_decode(substr($description, 0, 125)) . '...' : $description ?>
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