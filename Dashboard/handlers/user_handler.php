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
    $adminAddress = htmlspecialchars($_POST["ADMIN_ADDRESS"]);
    $adminBio = htmlspecialchars($_POST["ADMIN_BIO"]);
    $adminFacebook = htmlspecialchars($_POST["ADMIN_FACEBOOK"]);
    $adminInstagram = htmlspecialchars($_POST["ADMIN_INSTAGRAM"]);
    $adminTwitter = htmlspecialchars($_POST["ADMIN_TWITTER"]);
    $adminLinkedin = htmlspecialchars($_POST["ADMIN_LINKEDIN"]);
    $adminPassword = !empty($_POST["ADMIN_PASSWORD"]) ? password_hash($_POST["ADMIN_PASSWORD"], PASSWORD_BCRYPT) : null;
    $adminStatus = htmlspecialchars($_POST["ADMIN_USER_STATUS"] ?? 'inactive');
    $adminType = htmlspecialchars($_POST["ADMIN_TYPE"] ?? 'user');

    $adminProfileImage = "";
    
   if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../cms-data/profile-images/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Extract the original extension
    $ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
    // Create a clean, unique filename (e.g. 1720012345_abcd1234.jpeg)
    $adminProfileImage = time() . '_' . uniqid() . '.' . strtolower($ext);

    $targetPath = $uploadDir . $adminProfileImage;

    if (!move_uploaded_file($_FILES['profile']['tmp_name'], $targetPath)) {
        die("❌ Failed to upload profile image.");
    }
} else {
    // Keep existing filename (not full path)
    $adminProfileImage = htmlspecialchars($_POST['existing_profile'] ?? '');
}




    $modified = date('Y-m-d H:i:s');

    $userData = [
        'ADMIN_ID' => $adminId,
        'ADMIN_NAME' => $adminName,
        'ADMIN_EMAIL' => $adminEmail,
        'ADMIN_NUMBER' => $adminNumber,
        'ADMIN_WEBSITE' => $adminWebsite,
        'ADMIN_ADDRESS' => $adminAddress,
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

    return $userData;
}

function updateUser($userController)
{
    try {
        $userData = formDataArray();

        $userId = $_GET["id"];

        if (empty($userData['ADMIN_ID']) || empty($userData['ADMIN_NAME']) || empty($userData['ADMIN_EMAIL'])) {
            die("Validation failed: Missing required fields.");
        }

        $userController->updateUserData($userId, $userData);
        header("Location: /dashboards/profile?update=success");
    } catch (Exception $e) {
        header("Location: /dashboards/profile?update=fail");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["action"] == "update") {
    updateUser($userController);
}
