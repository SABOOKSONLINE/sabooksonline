<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/AcademicBookModel.php";
require_once __DIR__ . "/../controllers/AcademicBookController.php";

include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>


                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                    <?php
                    renderHeading(
                        "Manage Academic Books",
                        "Easily view, edit, or remove Academic books you’ve published.",
                    );

                    $academicBooksController = new AcademicBookController($conn);
                    $books = $academicBooksController->getAllBooks($userId);

                    // include __DIR__ . "/includes/layouts/tables/academic_table.php";
                    ?>
                    <?php if ($_SESSION['ADMIN_EMAIL'] == "khumalopearl003@gmail.com" || $_SESSION['ADMIN_EMAIL'] == "tebogo@sabooksonline.co.za" || $_SESSION['ADMIN_EMAIL'] == "kganyamilton@gmail.com"): ?>
                        <?php include __DIR__ . "/includes/layouts/tables/academic_table.php"; ?>
                    <?php else: ?>
                        <div class="alert alert-danger" role="alert">
                            You don’t have access to this feature.
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php" ?>
</body>

</html>