<?php
session_start();
require_once __DIR__ . "/sessionAlerts.php";
require_once __DIR__ . "/../Core/Conn.php";

if (!isset($_GET['type'], $_GET['return'])) {
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
    $bookId = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
    $publicKey = trim($_POST['public_key'] ?? '');

    if ($bookId <= 0 || !$publicKey) {
        setAlert("danger", "Please provide both Book ID and Public Key!");
        header("Location: $returnUrl");
        exit;
    }

    if ($controller->addAcademicListing($bookId, $publicKey)) {
        setAlert("success", "Academic listing added successfully!");
    } else {
        setAlert("danger", "Failed to add academic listing. Please try again.");
    }

} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && $type === "update") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $bookId = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
    $publicKey = trim($_POST['public_key'] ?? '');

    if ($id <= 0 || $bookId <= 0 || !$publicKey) {
        setAlert("danger", "Please provide ID, Book ID and Public Key!");
        header("Location: $returnUrl");
        exit;
    }

    if ($controller->updateAcademicListing($id, $bookId, $publicKey)) {
        setAlert("success", "Academic listing updated successfully!");
    } else {
        setAlert("danger", "No changes or update failed. Please try again.");
    }

} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && $type === "delete") {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) {
        setAlert("danger", "Invalid listing ID!");
        header("Location: $returnUrl");
        exit;
    }

    if ($controller->deleteAcademicListing($id)) {
        setAlert("success", "Academic listing deleted successfully!");
    } else {
        setAlert("danger", "Failed to delete academic listing. Please try again.");
    }

} else {
    setAlert("warning", "Invalid request parameters!");
}

header("Location: $returnUrl");
exit;
