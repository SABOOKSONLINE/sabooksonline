<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Config/connection.php';
require_once __DIR__ . '/../controllers/AuthController.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2;


$client = new Google_Client();

$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://www.sabooksonline.co.za/google/callback');
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


    $reg_name = $user->getName();
    $reg_email = $user->getEmail();
    $profileimage = $user->getPicture();


    $loginResult = $authController->loginWithGoogle($reg_email,$reg_name,$profileimage);

    error_log("Session Contents: " . print_r($_SESSION, true));

    if (isset($_SESSION['ADMIN_ID'])) {
    } else {
        error_log(" Session NOT set");
    }

    if ($loginResult === true && isset($_SESSION['ADMIN_ID'])) {
        // ✅ Proper session is set, now redirect
        header('Location: /dashboards');
        exit;
    } else {

        echo "<div class='alert alert-danger'>Login session could not be established. Please try again.</div>";
        echo $loginResult;
        exit;
    }
}
