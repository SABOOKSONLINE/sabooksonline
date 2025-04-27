<?php

function paginatedListsOfBooks($books)
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

function bookPagination($books)
{
    $pages = count(paginatedListsOfBooks($books));

    if (!isset($_GET['page'])) {
        echo '<li class="page-item"><a class="page-link" href="">Previous</a></li>';
    }

    for ($i = 1; $i <= $pages; $i++) {
        echo '
            <li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>
        ';
    }

    if (!$_GET['page'] == $pages) {
        echo '<li class="page-item"><a class="page-link" href="">Next</a></li>';
    }

    // foreach ( as $book) {



    // echo '
    //     <li class="page-item"><a class="page-link" href="">Previous</a></li>
    //     <li class="page-item"><a class="page-link" href="#">1</a></li>
    //     <li class="page-item"><a class="page-link" href="#">2</a></li>
    //     <li class="page-item"><a class="page-link" href="#">3</a></li>
    //     <li class="page-item"><a class="page-link" href="">Next</a></li>
    // ';
    // }
}

bookPagination($books);
