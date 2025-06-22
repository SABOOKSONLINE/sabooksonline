<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// If $audiobook is an array of rows (more than 1), get the first one:
if (isset($audiobook[0])) {
    $audioDetails = $audiobook[0];
} else {
    $audioDetails = $audiobook; // fallback if only one row or empty
}

$audiobookId = $audioDetails['audiobook_id'] ?? '';
$narrator = html_entity_decode($audioDetails['narrator'] ?? '');
$releaseDate = html_entity_decode($audioDetails['release_date'] ?? '');
?>


<form method="POST"
    action="<?= $audiobook ? "/dashboards/listings/updateAudio/$bookId" : "/dashboards/listings/insertAudio/" ?>"
    class="bg-white rounded mb-4 overflow-hidden position-relative"
    enctype="multipart/form-data">

    <div class="card border-0 shadow-sm p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-bold mb-3">Audiobook Information</h5>
            <?php if (!empty($audiobookId)): ?>
                <a href="/dashboards/listings/deleteAudio/<?= $bookId ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this audiobook?');">
                    Delete
                </a>
            <?php endif; ?>
        </div>
        <div class="row">
            <input type="text" class="form-control" name="content_id" value="<?= $contentId ?>" hidden>
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
if (!empty($audiobook)) {
    foreach ($audiobook as $audiobookChapter) {
        include __DIR__ . '/audiobook_chapter_form.php';
    }
}

if (count($audiobook) > 0) {
    $audiobookChapter = [];
    include __DIR__ . '/audiobook_chapter_form.php';
}
?>