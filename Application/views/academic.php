<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/AcademicBookModel.php";
require_once __DIR__ . "/../controllers/AcademicBookController.php";

$controller = new AcademicBookController($conn);
?>

<body class="justify-content-between">
    <?php require_once __DIR__ . "/includes/nav.php"; ?>


    <div class="container py-4">
        <h1 class="fw-bold mb-0">Academic Books</h1>
        <span class="text-muted">Explore a world of knowledge and learning</span>

        <br class="my-3">

        <div class="row py-3">
            <?php
            $books = $controller->getAllBooks();
            // echo "<pre>";
            // print_r($books);
            // echo "</pre>";

            require_once __DIR__ . "/books/catalogueView.php";
            ?>
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php"; ?>
    <?php require_once __DIR__ . "/includes/scripts.php"; ?>
</body>