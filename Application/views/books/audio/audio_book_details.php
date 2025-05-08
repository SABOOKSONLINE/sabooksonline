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
?>

<style>
    .audio-cover::before {
        background: url("https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>");

        background-position: center;
        background-size: cover;
    }

    .audio-controllers::before {
        background: url("https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>");

        background-position: center;
        background-size: cover;
    }
</style>
<div class="audio-cover">
    <div class="audio-img">
        <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>" alt="Book Cover" />
    </div>
</div>
<div class="audio-book-list">
    <div class="audio-book-title">
        <h2 class="title"><?= $title ?></h2>
        <p class="mb-1 text-capitalize">
            <span class="muted">Author/s:</span>
            <span class="fw-semibold"><?= $authors ?></span>
        </p>
        <p class="mb-3 text-capitalize">
            <span class="muted">Category:</span>
            <a href="/library?category=<?= $category ?>" class="fw-semibold"><?= $category ?></a>
        </p>
    </div>
    <div class="audio-chapters">
        <div class="chapter" audio_url="Gijima.mp3">
            <span>chapter 01</span>
            <!-- <div class="chapter-time">20:00</div> -->
        </div>
        <div class="chapter" audio_url="Inganono.mp3">
            <span>chapter 01</span>
            <!-- <div class="chapter-time">20:00</div> -->
        </div>
        <div class="chapter" audio_url="LUZUKO.mp3">
            <span>chapter 01</span>
            <!-- <div class="chapter-time">20:00</div> -->
        </div>
        <div class="chapter" audio_url="Nguwe.mp3">
            <span>chapter 01</span>
            <!-- <div class="chapter-time">20:00</div> -->
        </div>
        <div class="chapter" audio_url="Sabelo.mp3">
            <span>chapter 01</span>
            <!-- <div class="chapter-time">20:00</div> -->
        </div>
    </div>
</div>