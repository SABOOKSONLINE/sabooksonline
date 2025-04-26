<?php

function paginatedBooks($books)
{
    $book_pages = [];
    $page = 1;

    $book_pages[$page] = [];

    foreach ($books as $book) {
        if (count($book_pages[$page]) === 18) {
            $page += 1;
            $book_pages[$page] = [];
        }

        $book_pages[$page][] = $book;
    }

    return $book_pages;
}

function booksByPage($books, $page)
{
    foreach (paginatedBooks($books)[$page] as $book) {
        echo '
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
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
    }
}

$page = $_GET['page'] ?? null;

if (!isset($_GET['page'])) {
    $page = 1;
}

booksByPage($books, $page);
