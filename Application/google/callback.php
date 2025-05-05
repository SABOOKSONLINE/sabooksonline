<?php
ini_set('session.save_path', '/tmp'); // 👈 Set this before session_start()
session_start();

require_once 'vendor/autoload.php';
require_once '../../../includes/database_connections/sabooks.php';
require_once __DIR__ . "../controllers/AuthController.php";

use Google\Client as Google_Client;
use Google\Service\Oauth2;


$client = new Google_Client();

$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://11-july-2023.sabooksonline.co.za/google/callback.php');
$client->addScope('email');
$client->addScope('profile');



if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();

    $oauth = new Oauth2($client);
    $google_user = $oauth->userinfo->get();

    $authController = new AuthController($conn);

    $email = $google_user->email;

    $loginResult = $authController->loginWithGoogle($email);

    if ($loginResult === true) {
        // ✅ Proper session is set, now redirect
        header('https://11-july-2023.sabooksonline.co.za/dashboard');
        exit;
    } else {
        // ❌ Login failed, show the error
        echo $loginResult;
        exit;
    }
}

