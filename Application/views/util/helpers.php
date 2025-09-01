<?php
require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../../models/MediaModel.php";
require_once __DIR__ . "/../../controllers/MediaController.php";

global $mediaController;
$mediaController = new MediaController($conn);

function getPublisherById($id): ?array
{
    global $mediaController;
    return $mediaController->getUserById($id);
}


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
