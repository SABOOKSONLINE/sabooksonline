<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ini_set('session.save_path', '/tmp'); // 👈 Set this before session_start()
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/database_connections/sabooks.php';
require_once __DIR__ . '/../controllers/AuthController.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2;


$client = new Google_Client();

$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://11-july-2023.sabooksonline.co.za/google/callback');
$client->addScope('email');
$client->addScope('profile');



if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $accessToken = $client->getAccessToken();

    if ($accessToken) {

    $_SESSION['access_token'] = $accessToken;
    $client->setAccessToken($accessToken);

    } else {
        die("Failed to retrieve access token.");
    }

    $service = new Google_Service_Oauth2($client);
    $user = $service->userinfo->get();


    $authController = new AuthController($conn);



    $reg_name = $user->getName() ;
    $reg_email = $user->getEmail();
    $profileimage = $user->getPicture();

    print_r(value: $user);
    print_r(value: $reg_name);
    print_r(value: $reg_email);



    $loginResult = $authController->loginWithGoogle($reg_email);
    if (isset($_SESSION['ADMIN_ID'])) {
    error_log("✅ Session set for: " . $_SESSION['ADMIN_EMAIL']);
    } else {
        error_log("❌ Session NOT set");
    }

    if ($loginResult === true) {
        // ✅ Proper session is set, now redirect
        header('Location: https://11-july-2023.sabooksonline.co.za/dashboard');
        // header('Location: https://11-july-2023.sabooksonline.co.za/dashboard');
        exit;
    } else {
        // ❌ Login failed, show the error
        echo $loginResult;
        exit;
    }
}

