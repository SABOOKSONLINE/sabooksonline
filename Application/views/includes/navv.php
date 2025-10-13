<?php

require_once __DIR__ . "/../util/helpers.php";

// saveRedirectPage();
// echo $_SESSION["redirect_after_login"];



if (session_status() === PHP_SESSION_NONE) {
    $cookieDomain = ".sabooksonline.co.za";
    session_set_cookie_params(0, '/', $cookieDomain);
    session_start();
}

$userKey = $_SESSION['ADMIN_USERKEY'] ?? "";
$profileImage = "";
if (isset($_SESSION['ADMIN_PROFILE_IMAGE'])) {
    $profileImage = $_SESSION['ADMIN_PROFILE_IMAGE'];
}

if (isset($userKey)) {
    if (!empty($profileImage)) {
        if (strpos($profileImage, 'vecteezy.com/free-vector/default-profile-picture') !== false) {
            $profile = "/public/images/user-3296.png";
        } elseif (strpos($profileImage, 'googleusercontent.com') !== false) {
            $profile = $profileImage;
        } elseif (strpos($profileImage, 'http') === 0) {
            $profile = $profileImage;
        } else {
            $profile = "/cms-data/profile-images/" . ltrim($profileImage, '/');
        }
    } else {
        $profile = "/public/images/user-3296.png";
    }
} else {
    header("Location: /login");
    exit;
}

?>

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVQBTH7N"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<div style="width: 100%;height: 20px;background: url(../../../img/brand/02.jpg);background-size:contain;"></div>

<nav class="navbar navbar-expand-xl navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand pe-3 border-end" href="/">
            <img src="/public/images/sabo_logo.png" alt="sabooksonline logo" height="42">
        </a>

       

        
            <div class="d-xl-none bg-light p-3">
                <div class="d-grid gap-2">
                    <a href="/signup" class="btn btn-black">Sign Up</a>
                    <a href="/login" class="btn btn-danger btn-red">Login <i class="fas fa-sign-in-alt"></i></a>
                </div>
            </div>
        </div>
    </div>
</nav>


