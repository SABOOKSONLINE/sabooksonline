<?php
// Load dependencies
require_once __DIR__ . '/../config/connection.php';
include_once 'models/UserModel.php';

session_start();

class UserController {
    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function showUserProfile($query) {
        if (!isset($query)) {
            header("Location: 404");
            exit;
        }

        $contentid = $query;
        $user = $this->userModel->getUserByNameOrKey($contentid);

        if (!$user) {
            header("Location: 404?LINK-NOT-FOUND");
            exit;
        }

        // Assign variables (original names)
        $name = ucwords($user['ADMIN_NAME']);
        $name_right = ucwords($user['ADMIN_NAME']);
        $date = ucwords($user['ADMIN_DATE']);
        $email = ucwords($user['ADMIN_EMAIL']);
        $website = ucwords($user['ADMIN_WEBSITE']);
        $contentid = $user['ADMIN_USERKEY'];
        $desc = ucwords($user['ADMIN_BIO']);
        $cover = $user['ADMIN_PROFILE_IMAGE'];
        $facebook = strtolower($user['ADMIN_FACEBOOK']);
        $instagram = strtolower($user['ADMIN_INSTAGRAM']);
        $twitter = strtolower($user['ADMIN_TWITTER']);
        $type = ucwords($user['ADMIN_TYPE']);
        $contentdata = ucwords($user['ADMIN_USERKEY']);

        $name_slug = strtolower(rtrim(str_replace(' ', '-', $name), "-"));

        // Get posts
        $posts = $this->userModel->getPostsByUserKey($contentdata);

        // Load the view
        include 'views/user_Profile/profile.php';
    }
}

// Run the controller or you can run it in the creator home page
$controller = new UserController($conn);
$controller->showUserProfile($_GET['q'] ?? null);
