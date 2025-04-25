<?php
// echo $_SERVER["REQUEST_URI"];
foreach ($books as $book) {
    echo '
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4 gap-3">
            <div class="library-book-card">
                <div class="library-book-card-img">
                    <a href="/library/book/' . strtolower($book['CONTENTID']) . '">
                        <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'] . '" alt="' . strtolower($book['TITLE']) . '">
                    </a>
                </div>
                <div class="w-100">
                    <a class="book-card-little text-capitalize" href="/library/book/' . strtolower($book['CONTENTID']) . '">
                        ' . (strlen($book['TITLE']) > 30 ? substr($book['TITLE'], 0, 30) . '...' : $book['TITLE']) . '
                    </a>
                    <p>' . (strlen($book['DESCRIPTION']) > 125 ? substr($book['DESCRIPTION'], 0, 125) . '...' : $book['DESCRIPTION']) . '</p>
                    <span class="book-card-pub">
                        Published by: <a class="text-muted" href="/creators/creator/' . strtolower($book['USERID']) . '">' . ucwords($book['PUBLISHER']) . '</a>
                    </span>
                </div>
            </div>
        </div>
    ';
};
