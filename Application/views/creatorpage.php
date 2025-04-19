<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="jumbotron jumbotron-sm">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
        </div>
    </div>

    <div class="container pt-4 pb-5">
        <?php
        require __DIR__ . "/../../database/connection.php";
        require_once __DIR__ . "/../models/CreatorModel.php";
        require_once __DIR__ . "/../controllers/CreatorController.php";

        $controller = new CreatorController($conn);
        $controller->renderCreatorView();
        ?>

        <div class="row" id="book_collection">
            <h1 class="fw-bold py-3">Book Collection:</h1>

            <?php
            require_once __DIR__ . "/../models/Book.php";
            require_once __DIR__ . "/../controllers/BookController.php";

            $bookController = new BookController($conn);
            $bookController->renderBooksByPublisher($_GET['q']);
            ?>
        </div>

    </div>


    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>