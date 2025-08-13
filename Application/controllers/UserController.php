<?php
// Load dependencies
// require_once __DIR__ . '/../Config/connection.php';
// include_once 'models/UserModel.php';

// session_start();

/**
 * Class UserController
 *
 * Responsible for handling user profile views.
 */
class UserController {
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * UserController constructor.
     *
     * @param mysqli $conn MySQLi connection object
     */
    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function getCreators($date = null)
    {
        $creator = $this->userModel->getCreators($date);

        header('Content-Type: application/json');

        if ($creator) {
            echo json_encode($creator);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'creator not found']);
        }
    }

    /**
     * Display a user profile based on a query string or unique user key.
     *
     * @param string|null $query Content ID or username slug from URL
     * @return void Redirects on failure, loads view on success
     */
    public function showUserProfile(?string $query): void {
        if (empty($query)) {
            header("Location: 404");
            exit;
        }

        $contentid = $query;
        $user = $this->userModel->getUserByNameOrKey($contentid);

        if (!$user) {
            header("Location: 404?LINK-NOT-FOUND");
            exit;
        }

        // Assign variables for use in the profile view
        $name        = ucwords($user['ADMIN_NAME']);
        $name_right  = ucwords($user['ADMIN_NAME']);
        $date        = ucwords($user['ADMIN_DATE']);
        $email       = ucwords($user['ADMIN_EMAIL']);
        $website     = ucwords($user['ADMIN_WEBSITE']);
        $contentid   = $user['ADMIN_USERKEY'];
        $desc        = ucwords($user['ADMIN_BIO']);
        $cover       = $user['ADMIN_PROFILE_IMAGE'];
        $facebook    = strtolower($user['ADMIN_FACEBOOK']);
        $instagram   = strtolower($user['ADMIN_INSTAGRAM']);
        $twitter     = strtolower($user['ADMIN_TWITTER']);
        $type        = ucwords($user['ADMIN_TYPE']);
        $contentdata = ucwords($user['ADMIN_USERKEY']);

        // Slug for user profile URL
        $name_slug = strtolower(rtrim(str_replace(' ', '-', $name), "-"));

        // Get posts authored by this user
        $posts = $this->userModel->getPostsByUserKey($contentdata);

        // Load the user profile view
        include 'views/user_Profile/profile.php';
    }
}

// // Run the controller
// $controller = new UserController($conn);
// $controller->showUserProfile($_GET['q'] ?? null);
