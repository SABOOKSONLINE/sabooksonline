<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$audiobook = $audiobook ?? [];

$audiobookId = $audiobook['audiobook_id'] ?? '';
$bookid = $audiobook['book_id'] ?? '';

$narrator = $audiobook['narrator'] ?? '';
$releaseDate = $audiobook['release_date'] ?? '';
?>

<form method="POST"
    action="<?= $bookid ? "/dashboards/listings/updateAudio/$bookid" : "/dashboards/listings/insertAudio" ?>"
    class="bg-white rounded mb-4 overflow-hidden position-relative"
    enctype="multipart/form-data">

    <div class="card border-0 shadow-sm p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-bold mb-3">Audiobook Information</h5>
            <?php if (!empty($audiobookId)): ?>
                <a href="/dashboards/listings/deleteAudio/<?= $bookid ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this audiobook?');">
                    Delete
                </a>
            <?php endif; ?>
        </div>
        <div class="row">
            <input type="text" class="form-control" name="book_id" value="<?= $bookId ?>" hidden>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Narrator*</label>
                    <input type="text" class="form-control" name="audiobook_narrator" value="<?= $narrator ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Release Date*</label>
                    <input type="date" class="form-control" name="release_date" value="<?= $releaseDate ?>" required>
                </div>
            </div>

            <div class="mt-2">
                <?php if (!empty($audiobook)): ?>
                    <button class="btn btn-success" type="submit">Update Audiobook</button>
                <?php else: ?>
                    <button class="btn btn-success" type="submit">Save Audiobook</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<?php
// Only show the chapter form if the audiobook was saved (i.e. a valid book ID exists)
if ($bookid):
    include __DIR__ . '/audiobook_chapter_form.php';
endif;
?>