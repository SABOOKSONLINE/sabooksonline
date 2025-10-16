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
        <div class="d-flex order-xl-last">
            <button class="btn border-0 me-2 navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch">
                <i class="fas fa-search"></i>
            </button>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fas fa-bars"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/library">Library</a>
                </li>
                <li><a class="dropdown-item" href="/membership">Sell on SABO</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="/library/academic">Academic</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/media">Media</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/services">Our Services</a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
            </ul>

        </div>
    </div>
</nav>


