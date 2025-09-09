<?php
session_start();
require_once __DIR__ . "/sessionAlerts.php";
require_once __DIR__ . "/../Core/Conn.php";

if (!isset($_GET['book'], $_GET['type'], $_GET['return'])) {
    setAlert("warning", "Invalid request parameters!");
    header("Location: /admin");
    exit;
}

require_once __DIR__ . "/../Model/BookModel.php";
require_once __DIR__ . "/../Controller/PagesController.php";

$controller = new PagesController($conn);
$type = $_GET['type'];
$returnUrl = $_GET['return'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && $type === "insert") {
    $publicKey = $_POST["public_key"] ?? '';
    $category = $_POST["category"] ?? '';

    if ($controller->addListing($publicKey, $category)) {
        setAlert("success", "Book has been successfully added to listing!");
    } else {
        setAlert("danger", "Failed to add the book. Please try again!");
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && $type === "delete") {
    if ($controller->deleteListing($_GET['book'])) {
        setAlert("success", "Book has been successfully deleted from the listing!");
    } else {
        setAlert("danger", "Failed to delete the book. Please try again!");
    }
} else {
    setAlert("warning", "Invalid request parameters!");
}

header("Location: $returnUrl");
exit;
