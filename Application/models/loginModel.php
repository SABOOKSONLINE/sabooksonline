<?php
// models/UserModel.php
namespace App\Models;

use mysqli;

class loginModel {

    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function findUserByEmail($email) {
        $email = mysqli_real_escape_string($this->conn, $email);
        $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$email';";
        return mysqli_query($this->conn, $sql);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function startSession($userData) {
        session_start();
        $_SESSION['ADMIN_ID'] = $userData['ADMIN_ID'];
        $_SESSION['ADMIN_SUBSCRIPTION'] = $userData['ADMIN_SUBSCRIPTION'];
        $_SESSION['ADMIN_NAME'] = $userData['ADMIN_NAME'];
        $_SESSION['ADMIN_EMAIL'] = $userData['ADMIN_EMAIL'];
        $_SESSION['ADMIN_NUMBER'] = $userData['ADMIN_NUMBER'];
        $_SESSION['ADMIN_WEBSITE'] = $userData['ADMIN_WEBSITE'];
        $_SESSION['ADMIN_BIO'] = $userData['ADMIN_BIO'];
        $_SESSION['ADMIN_TYPE'] = $userData['ADMIN_TYPE'];
        $_SESSION['ADMIN_FACEBOOK'] = $userData['ADMIN_FACEBOOK'];
        $_SESSION['ADMIN_TWITTER'] = $userData['ADMIN_TWITTER'];
        $_SESSION['ADMIN_LINKEDIN'] = $userData['ADMIN_LINKEDIN'];
        $_SESSION['ADMIN_GOOGLE'] = $userData['ADMIN_GOOGLE'];
        $_SESSION['ADMIN_INSTAGRAM'] = $userData['ADMIN_INSTAGRAM'];
        $_SESSION['ADMIN_CUSTOMER_PLESK'] = $userData['ADMIN_PINTEREST'];
        $_SESSION['ADMIN_PASSWORD'] = $userData['ADMIN_PASSWORD'];
        $_SESSION['ADMIN_DATE'] = $userData['ADMIN_DATE'];
        $_SESSION['ADMIN_VERIFICATION_LINK'] = $userData['ADMIN_VERIFICATION_LINK'];
        $_SESSION['ADMIN_PROFILE_IMAGE'] = $userData['ADMIN_PROFILE_IMAGE'];
        $_SESSION['ADMIN_USERKEY'] = $userData['ADMIN_USERKEY'];
        $_SESSION['ADMIN_USER_STATUS'] = $userData['ADMIN_USER_STATUS'];
        $_SESSION['ADMIN_SERVICES'] = $userData['ADMIN_SERVICES'];
        $_SESSION['ADMIN_IMAGE01'] = $userData['ADMIN_IMAGE01'];
        $_SESSION['ADMIN_IMAGE02'] = $userData['ADMIN_IMAGE02'];
        $_SESSION['ADMIN_IMAGE03'] = $userData['ADMIN_IMAGE03'];
        $_SESSION['ADMIN_IMAGE04'] = $userData['ADMIN_IMAGE04'];
    }
}
