<?php
session_start();

include '../includes/database_connections/sabooks.php';
require_once '../google/vendor/autoload.php';
require_once __DIR__ . "../controllers/AuthController.php";

$authController = new AuthController($conn);


if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    header('Location: https://11-july-2023.sabooksonline.co.za/dashboard');
}

// Check if the form is submitted
if (isset($_POST['log_email']) && isset($_POST['log_pwd2'])) {
    $email = $_POST['log_email'];
    $password = $_POST['log_pwd2'];

    echo $authController->loginWithForm($email, $password);
} else {
    // Render login view
    include 'views/login/login.php';
}

?>

 

