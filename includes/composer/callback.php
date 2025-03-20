<?php
session_start();

require_once 'vendor/autoload.php';

use Google\Client as Google_Client;

$client = new Google_Client();
$client->setClientId('515832721645-o0nkphq6v3pmjk1o10720vi0b074vv39.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://miningpartnership.co.za/dashboard/google/callback.php');
$client->addScope('email');
$client->addScope('profile'); 

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: dashboard.php');
    exit;
}
