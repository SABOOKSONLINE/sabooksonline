<?php
require_once __DIR__ . '/../models/UserModel.php';


class AuthController {

    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function loginWithGoogle($email,$reg_name,$profileImage) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "<div class='alert alert-warning'>Your email is invalid!</div>";
    }

    $result = $this->userModel->findUserByEmail($email);

    if (!mysqli_num_rows($result)) {
        $this->userModel->insertGoogleUser($reg_name, $email, $profileImage, $email);
    }

    $userData = mysqli_fetch_assoc($result);
    // $status = $userData['USER_STATUS'];

    // if ($status !== "Verified") {
    //     return "<center class='alert alert-warning'>Your account needs to be confirmed. Please check your email.</center>";
    // }

    $this->userModel->startSession($userData);


    // 👇 Confirm session is properly set
    if (!isset($_SESSION['ADMIN_ID'])) {
        return "<div class='alert alert-danger'>Failed to set session. Please try again.</div>";
    }

    return true;
}

    public function loginWithForm($email, $password, $api = false) {
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
                    if ($api) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'adminKey' => $userData['ADMIN_USERKEY']]);
                        exit;
                    }else{
                        $this->userModel->startSession($userData);
                        header('Location: /dashboard');}

                }
            }
        }
    }
}
