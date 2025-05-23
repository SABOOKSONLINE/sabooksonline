<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct($conn)
    {
        $this->userModel = new UserModel($conn);
    }

    public function renderUserById($userId)
    {
        $user = $this->userModel->getUserById($userId);

        if ($user) {
            include __DIR__ . "/../views/includes/layouts/forms/user_form.php";
        } else {
            echo "User not found.";
        }
    }

    /**
     * Update existing user data
     * @param string $userId
     * @param array $data
     */
    public function updateUserData($userId, $data)
    {
        $this->userModel->updateUser($userId, $data);
    }
}
