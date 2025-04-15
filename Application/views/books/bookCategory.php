<?php
foreach ($books as $book) {
    echo '
<div class="book-card">
    <span class="book-card-num"></span>
    <a class="book-card-cover" href="/library/book/' . strtolower($book['CONTENTID']) . '">
        <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'] . '" alt="' . strtolower($book['TITLE']) . '">
    </a>
    <div class="book-card-info">
        <a class="book-card-little" href="/library/book/' . strtolower($book['CONTENTID']) . '">
            ' . (strlen($book['TITLE']) > 30 ? substr($book['TITLE'], 0, 30) . '...' : $book['TITLE']) . '
        </a>
        <span class="book-card-pub">
            Published by: <a class="text-muted" href="/creators/creator/' . strtolower($book['USERID']) . '">' . ucwords($book['PUBLISHER']) . '</a>
        </span>
    </div>
</div>
    ';
};
