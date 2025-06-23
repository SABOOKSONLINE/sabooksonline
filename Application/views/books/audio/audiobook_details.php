<?php
$contentId = strtolower($book['USERID']);
$cover = htmlspecialchars($book['COVER']);
$title = htmlspecialchars($book['TITLE']);
$category = htmlspecialchars($book['CATEGORY']);
$publisher = ucwords(htmlspecialchars($book['PUBLISHER']));
$authors = htmlspecialchars($book['AUTHORS']);
$description = htmlspecialchars($book['DESCRIPTION']);
$isbn = htmlspecialchars($book['ISBN']);
$website = htmlspecialchars($book['WEBSITE']);
$retailPrice = htmlspecialchars($book['RETAILPRICE']);

$bookId = $_GET['q'] ?? null;

// AUDIOBOOK SECTION
$releaseDate = !empty($book['a_release_date']) ? htmlspecialchars($book['a_release_date']) : 'N/A';
$narrator = !empty($book['a_narrator']) ? htmlspecialchars($book['a_narrator']) : 'N/A';
$audioDuration = !empty($book['a_duration_minutes']) ? (int)$book['a_duration_minutes'] . ' mins' : 'N/A';
$audiobookId = $book['a_id'] ?? null;

?>

<style>
    .audio-cover::before {
        background: url("/cms-data/book-covers/<?= $cover ?>");

        background-position: center;
        background-size: cover;
    }

    .audio-controllers::before {
        background: url("/cms-data/book-covers/<?= $cover ?>");

        background-position: center;
        background-size: cover;
    }
</style>
<div class="audio-cover">
    <div class="audio-img">
        <img src="/cms-data/book-covers/<?= $cover ?>" alt="Book Cover" />
    </div>
</div>
<div class="audio-book-list">
    <div class="audio-book-title">
        <h3 class="title"><?= $title ?></h3>
        <?php if ($authors): ?>
            <p class="mb-1 text-capitalize">
                <span class="text-muted">Author/s:</span>
                <span class="fw-semibold"><?= $authors ?></span>
            </p>
        <?php endif; ?>

        <p class="mb-1 text-capitalize">
            <span class="text-muted">Narrator:</span>
            <span class="fw-semibold"><?= $narrator ?></span>
        </p>

        <p class="mb-2 text-capitalize">
            <span class="text-muted">Genre:</span>
            <a href="/library?category=<?= $category ?>" class="fw-semibold"><?= $category ?></a>
        </p>
    </div>
    <div class="audio-chapters">
        <?php foreach ($audiobookChapters as $chapter): ?>
            <div class="chapter" audio_url="<?= $chapter['audio_url'] ?>">
                <span><?= $chapter['chapter_number'] ?> - <?= $chapter['chapter_title'] ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>