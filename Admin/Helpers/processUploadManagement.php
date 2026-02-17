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
require_once __DIR__ . "/../Controller/UploadManagementController.php";

$controller = new UploadManagementController($conn);
$type = $_GET['type'];
$returnUrl = $_GET['return'];

if ($type === "add-publisher") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $userId = trim($_POST['user_id'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');

        if (!$userId || !$email) {
            setAlert("danger", "User ID and Email are required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->addPublisher($userId, $email, $name)) {
            setAlert("success", "Publisher added successfully!");
        } else {
            setAlert("danger", "Failed to add publisher. Email might already exist.");
        }
    }
} elseif ($type === "remove-publisher") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST['email'] ?? '');

        if (!$email) {
            setAlert("danger", "Email is required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->removePublisher($email)) {
            setAlert("success", "Publisher removed successfully!");
        } else {
            setAlert("danger", "Failed to remove publisher.");
        }
    }
} elseif ($type === "toggle-publisher") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST['email'] ?? '');
        $canPublish = isset($_POST['can_publish']) ? (bool)$_POST['can_publish'] : false;

        if (!$email) {
            setAlert("danger", "Email is required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->togglePublisher($email, $canPublish)) {
            setAlert("success", "Publisher status updated!");
        } else {
            setAlert("danger", "Failed to update publisher status.");
        }
    }
}

header("Location: $returnUrl");
exit;
