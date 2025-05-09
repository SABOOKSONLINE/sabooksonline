<?php 

$cookieDomain = ".sabooksonline.co.za";
session_set_cookie_params(0, '/', $cookieDomain);

session_start();
if (isset($_SESSION['ADMIN_USERKEY'])) {
    $adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'];

    if (strpos($adminProfileImage, 'googleusercontent.com') !== false) {
        $profile = $adminProfileImage;
    } else {
        $profile = "https://sabooksonline.co.za/cms-data/profile-images/" . $adminProfileImage;
    }
} else {
    $profile = null;
}
?>
<div style="width: 100%;height: 20px;background: url(../../../img/brand/02.jpg);background-size:contain;"></div>

<nav class="navbar navbar-expand-xl navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="../../../public/images/sabo_logo.png" alt="sabooksonline logo" width="90">
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
                    <a class="nav-link" href="/about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
            </ul>

            <div class="d-none d-xl-flex">
                <form class="d-flex me-3" action="/library" method="GET">
                    <div class="input-group">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
                    </div>
                </form>

                <!-- <div class="btn-group">
                    <a href="/membership" class="btn btn-outline-danger btn-outline-red">Sign Up</a>
                    <a href="/login" class="btn btn-danger btn-red">LOGIN <i class="fas fa-sign-in-alt"></i></a>
                </div> -->
                <div class="btn-group">
                <?php

                if ($profile != null && isset($_SESSION['ADMIN_USERKEY'])) {
                    ?>
                    <a href="/dashboard" class="btn btn-outline-secondary rounded-circle p-0" style="width: 40px; height: 40px;">
                        <img src="<?= htmlspecialchars($profile) ?>" alt="Admin Profile" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                    </a>
                    <?php
                } else {
                    ?>
                    <a href="/membership" class="btn btn-outline-danger btn-outline-red">Sign Up</a>
                    <a href="/login" class="btn btn-danger btn-red">LOGIN <i class="fas fa-sign-in-alt"></i></a>
                    <?php
                }
                ?>
            </div>


            </div>

            <div class="d-xl-none bg-light p-3">
                <div class="d-grid gap-2">
                    <a href="/membership" class="btn btn-outline-danger btn-outline-red">Sign Up</a>
                    <a href="/login" class="btn btn-danger btn-red">LOGIN <i class="fas fa-sign-in-alt"></i></a>
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="collapse container-fluid bg-light p-3" id="mobileSearch">
    <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
    </form>
</div>