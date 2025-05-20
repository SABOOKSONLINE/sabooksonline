<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../Config/connection.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This route only handles POST.";
    exit;
}

if (isset($_POST['log_email']) && isset($_POST['log_pwd2'])) {
    $authController = new AuthController($conn);
    $email = $_POST['log_email'];
    $password = $_POST['log_pwd2'];
    echo $authController->loginWithForm($email, $password);
} else {
    http_response_code(400);
    echo "Invalid request.";
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "This route only handles POST.";
    exit;
}

