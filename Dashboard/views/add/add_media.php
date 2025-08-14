<?php
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/../includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/../includes/layouts/side-bar.php"; ?>

                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">

                    <div>
                        <?php

                        if (isset($_GET["id"]) && !empty($_GET["id"])) {
                            renderHeading("Manage Media", "You can manage, add or delete your media uploads.");
                        } else {
                            renderHeading("Publish Media", "You can manage, add or delete your media uploads.");
                        }

                        include __DIR__ . "/../includes/layouts/forms/media_form.php";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/scripts.php" ?>
</body>

</html>