<?php
session_start();

// require_once 'vendor/autoload.php';
require_once __DIR__ . "/../../../vendor/autoload.php";


use Google\Client as Google_Client;


//for the main website
$client = new Google_Client();
$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://11-july-2023.sabooksonline.co.za/google/callback');

$client->addScope('email');
$client->addScope('profile');

if (isset($_SESSION['ADMIN_ID'])) {
        // ✅ Proper session is set, now redirect
 header('Location: /dashboard');
}

$authUrl = $client->createAuthUrl();