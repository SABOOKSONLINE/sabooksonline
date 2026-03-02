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

                    // Get filter parameters from GET request
                    $filters = [
                        'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
                        'status' => isset($_GET['status']) ? trim($_GET['status']) : '',
                        'subject' => isset($_GET['subject']) ? trim($_GET['subject']) : '',
                        'min_price' => isset($_GET['min_price']) ? trim($_GET['min_price']) : '',
                        'max_price' => isset($_GET['max_price']) ? trim($_GET['max_price']) : '',
                        'date_from' => isset($_GET['date_from']) ? trim($_GET['date_from']) : '',
                        'date_to' => isset($_GET['date_to']) ? trim($_GET['date_to']) : '',
                        'sort' => isset($_GET['sort']) ? trim($_GET['sort']) : ''
                    ];
                    
                    $academicBooksController = new AcademicBookController($conn);
                    $books = $academicBooksController->getAllBooks($userId, $filters);
                    
                    // Get filter options
                    $filterData = $academicBooksController->getFilterData($userId);
                    $filters['subjects'] = $filterData['subjects'];
                    $filters['price_range'] = $filterData['price_range'];

                    include __DIR__ . "/includes/layouts/tables/academic_table.php";
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php" ?>
</body>

</html>