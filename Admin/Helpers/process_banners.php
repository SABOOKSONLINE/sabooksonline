<?php
session_start();
require_once __DIR__ . "/sessionAlerts.php";
require_once __DIR__ . "/../Core/Conn.php";

if (!isset($_GET['type'], $_GET['return'], $_GET['banner'])) {
    setAlert("warning", "Invalid request parameters!");
    header("Location: /admin");
    exit;
}

require_once __DIR__ . "/../Model/BannersModel.php";
require_once __DIR__ . "/../Controller/PagesController.php";

$controller = new PagesController($conn);
$type = $_GET['type'];
$returnUrl = $_GET['return'];
$bannerType = $_GET['banner'];

if ($bannerType === "popup") {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && $type === "insert") {
        $bookKey    = trim($_POST['book_public_key'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $subtext    = trim($_POST['subtext'] ?? '');
        $buttonText = trim($_POST['button_text'] ?? 'Read Now');
        $buttonLink = trim($_POST['link'] ?? '');
        $dateFrom   = $_POST['date_from'] ?? null;
        $dateTo     = $_POST['date_to'] ?? null;
        $timeFrom   = $_POST['time_from'] ?? null;
        $timeTo     = $_POST['time_to'] ?? null;

        if (!$bookKey || !$dateFrom || !$dateTo || !$timeFrom || !$timeTo) {
            setAlert("danger", "Please fill in all required fields!");
            header("Location: $returnUrl");
            exit;
        }

        $bannerData = [
            'book_public_key' => $bookKey,
            'description'     => $description,
            'subtext'         => $subtext,
            'button_text'     => $buttonText,
            'link'            => $buttonLink,
            'date_from'       => $dateFrom,
            'date_to'         => $dateTo,
            'time_from'       => $timeFrom,
            'time_to'         => $timeTo,
            'is_active'       => 1
        ];

        if ($controller->addPopupBanner($bannerData)) {
            setAlert("success", "Popup banner saved successfully!");
        } else {
            setAlert("danger", "Failed to save popup banner. Please try again.");
        }
    } elseif ($_SERVER["REQUEST_METHOD"] === "GET" && $type === "delete") {
        if ($controller->deletePopupBanner($_GET["id"])) {
            setAlert("success", "Popup banner deleted successfully!");
        } else {
            setAlert("danger", "Failed to delete popup banner. Please try again.");
        }
    }
} elseif ($bannerType === "sticky") {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && $type === "insert") {
        $heading    = trim($_POST['heading'] ?? '');
        $subheading = trim($_POST['subheading'] ?? '');
        $buttonText = trim($_POST['buttonText'] ?? '');
        $link       = trim($_POST['link'] ?? '');

        if (!$heading || !$subheading || !$buttonText || !$link) {
            setAlert("danger", "Please fill in all required fields!");
            header("Location: $returnUrl");
            exit;
        }

        $bannerData = [
            'heading'     => $heading,
            'subheading'  => $subheading,
            'button_text' => $buttonText,
            'button_link' => $link,
            'is_active'   => 1
        ];

        if ($controller->addStickyBanner($bannerData)) {
            setAlert("success", "Sticky banner saved successfully!");
        } else {
            setAlert("danger", "Failed to save sticky banner. Please try again.");
        }
    } elseif ($_SERVER["REQUEST_METHOD"] === "GET" && $type === "delete") {
        if ($controller->deleteStickyBanner($_GET["id"])) {
            setAlert("success", "Sticky banner deleted successfully!");
        } else {
            setAlert("danger", "Failed to delete sticky banner. Please try again.");
        }
    }
} elseif ($bannerType === "page") {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && $type === "insert") {
        $link      = trim($_POST['link'] ?? '');
        $showPage  = trim($_POST['show_page'] ?? '');
        $image     = $_FILES['banner_image'] ?? null;

        if (!$image || $image['error'] !== UPLOAD_ERR_OK || !$showPage) {
            setAlert("danger", "Please select a page and upload an image!");
            header("Location: $returnUrl");
            exit;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($image['type'], $allowed)) {
            setAlert("danger", "Invalid image format! Only JPG, PNG, or WEBP allowed.");
            header("Location: $returnUrl");
            exit;
        }

        $uploadDir = __DIR__ . "/../../cms-data/banners/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid("banner_", true) . "." . pathinfo($image['name'], PATHINFO_EXTENSION);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($image['tmp_name'], $targetPath)) {
            $bannerData = [
                'banner_image' => $fileName,
                'link'         => $link,
                'show_page'    => $showPage
            ];

            if ($controller->addPageBanner($bannerData)) {
                setAlert("success", "Banner uploaded successfully!");
            } else {
                setAlert("danger", "Failed to save banner data.");
            }
        } else {
            setAlert("danger", "Failed to upload image file.");
        }
    } elseif ($_SERVER["REQUEST_METHOD"] === "GET" && $type === "delete") {
        if ($controller->deletePageBanner($_GET["id"])) {
            setAlert("success", "Image banner deleted successfully!");
        } else {
            setAlert("danger", "Failed to delete image banner. Please try again.");
        }
    }
}

header("Location: $returnUrl");
exit;
