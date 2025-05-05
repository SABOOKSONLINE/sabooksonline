<?php
session_start();

require_once 'vendor/autoload.php';
require_once '../includes/database_connections/sabooks.php';
require_once __DIR__ . "../controllers/AuthController.php";

use Google\Client as Google_Client;
use Google\Service\Oauth2;


$client = new Google_Client();

$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://11-july-2023.sabooksonline.co.za/google/callback.php');
$client->addScope('email');
$client->addScope('profile');



$authController = new AuthController($conn);

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();

    // Get user info
    $oauth = new Oauth2($client);
    $google_user = $oauth->userinfo->get();

    $email = $google_user->email;
    $name = $google_user->name;
    $google_id = $google_user->id;

    echo $authController->loginWithGoogle($email);    
    exit;
}
