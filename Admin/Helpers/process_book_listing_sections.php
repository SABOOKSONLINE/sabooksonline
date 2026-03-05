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
$type       = $_GET['type'];
$returnUrl  = $_GET['return'];

/** ------------------ ADD BOOK LISTING SECTION ------------------ */
if ($type === "add-book-listing-section") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $sectionName = trim($_POST['section_name'] ?? '');
        $orderIndex  = (int) trim($_POST['order_index'] ?? 0);
        $cardType    = trim($_POST['card_type'] ?? 'standard');

        if (!$sectionName) {
            setAlert("danger", "Section Name is required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($orderIndex <= 0) {
            setAlert("danger", "Section Order must be a positive number!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->addBookListingSection($sectionName, $orderIndex, $cardType)) {
            setAlert("success", "Book listing section added successfully!");
        } else {
            setAlert("danger", "Failed to add book listing section.");
        }
    }

    /** ------------------ UPDATE BOOK LISTING SECTION ------------------ */
} elseif ($type === "update-book-listing-section") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id          = (int) trim($_POST['id'] ?? 0);
        $sectionName = trim($_POST['section_name'] ?? '');
        $orderIndex  = (int) trim($_POST['order_index'] ?? 0);
        $cardType    = trim($_POST['card_type'] ?? 'standard');

        if (!$id) {
            setAlert("danger", "Section ID is required!");
            header("Location: $returnUrl");
            exit;
        }

        if (!$sectionName) {
            setAlert("danger", "Section Name is required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($orderIndex <= 0) {
            setAlert("danger", "Section Order must be a positive number!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->updateBookListingSection($id, $sectionName, $orderIndex, $cardType)) {
            setAlert("success", "Book listing section updated successfully!");
        } else {
            setAlert("danger", "Failed to update book listing section.");
        }
    }

    /** ------------------ DELETE BOOK LISTING SECTION ------------------ */
} elseif ($type === "delete-book-listing-section") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = (int) trim($_POST['id'] ?? 0);

        if (!$id) {
            setAlert("danger", "Section ID is required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->deleteBookListingSection($id)) {
            setAlert("success", "Book listing section deleted successfully!");
        } else {
            setAlert("danger", "Failed to delete book listing section.");
        }
    }
} elseif ($type === "reorder-book-listing-sections") {

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $orderData = json_decode(file_get_contents("php://input"), true);

        if (!$orderData || !is_array($orderData)) {
            setAlert("danger", "Invalid sort data.");
            header("Location: $returnUrl");
            exit;
        }

        foreach ($orderData as $section) {

            $id = (int) ($section['id'] ?? 0);
            $orderIndex = (int) ($section['order_index'] ?? 0);

            if ($id && $orderIndex > 0) {
                $controller->updateBookListingSectionOrder($id, $orderIndex);
            }
        }

        setAlert("success", "Sections reordered successfully!");
    }
}

header("Location: $returnUrl");
exit;
