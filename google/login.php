<?php

require __DIR__ . '/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://sabooksonline.co.za');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: ' . filter_var('https://sabooksonline.co.za', FILTER_SANITIZE_URL));
    exit;
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $service = new Google_Service_Oauth2($client);
    $user = $service->userinfo->get();

    // Store user data in variables
    $userId = $user->getId();
    $userName = $user->getName();
    $userEmail = $user->getEmail();
    
    // You can access other properties similarly if needed
    
    // Here, you can do whatever you want with the user data
    echo "Hello, " . $userName;
    echo "Your email is: " . $userEmail;
} else {
    $authUrl = $client->createAuthUrl();
    echo '<a href="' . $authUrl . '">Login with Google</a>';
}
