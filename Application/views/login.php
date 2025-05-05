<?php 
require_once 'vendor/autoload.php';
use Google\Client as Google_Client;

$client = new Google_Client();
$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://11-july-2023.sabooksonline.co.za/google/callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    header('Location: https://11-july-2023.sabooksonline.co.za/dashboard');
}

// Generate the Google login URL
$authUrl = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div id="register">
        <aside>
            <figure><a href="index"><img src="img/social.png" width="140" height="65" alt=""></a></figure>
            <h4 class="text-center">Login To Account</h4>
            <div class="access_social">
                <a href="<?php echo $authUrl; ?>" class="social_bt google" style="background: #fff !important;border: #ddd 1px solid;color: #444;">
                    <img src="img/Google__G__Logo.svg" width="20px" class="mr-5"> Login with Google
                </a>
            </div>
            <div class="divider"><span>Or</span></div>
            <form autocomplete="off" id="login">
                <div id="reg_status"></div>
                <div class="form-group">
                    <input class="form-control" type="email" placeholder="Email" name="log_email" required>
                    <i class="icon_mail_alt"></i>
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" id="password" placeholder="Password" name="log_pwd2" required>
                    <i class="icon_lock_alt"></i>
                </div>
                <button type="submit" class="btn_1 gradient full-width" id="reg_load">Login Now!</button>
            </form>
        </aside>  
    </div>
    <script src="js/common_scripts.min.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>
