<?php
function paginatedBooks($books)
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

function saveRedirectPage(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION["redirect_after_login"] = $_SERVER["REQUEST_URI"];
}

// function redirectAfterLogin(): void
// {
//     if (isset($_SESSION["redirect_after_login"])) {
//         $path = $_SESSION["redirect_after_login"];
//     } else {
//         $path = "/dashboards";
//     }

//     header("Location: " . $path);
//     exit;
// }
