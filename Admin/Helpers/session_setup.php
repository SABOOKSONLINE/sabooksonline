<?php
if (session_status() === PHP_SESSION_NONE) {
    $cookieDomain = ".sabooksonline.co.za";
    session_set_cookie_params(0, '/', $cookieDomain);
    session_start();
}

$userKey = $_SESSION['ADMIN_USERKEY'];
$userId = $_SESSION['ADMIN_ID'];
$userName = extractName($_SESSION['ADMIN_EMAIL']);

$oldDefaultProfile = "https://www.vecteezy.com/free-vector/default-profile-picture";
$adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'] ?? null;

if ($userKey) {
    if ($adminProfileImage && $adminProfileImage !== $oldDefaultProfile) {
        if (strpos($adminProfileImage, 'googleusercontent.com') !== false) {
            $profile = $adminProfileImage;
        } else {
            $profile = "https://sabooksonline.co.za/cms-data/profile-images/" . $adminProfileImage;
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
