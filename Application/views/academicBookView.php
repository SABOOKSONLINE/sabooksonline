<?php
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/layout/sectionHeading.php";
?>

<body class="d-flex flex-column min-vh-100">
    <?php
    require_once __DIR__ . "/includes/nav.php";
    require_once __DIR__ . "/../Config/connection.php";
    require_once __DIR__ . "/../models/AcademicBookModel.php";
    require_once __DIR__ . "/../controllers/AcademicBookController.php";

    $controller = new AcademicBookController($conn);
    $publicKey = $_GET["publicKey"];
    $book = $controller->getBookById($publicKey);

    // echo "<pre>";
    // print_r($book);
    // echo "</pre>";

    require_once __DIR__ . "/books/academicView.php";
    ?>

    <?php
    require_once __DIR__ . "/includes/payfast.php";
    require_once __DIR__ . "/includes/footer.php";
    require_once __DIR__ .  "/includes/scripts.php";
    ?>
</body>