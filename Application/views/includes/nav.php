<?php

require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../../models/PageVisitsModel.php";
require_once __DIR__ . "/../../controllers/PageVisitsController.php";

$tracker = new PageVisitsController($conn);
$tracker->trackVisits();

$cookieDomain = ".sabooksonline.co.za";
session_set_cookie_params(0, '/', $cookieDomain);

session_start();
$userKey = $_SESSION['ADMIN_USERKEY'] ?? "";

if (isset($userKey) && !empty($userKey)) {
    $adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'];

    if (strpos($adminProfileImage, 'googleusercontent.com') !== false) {
        $profile = $adminProfileImage;
    } else {
        $profile = "/public/images/user-3296.png";
    }
} else {
    $profile = null;
}
?>

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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle show" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                        Community
                    </a>
                    <ul class="dropdown-menu" data-bs-popper="static">
                        <li><a class="dropdown-item" href="/events">Events</a></li>
                        <li><a class="dropdown-item" href="/providers">Service Providers</a></li>
                        <li><a class="dropdown-item" href="/membership">Membership Pricing</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/services">Our Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/gallery">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">Our Story</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
            </ul>

            <div class="d-none d-xl-flex">
                <form class="d-flex me-3" action="/library" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search Title or Publisher" aria-label="Search" name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
                    </div>
                </form>

                <div class="    ">
                    <?php
                    if ($profile != null && isset($_SESSION['ADMIN_USERKEY'])) {
                    ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary rounded-circle p-0 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 48px; height: 48px;">
                                <img src="<?= $profile ?>" alt="Admin Profile"
                                    class="rounded-circle"
                                    style="width: 100%; height: 100%; object-fit: cover;
                                            border: 2px solid #dee2e6;
                                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li>
                                    <a class="dropdown-item" href="/dashboards">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/dashboards/bookshelf">
                                        <i class="fas fa-book me-2"></i> My Library
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="/logout">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php
                    } else {
                    ?>
                        <a href="/signup" class="btn btn-black">Sign Up</a>
                        <a href="/login" class="btn btn-red">Login <i class="fas fa-sign-in-alt"></i></a>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="d-xl-none bg-light p-3">
                <div class="d-grid gap-2">
                    <a href="/signup" class="btn btn-black">Sign Up</a>
                    <a href="/login" class="btn btn-danger btn-red">Login <i class="fas fa-sign-in-alt"></i></a>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="collapse container-fluid bg-light p-3" id="mobileSearch">
    <form class="d-flex" action="/library" method="GET">
        <input class="form-control" type="search" placeholder="Search Title or Publisher" aria-label="Search" name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
    </form>
</div>