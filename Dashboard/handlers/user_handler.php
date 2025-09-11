<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../controllers/UserController.php";

$userController = new UserController($conn);

function formDataArray()
{
    $adminId = htmlspecialchars($_POST["ADMIN_ID"]);
    $adminName = htmlspecialchars($_POST["ADMIN_NAME"]);
    $adminEmail = htmlspecialchars($_POST["ADMIN_EMAIL"]);
    $adminNumber = htmlspecialchars($_POST["ADMIN_NUMBER"]);
    $adminWebsite = htmlspecialchars($_POST["ADMIN_WEBSITE"]);
    $adminBio = htmlspecialchars($_POST["ADMIN_BIO"]);
    $adminFacebook = htmlspecialchars($_POST["ADMIN_FACEBOOK"]);
    $adminInstagram = htmlspecialchars($_POST["ADMIN_INSTAGRAM"]);
    $adminTwitter = htmlspecialchars($_POST["ADMIN_TWITTER"]);
    $adminLinkedin = htmlspecialchars($_POST["ADMIN_LINKEDIN"]);
    $adminStatus = htmlspecialchars($_POST["ADMIN_USER_STATUS"] ?? 'approved');
    $adminType = htmlspecialchars($_POST["ADMIN_TYPE"] ?? 'user');
    $modified = date('Y-m-d H:i:s');

    $adminPassword = null;
    if (!empty($_POST["ADMIN_PASSWORD"])) {
        if ($_POST["ADMIN_PASSWORD"] !== $_POST["CONFIRM_PASSWORD"]) {
            die("❌ Passwords do not match.");
        }
        $adminPassword = password_hash($_POST["ADMIN_PASSWORD"], PASSWORD_BCRYPT);
    }

    $adminProfileImage = htmlspecialchars($_POST['existing_profile'] ?? '');
    if (isset($_FILES['ADMIN_PROFILE_IMAGE']) && $_FILES['ADMIN_PROFILE_IMAGE']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../cms-data/profile-images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = pathinfo($_FILES['ADMIN_PROFILE_IMAGE']['name'], PATHINFO_EXTENSION);
        $adminProfileImage = time() . '_' . uniqid() . '.' . strtolower($ext);
        $targetPath = $uploadDir . $adminProfileImage;

        if (!move_uploaded_file($_FILES['ADMIN_PROFILE_IMAGE']['tmp_name'], $targetPath)) {
            die("❌ Failed to upload profile image.");
        }
    }

    return [
        'ADMIN_ID' => $adminId,
        'ADMIN_NAME' => $adminName,
        'ADMIN_EMAIL' => $adminEmail,
        'ADMIN_NUMBER' => $adminNumber,
        'ADMIN_WEBSITE' => $adminWebsite,
        'ADMIN_BIO' => $adminBio,
        'ADMIN_FACEBOOK' => $adminFacebook,
        'ADMIN_INSTAGRAM' => $adminInstagram,
        'ADMIN_TWITTER' => $adminTwitter,
        'ADMIN_LINKEDIN' => $adminLinkedin,
        'ADMIN_PASSWORD' => $adminPassword,
        'ADMIN_PROFILE_IMAGE' => $adminProfileImage,
        'ADMIN_USER_STATUS' => $adminStatus,
        'ADMIN_TYPE' => $adminType,
        'MODIFIED' => $modified
    ];
}

function updateUser($userController)
{
    try {
        $userData = formDataArray();
        $userId = $_GET["id"] ?? null;
        if (!$userId) die("❌ Missing user ID.");

        if (empty($userData['ADMIN_NAME']) || empty($userData['ADMIN_EMAIL'])) {
            die("❌ Validation failed: Name and Email required.");
        }

        $fieldsToUpdate = [
            'ADMIN_NAME' => $userData['ADMIN_NAME'],
            'ADMIN_NUMBER' => $userData['ADMIN_NUMBER'],
            'ADMIN_WEBSITE' => $userData['ADMIN_WEBSITE'],
            'ADMIN_BIO' => $userData['ADMIN_BIO'],
            'ADMIN_FACEBOOK' => $userData['ADMIN_FACEBOOK'],
            'ADMIN_INSTAGRAM' => $userData['ADMIN_INSTAGRAM'],
            'ADMIN_TWITTER' => $userData['ADMIN_TWITTER'],
            'ADMIN_LINKEDIN' => $userData['ADMIN_LINKEDIN'],
            'ADMIN_PROFILE_IMAGE' => $userData['ADMIN_PROFILE_IMAGE'],
            'ADMIN_USER_STATUS' => $userData['ADMIN_USER_STATUS'],
            'ADMIN_TYPE' => $userData['ADMIN_TYPE'],
            'MODIFIED' => $userData['MODIFIED']
        ];

        if (!empty($userData['ADMIN_PASSWORD'])) {
            $fieldsToUpdate['ADMIN_PASSWORD'] = $userData['ADMIN_PASSWORD'];
        }

        $userController->updateUserData($userId, $fieldsToUpdate);

        header("Location: /dashboards/profile?update=success");
        exit();
    } catch (Exception $e) {
        error_log("Profile update error: " . $e->getMessage());
        header("Location: /dashboards/profile?update=fail");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_GET["action"] ?? '') === "update") {
    updateUser($userController);
}
