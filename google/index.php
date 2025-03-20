<?php
session_start();

require_once 'vendor/autoload.php';

use Google\Client as Google_Client;

$client = new Google_Client();
$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://sabooksonline.co.za/google/callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    header('Location: dashboard.php');
}

$authUrl = $client->createAuthUrl();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Register</title>
</head>
<body>
    <h2>Login or Register</h2>
    <a href="<?php echo $authUrl; ?>" style="background: #f3f3f3;padding: 1%;">Login with Google</a>
</body>
</html>
