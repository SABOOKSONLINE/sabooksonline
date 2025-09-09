<?php
function renderBookCards($books, $viewOnly = false, $section = "")
{
?>
    <div class="row position-relative">
        <div class="scroll-row-wrapper">
            <div class="scroll-row p-2">
                <?php foreach ($books as $book): ?>
                    <div class="card shadow-sm rounded-4 h-100 card-wrapper" style="width: 202px; flex: 0 0 auto;">
                        <?php if (!$viewOnly): ?>
                            <a href="/admin/process?book=<?= $book["CONTENTID"] ?>&type=delete&return=<?= $_SERVER["REQUEST_URI"] ?>" class="remove-btn text-decoration-none" title="Remove">
                                Remove
                            </a>
                        <?php endif ?>

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
                <?php if (!$viewOnly): ?>
                    <button
                        id="books_table_<?= $section ?>"
                        class="books_table_open btn btn-dark rounded-circle d-flex align-items-center justify-content-center 
           position-absolute top-50 end-0 translate-middle-y me-3"
                        style="width: 50px; height: 50px; z-index: 2;"
                        data-bs-toggle="modal"
                        data-bs-target="#modal_<?= str_replace(' ', '_', $section) ?>"
                        type="button">
                        <i class="fas fa-plus"></i>
                    </button>
                <?php endif ?>
            </div>

            <?php if (count($books) >= 4): ?>
                <div id="scrollHint" class="scroll-hint d-flex align-items-center text-muted justify-content-end mt-2">
                    <i class="fas fa-angle-left me-2"></i>
                    <span>Scroll to see more books</span>
                    <i class="fas fa-angle-right ms-2"></i>
                </div>
            <?php endif ?>
        </div>
    </div>
<?php
}
?>