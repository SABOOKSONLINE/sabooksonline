<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('session.save_path', '/tmp');
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../../includes/database_connections/sabooks.php';
require_once __DIR__ . '/../../google/vendor/autoload.php';

$authController = new AuthController($conn);

if (isset($_POST['log_email']) && isset($_POST['log_pwd2'])) {
    $email = $_POST['log_email'];
    $password = $_POST['log_pwd2'];
    echo $authController->loginWithForm($email, $password);
} else {
    http_response_code(400);
    echo "Invalid request.";
}
