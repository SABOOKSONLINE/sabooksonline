<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../models/BookModel.php";
require_once __DIR__ . "/../controllers/BookController.php";

$controller = new BookController($conn);
?>

<body class="justify-content-between">
    <?php require_once __DIR__ . "/includes/nav.php"; ?>


    <div class="container py-4">
        <h1 class="fw-bold mb-0">Academic Books</h1>
        <span class="text-muted">Explore a world of knowledge and learning</span>

        <div class="row py-3">
            
        </div>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php"; ?>
    <?php require_once __DIR__ . "/includes/scripts.php"; ?>
</body>