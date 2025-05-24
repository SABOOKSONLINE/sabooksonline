<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../controllers/UserController.php";

include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/../views/includes/nav.php" ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>

                <div class="col offset-lg-3 offset-xl-2 p-5 hv-100 overflow-y-scroll mt-5">
                    <?php
                    $contentId = $_GET["q"] ?? "";
                    renderHeading("My Profile", "");

                    $userKey = $_SESSION["ADMIN_USERKEY"];

                    $userController = new UserController($conn);
                    $userController->renderUserById($userKey);
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php"; ?>
</body>

</html>