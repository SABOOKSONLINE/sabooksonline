<?php
class AuthService {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function login($email, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Your email is invalid!'];
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return ['error' => 'Email not found!'];
        }

        if ($user['USER_STATUS'] !== 'Verified') {
            return ['error' => 'Account not verified. Check your email for a verification link.'];
        }

        if (!password_verify($password, $user['ADMIN_PASSWORD'])) {
            return ['error' => 'Password incorrect!'];
        }

        return ['success' => true, 'user' => $user];
    }
}
