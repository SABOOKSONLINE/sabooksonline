<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/layout/sectionHeading.php";

require __DIR__ . "/../../database/connection.php";
require __DIR__ . "/../models/CartModel.php";
require __DIR__ . "/../controllers/CartController.php";
?>

<body>
    <?php
    require_once __DIR__ . "/includes/nav.php";
    ?>

    <section class="section">
        <div class="container">
            <?php renderSectionHeading("Cart", "", "", "") ?>

            <div class="row">
                <?php
                $controller = new CartController($conn);
                $controller->renderCartItems($_SESSION['ADMIN_ID']);
                ?>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . "/includes/payfast.php" ?>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
</body>