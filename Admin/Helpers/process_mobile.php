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
                    'action_url' => trim($_POST['action_url'] ?? ''),
                    'screen' => $_POST['screen'] ?? 'home',
                    'priority' => (int)($_POST['priority'] ?? 0),
                    'is_active' => isset($_POST['is_active']) ? 1 : 0,
                    'start_date' => $_POST['start_date'] ?? date('Y-m-d H:i:s'),
                    'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null
                ];

                // Handle image upload if new image provided
                if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
                    $image = $_FILES['banner_image'];
                    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                    
                    if (!in_array($image['type'], $allowed)) {
                        setAlert("danger", "Invalid image format! Only JPG, PNG, or WEBP allowed.");
                        header("Location: /admin/mobile/banners");
                        exit;
                    }

                    $uploadDir = __DIR__ . "/../../cms-data/banners/";
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileName = uniqid("mobile_banner_", true) . "." . pathinfo($image['name'], PATHINFO_EXTENSION);
                    $targetPath = $uploadDir . $fileName;

                    if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                        $data['image_url'] = $fileName;
                    } else {
                        setAlert("danger", "Failed to upload image file.");
                        header("Location: /admin/mobile/banners");
                        exit;
                    }
                } else {
                    // Keep existing image if no new image uploaded
                    $existingBanner = $mobileBannerModel->getMobileBannerById($id);
                    $data['image_url'] = $existingBanner['image_url'] ?? '';
                }

                if (empty($data['title'])) {
                    setAlert("danger", "Title is required!");
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'action_url' => trim($_POST['action_url'] ?? ''),
        'screen' => $_POST['screen'] ?? 'home',
        'priority' => (int)($_POST['priority'] ?? 0),
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'start_date' => $_POST['start_date'] ?? date('Y-m-d H:i:s'),
        'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null
    ];

    // Handle image upload
    if (!isset($_FILES['banner_image']) || $_FILES['banner_image']['error'] !== UPLOAD_ERR_OK) {
        setAlert("danger", "Please select a banner image to upload!");
        header("Location: /admin/mobile/banners");
        exit;
    }

    $image = $_FILES['banner_image'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    
    if (!in_array($image['type'], $allowed)) {
        setAlert("danger", "Invalid image format! Only JPG, PNG, or WEBP allowed.");
        header("Location: /admin/mobile/banners");
        exit;
    }

    // Check file size (5MB max)
    if ($image['size'] > 5 * 1024 * 1024) {
        setAlert("danger", "Image file is too large! Maximum size is 5MB.");
        header("Location: /admin/mobile/banners");
        exit;
    }

    $uploadDir = __DIR__ . "/../../cms-data/banners/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid("mobile_banner_", true) . "." . pathinfo($image['name'], PATHINFO_EXTENSION);
    $targetPath = $uploadDir . $fileName;

    if (!move_uploaded_file($image['tmp_name'], $targetPath)) {
        setAlert("danger", "Failed to upload image file.");
        header("Location: /admin/mobile/banners");
        exit;
    }

    $data['image_url'] = $fileName;

    if (empty($data['title'])) {
        setAlert("danger", "Title is required!");
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