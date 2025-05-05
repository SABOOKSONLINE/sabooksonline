<?php
session_start();

include '../includes/database_connections/sabooks.php';
require_once '../google/vendor/autoload.php';

use App\Controllers\AuthController;

$authController = new AuthController($conn);

// Check if the form is submitted
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['log_email'];
    $password = $_POST['log_pwd2'];
    echo $authController->loginWithForm($email, $password);
} else {
    // Render login view
    include 'views/login/login.php';
}

?>

 

