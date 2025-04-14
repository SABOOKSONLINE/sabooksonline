<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <?php
    require_once __DIR__ . "/../../database/connection.php";
    require_once __DIR__ . "/../../database/models/Book.php";

    $book = new Book($conn);
    $contentId = $_GET['q'];
    $books = $book->getBookById($contentId);
    $book->renderBook($books);
    ?>


    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>