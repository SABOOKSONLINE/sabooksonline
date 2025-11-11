<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/../Config/connection.php";
require_once __DIR__ . "/../models/MediaModel.php";
require_once __DIR__ . "/../controllers/MediaController.php";

require_once __DIR__ . "/layout/sectionHeading.php";

$controller = new MediaController($conn);

$magazines = $controller->getAllMagazines();
$newspapers = $controller->getAllNewspapers();

// echo "<pre>";
// print_r($magazines);
// echo "</pre>";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php";
    ?>

    <?php
    include __DIR__ . "/media/mCard.php";
    require_once __DIR__ . "/includes/mobile.php" ;

    include __DIR__ . "/media/nCard.php";
    require_once __DIR__ . "/includes/payfast.php" ;

    include __DIR__ . "/includes/footer.php";
    include __DIR__ .  "/includes/scripts.php";
    ?>
</body>