<?php
function renderBookCards($books)
{
?>
    <div class="scroll-row-wrapper">
        <div class="scroll-row p-2">
            <?php foreach ($books as $book): ?>
                <div class="card shadow-sm rounded-4 h-100 card-wrapper" style="width: 202px; flex: 0 0 auto;">
                    <a href="" class="remove-btn text-decoration-none" title="Remove">
                        Remove
                    </a>

                    <a href="/library/book/<?= $book["CONTENTID"] ?>" target="_blank" class="ratio-container">
                        <img src="/cms-data/book-covers/<?= $book["COVER"] ?>"
                            class="card-img-top rounded-top-4"
                            alt="<?= htmlspecialchars($book["TITLE"]) ?>">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title mb-1 text-truncate">
                            <?= htmlspecialchars($book["TITLE"]) ?>
                        </h5>
                        <p class="card-text text-muted mb-0">
                            Published by:
                            <a href="/creators/creator/<?= $book["USERID"] ?>"
                                class="text-decoration-none text-muted"
                                target="_blank">
                                <?= htmlspecialchars($book["PUBLISHER"]) ?>
                            </a>
                        </p>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div id="scrollHint" class="scroll-hint d-flex align-items-center text-muted">
            <i class="fas fa-angle-left me-2"></i>
            <span>Scroll to see more books</span>
            <i class="fas fa-angle-right ms-2"></i>
        </div>
    </div>
<?php
}
?>