<?php
require_once __DIR__ . "/sessionAlerts.php";

// process delete book from listing
// 1. get the following
//      echo $_GET['book'] . "<br>";
//      echo $_GET['type'] . "<br>";
//      echo $_GET['return'] . "<br>";
// 2. if all values are avail - call the controller class responsible for removing books
// 3. if successful - set success session alert
//    else          - set danger session alert 
// 4. redirect to return page

session_start();
require_once __DIR__ . "/../Core/Conn.php";

if (
    isset($_GET['book']) &&
    isset($_GET['type']) && $_GET['type'] === "delete" &&
    isset($_GET['return'])
) {
    require_once __DIR__ . "/../Model/BookModel.php";
    require_once __DIR__ . "/../Controller/PagesController.php";

    $controller = new PagesController($conn);

    if ($controller->deleteListing($_GET['book'])) {
        setAlert("success", "Book has been successfully deleted from the listing!");
    } else {
        setAlert("danger", "Failed to delete the book. Please try again!");
    }

    header("Location: " . $_GET['return']);
    exit;
} else {
    setAlert("warning", "Invalid request parameters!");
    header("Location: /admin");
    exit;
}
