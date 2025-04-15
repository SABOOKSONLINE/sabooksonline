<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <?php
    require_once __DIR__ . "/../../database/connection.php";
    require_once __DIR__ . "/../models/CreatorModel.php";
    require_once __DIR__ . "/../controllers/CreatorController.php";
    creatorViewRender($conn, $contentId);
    ?>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>