<?php

function paginatedListsOfBooks($books)
{
    $book_pages = [];
    $page = 1;
    $book_pages[$page] = [];

    foreach ($books as $book) {
        if (count($book_pages[$page]) === 18) {
            $page++;
            $book_pages[$page] = [];
        }
        $book_pages[$page][] = $book;
    }

    return $book_pages;
}

function bookPagination($books)
{
    $pages = count(paginatedListsOfBooks($books));
    $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

    $prev = $currentPage - 1;
    $next = $currentPage + 1;
    $query = http_build_query(array_merge($_GET, ['page' => ''])); // Keep all query parameters except page

    echo '<ul class="pagination justify-content-center">';

    if ($currentPage > 1) {
        echo '<li class="page-item"><a class="page-link" href="?' . str_replace('page=', 'page=' . $prev, $query) . '">Previous</a></li>';
    }

    if ($currentPage > 2) {
        echo '<li class="page-item"><a class="page-link" href="?' . str_replace('page=', 'page=1', $query) . '">1</a></li>';
        if ($currentPage > 3) {
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    for ($i = max(1, $currentPage - 1); $i <= min($pages, $currentPage + 1); $i++) {
        $active = ($i === $currentPage) ? ' active' : '';
        echo '<li class="page-item' . $active . '"><a class="page-link" href="?' . str_replace('page=', 'page=' . $i, $query) . '">' . $i . '</a></li>';
    }

    if ($currentPage < $pages - 1) {
        if ($currentPage < $pages - 2) {
            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        echo '<li class="page-item"><a class="page-link" href="?' . str_replace('page=', 'page=' . $pages, $query) . '">' . $pages . '</a></li>';
    }

    if ($currentPage < $pages) {
        echo '<li class="page-item"><a class="page-link" href="?' . str_replace('page=', 'page=' . $next, $query) . '">Next</a></li>';
    }

    echo '</ul>';
}

bookPagination($books);
