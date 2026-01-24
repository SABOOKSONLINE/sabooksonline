<?php
session_start();
require_once __DIR__ . "/sessionAlerts.php";
require_once __DIR__ . "/../Core/Conn.php";
require_once __DIR__ . "/../Model/MobileBannerModel.php";
require_once __DIR__ . "/../Model/NotificationModel.php";

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$type = $_GET['type'] ?? $_POST['type'] ?? '';
$id = $_GET['id'] ?? $_POST['id'] ?? 0;

$mobileBannerModel = new MobileBannerModel($conn);
$notificationModel = new NotificationModel($conn);

// Mobile Banner Actions
if ($type === 'banner') {
    switch ($action) {
        case 'delete':
            if ($id > 0) {
                $success = $mobileBannerModel->deleteMobileBanner($id);
                if ($success) {
                    setAlert("success", "Mobile banner deleted successfully!");
                } else {
                    setAlert("danger", "Failed to delete mobile banner.");
                }
            }
            header("Location: /admin/mobile/banners");
            exit;
            
        case 'toggle':
            if ($id > 0) {
                $success = $mobileBannerModel->toggleMobileBannerStatus($id);
                if ($success) {
                    setAlert("success", "Mobile banner status updated!");
                } else {
                    setAlert("danger", "Failed to update banner status.");
                }
            }
            header("Location: /admin/mobile/banners");
            exit;
            
        case 'edit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
                $data = [
                    'title' => trim($_POST['title'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'image_url' => trim($_POST['image_url'] ?? ''),
                    'action_url' => trim($_POST['action_url'] ?? ''),
                    'screen' => $_POST['screen'] ?? 'home',
                    'priority' => (int)($_POST['priority'] ?? 0),
                    'is_active' => isset($_POST['is_active']) ? 1 : 0,
                    'start_date' => $_POST['start_date'] ?? date('Y-m-d H:i:s'),
                    'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null
                ];

                if (empty($data['title']) || empty($data['image_url'])) {
                    setAlert("danger", "Title and image URL are required!");
                    header("Location: /admin/mobile/banners");
                    exit;
                }

                $success = $mobileBannerModel->updateMobileBanner($id, $data);
                if ($success) {
                    setAlert("success", "Mobile banner updated successfully!");
                } else {
                    setAlert("danger", "Failed to update mobile banner.");
                }
            }
            header("Location: /admin/mobile/banners");
            exit;
    }
}

// Mobile Notification Actions
if ($type === 'notification') {
    switch ($action) {
        case 'delete':
            if ($id > 0) {
                $success = $notificationModel->deleteNotification($id);
                if ($success) {
                    setAlert("success", "Notification deleted successfully!");
                } else {
                    setAlert("danger", "Failed to delete notification. Only draft notifications can be deleted.");
                }
            }
            header("Location: /admin/mobile/notifications");
            exit;
    }
}

// Handle mobile banner add/create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$id) {
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'image_url' => trim($_POST['image_url'] ?? ''),
        'action_url' => trim($_POST['action_url'] ?? ''),
        'screen' => $_POST['screen'] ?? 'home',
        'priority' => (int)($_POST['priority'] ?? 0),
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'start_date' => $_POST['start_date'] ?? date('Y-m-d H:i:s'),
        'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null
    ];

    if (empty($data['title']) || empty($data['image_url'])) {
        setAlert("danger", "Title and image URL are required!");
        header("Location: /admin/mobile/banners");
        exit;
    }

    $bannerId = $mobileBannerModel->addMobileBanner($data);
    if ($bannerId) {
        setAlert("success", "Mobile banner created successfully!");
    } else {
        setAlert("danger", "Failed to create mobile banner.");
    }
    
    header("Location: /admin/mobile/banners");
    exit;
}

// If no valid action, redirect back
header("Location: /admin/mobile/banners");
exit;
?>