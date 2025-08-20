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

    <div class="jumbotron jumbotron-md">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4"><b>SABO Media</b> Hub</h1>
                <p class="lead mb-4">Read. Discover. Stay Informed.</p>
            </div>
        </div>
    </div>

    <?php
    include __DIR__ . "/media/mCard.php";
    include __DIR__ . "/media/nCard.php";

    include __DIR__ . "/includes/footer.php";
    include __DIR__ .  "/includes/scripts.php";
    ?>
</body>