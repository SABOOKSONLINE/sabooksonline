<?php
session_start();

require_once 'vendor/autoload.php';

use Google\Client as Google_Client;

$client = new Google_Client();

//for the main website
$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
// $client->setRedirectUri('https://sabooksonline.co.za/google/callback.php');

//for the staging website
// $client->setClientId('127479199120-jekj11lumhpkcu3vs7uh0ke64q9pt56u.apps.googleusercontent.com');
// $client->setClientSecret('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');
$client->setRedirectUri('https://11-july-2023.sabooksonline.co.za/google/callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: dashboard.php');
    exit;
}
