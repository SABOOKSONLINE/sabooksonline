<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="container">
        <?php
        require_once __DIR__ . "/../Config/connection.php";
        require_once __DIR__ . "/../models/EventModel.php";
        require_once __DIR__ . "/../controllers/EventController.php";

        $controller = new EventController($conn);
        $controller->renderEventByContentId();
        ?>
    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>