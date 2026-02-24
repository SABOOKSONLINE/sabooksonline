<?php
function renderAcademicCollectionCards($academicListings, $academicAllBooks)
{
?>
    <?php if (!empty($academicListings)): ?>
        <div class="row position-relative">
            <div class="scroll-row-wrapper">
                <div class="scroll-row p-2">
                    <?php foreach ($academicListings as $listing): ?>
                        <div class="card shadow-sm rounded-4 h-100 card-wrapper" style="width: 202px; flex: 0 0 auto;">
                            <a href="/admin/process_academic?id=<?= $listing["id"] ?>&type=delete&return=<?= urlencode($_SERVER["REQUEST_URI"]) ?>" class="remove-btn text-decoration-none" title="Remove">
                                Remove
                            </a>

                            <a href="/library/academic/<?= $listing["CONTENTID"] ?>" target="_blank" class="ratio-container">
                                <img src="/cms-data/academic/covers/<?= $listing["COVER"] ?>"
                                    class="card-img-top rounded-top-4"
                                    alt="<?= htmlspecialchars($listing["TITLE"]) ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title mb-1 text-truncate">
                                    <?= htmlspecialchars($listing["TITLE"]) ?>
                                </h5>
                                <p class="card-text text-muted mb-0">
                                    <small><?= htmlspecialchars($listing["PUBLISHER"]) ?></small>
                                </p>
                            </div>
                        </div>
                    <?php endforeach ?>

                    <button
                        class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center position-absolute top-50 end-0 translate-middle-y me-3"
                        style="width: 50px; height: 50px; z-index: 2;"
                        data-bs-toggle="modal"
                        data-bs-target="#academicCollectionModal"
                        type="button">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <?php if (count($academicListings) >= 4): ?>
                    <div class="scroll-hint d-flex align-items-center text-muted justify-content-end mt-2">
                        <i class="fas fa-angle-left me-2"></i>
                        <span>Scroll to see more books</span>
                        <i class="fas fa-angle-right ms-2"></i>
                    </div>
                <?php endif ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No academic books in collection. Click the button below to add some.
        </div>
        <button
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#academicCollectionModal"
            type="button">
            <i class="fas fa-plus"></i> Add Academic Book
        </button>
    <?php endif;
}

function renderAcademicCollectionModal($academicAllBooks)
{
    ?>
    <div class="modal fade" id="academicCollectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Academic Book to Collection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="/admin/process_academic?type=insert&return=<?= urlencode($_SERVER["REQUEST_URI"]) ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="academicBookSelect" class="form-label">
                                <i class="fas fa-book"></i> Select Academic Book <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="academicBookSelect" name="public_key" required>
                                <option value="">-- Choose a book --</option>
                                <?php foreach ($academicAllBooks as $book): ?>
                                    <option value="<?= htmlspecialchars($book["CONTENTID"]) ?>">
                                        <?= htmlspecialchars($book["TITLE"]) ?> (ID: <?= $book["ID"] ?>)
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <input type="hidden" name="book_id" id="bookIdInput" value="">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add to Collection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookSelect = document.getElementById('academicBookSelect');
            const bookIdInput = document.getElementById('bookIdInput');

            bookSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                // Extract book_id from the option text (ID: X)
                const idMatch = selectedOption.text.match(/ID: (\d+)/);
                if (idMatch) {
                    bookIdInput.value = idMatch[1];
                }
            });
        });
    </script>
<?php
}
?>