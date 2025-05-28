<?php
$chapter = $audiobook ?? [];
$chapterId = $audiobook['chapter_id'] ?? '';

$chapterNumber = $audiobook['chapter_number'] ?? '';
$chapterTitle = $audiobook['chapter_title'] ?? '';
$audioUrl = $audiobook['audio_url'] ?? '';
$durationMinutes = $audiobook['duration_minutes'] ?? '';
?>
<form method="POST"
    action="<?= $chapterId ? "/dashboards/listings/updateAudioChapter/$chapterId" : "/dashboards/listings/insertAudioChapter" ?>"
    class="bg-white rounded mb-4 overflow-hidden position-relative"
    enctype="multipart/form-data">

    <input type="hidden" name="content_id" value="<?= $contentId ?>">
    <input type="hidden" name="audiobook_id" value="<?= $audiobookId ?>">
    <input type="hidden" name="chapter_id" value="<?= $chapterId ?>">

    <div class="card border-0 shadow-sm p-4 mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-bold mb-3">Audiobook Chapter Information</h5>
            <?php if (!empty($chapterId)): ?>
                <a href="/dashboards/listings/deleteAudioChapter/<?= $chapterId ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this chapter?');">
                    Delete
                </a>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Chapter Number*</label>
                    <input type="number" class="form-control" name="chapter_number" value="<?= $chapterNumber ?>" required>
                </div>
            </div>

            <div class="col-sm-10">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Chapter Title*</label>
                    <input type="text" class="form-control" name="chapter_title" value="<?= $chapterTitle ?>" required>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Audio File*</label>
                    <input type="file" class="form-control" name="audio_url" accept=".mp3" <?= empty($audioUrl) ? 'required' : '' ?>>
                    <?php if (!empty($audioUrl)): ?>
                        <div class="mt-2">
                            <label class="form-label fw-semibold">Current Audio:</label> <br>
                            <audio controls>
                                <source src="/cms-data/audiobooks/<?= $audioUrl ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            <input type="hidden" name="audio_url" value="<?= $audioUrl ?>">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 mt-2">
                <?php if (!empty($chapterId)): ?>
                    <button class="btn btn-success" type="submit">Update Chapter</button>
                <?php else: ?>
                    <button class="btn btn-success" type="submit">Add Chapter</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>