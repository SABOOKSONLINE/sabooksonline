<?php
if (session_status() === PHP_SESSION_NONE) {
    $cookieDomain = ".sabooksonline.co.za";
    session_set_cookie_params(0, '/', $cookieDomain);
    session_start();
}

if (!isset($_SESSION['ADMIN_USERKEY'])) {
    header("Location: /login");
    exit;
}

$userKey = $_SESSION['ADMIN_USERKEY'];
$userId = $_SESSION['ADMIN_ID'];
$userName = extractName($_SESSION['ADMIN_EMAIL']);

$oldDefaultProfile = "https://www.vecteezy.com/free-vector/default-profile-picture";
$adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'];

$emails = ["tebogo@sabooksonline.co.za", "kganyamilton@gmail.com", "khumalopearl003@gmail.com"];
checkEmailAllowed($_SESSION['ADMIN_EMAIL'], $emails);

if (isset($userKey)) {
    if (!empty($adminProfileImage)) {
        if (strpos($adminProfileImage, 'vecteezy.com/free-vector/default-profile-picture') !== false) {
            $profile = "/public/images/user-3296.png";
        } elseif (strpos($adminProfileImage, 'googleusercontent.com') !== false) {
            $profile = $adminProfileImage;
        } elseif (strpos($adminProfileImage, 'http') === 0) {
            $profile = $adminProfileImage;
        } else {
            $profile = "/cms-data/profile-images/" . ltrim($adminProfileImage, '/');
        }
    } else {
        $profile = "/public/images/user-3296.png";
    }
} else {
    header("Location: /login");
    exit;
}

function extractName($email): string
{
    if (!is_string($email)) {
        return "";
    }

    $atPosition = strpos($email, "@");
    if ($atPosition === false) {
        return $email;
    }

    return substr($email, 0, $atPosition);
}

function checkEmailAllowed(string $email, array $allowedEmails)
{
    if (!in_array($email, $allowedEmails)) {
        die("User not allowed");
    }
}
