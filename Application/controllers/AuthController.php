<?php
require_once __DIR__ . '/../models/userModel.php';


class AuthController {

    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function loginWithGoogle($email) {
        
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "<div class='alert alert-warning'>Your email is invalid!</div>";
    }

    $result = $this->userModel->findUserByEmail($email);

    if (!mysqli_num_rows($result)) {
        return "<center class='alert alert-warning'>Email Not Found!</center>";
    }

    $userData = mysqli_fetch_assoc($result);
    $status = $userData['USER_STATUS'];

    if ($status !== "Verified") {
        return "<center class='alert alert-warning'>Your account needs to be confirmed. Please check your email.</center>";
    }

    $this->userModel->startSession($userData);

    // ✅ We return true and handle the redirect in callback.php
    return true;
}

    public function loginWithForm($email, $password) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "<div class='alert alert-warning'>Your email is invalid!</div>";
        }

        $result = $this->userModel->findUserByEmail($email);

        if (!mysqli_num_rows($result)) {
            return "<center class='alert alert-warning'>Email Not Found!</center>";
        } else {
            $userData = mysqli_fetch_assoc($result);
            $status = $userData['USER_STATUS'];

            if ($status != "Verified") {
                return "<center class='alert alert-warning'>Your account needs to be confirmed before you can login. Please check your emails for a confirmation email with a verification link.</center>";
            } else {
                if (!$this->userModel->verifyPassword($password, $userData['ADMIN_PASSWORD'])) {
                    return "<center class='alert alert-warning'>Password Incorrect!</center>";
                } else {
                    $this->userModel->startSession($userData);
                    return '<script>window.location.href="https://11-july-2023.sabooksonline.co.za/dashboard";</script>';
                }
            }
        }
    }
}
