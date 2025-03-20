<?php
require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['access_token']) || !$_SESSION['access_token']) {
    header('Location: ../index.php');
    exit;
}

// Retrieve user data from Google API using the access token
$client = new Google_Client();
$client->setAccessToken($_SESSION['access_token']);
$service = new Google_Service_Oauth2($client);
$user = $service->userinfo->get();

// Display user information
//echo '<h2>Welcome ' . $user->getName() . '</h2>';
//echo '<p>Email: ' . $user->getEmail() . '</p>';
//echo '<p>Google ID: ' . $user->getId() . '</p>';
//echo '<p>Given Name: ' . $user->getGivenName() . '</p>';
//echo '<p>Family Name: ' . $user->getFamilyName() . '</p>';
//echo '<p>Gender: ' . $user->getGender() . '</p>';
//echo '<p>Locale: ' . $user->getLocale() . '</p>';
//echo '<p>Profile Picture URL: ' . $user->getPicture() . '</p>';
////echo '<p>Verified Email: ' . ($user->getVerifiedEmail() ? 'Yes' : 'No') . '</p>';
//echo '<p>HD Domain: ' . $user->getHd() . '</p>'; // For G Suite users only

include 'google-register.php';

echo '<a href="logout.php">Logout</a>';
