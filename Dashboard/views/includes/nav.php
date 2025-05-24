<?php

$cookieDomain = ".sabooksonline.co.za";
session_set_cookie_params(0, '/', $cookieDomain);

session_start();
$userKey = $_SESSION['ADMIN_USERKEY'];

if (isset($userKey)) {
    $adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'];

    if (strpos($adminProfileImage, 'googleusercontent.com') !== false) {
        $profile = $adminProfileImage;
    } else {
        $profile = "https://sabooksonline.co.za/cms-data/profile-images/" . $adminProfileImage;
    }
} else {
    header("Location: /login");
    exit;
}
?>
<nav class="navbar navbar-expand-xl navbar-light fixed-top border-bottom bg-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="https://11-july-2023.sabooksonline.co.za/public/images/sabo_logo.png" alt="sabooksonline logo" width="80">
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
                    <a class="nav-link" href="/dashboards/bookshelf">Library</a>
                </li>
            </ul>

            <div class="d-none d-xl-flex">
                <div class="btn-group">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary rounded-circle p-0 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 48px; height: 48px;">
                            <img src="<?php echo htmlspecialchars($profile); ?>" alt="Admin Profile"
                                class="rounded-circle"
                                style="width: 100%; height: 100%; object-fit: cover;
                                        border: 2px solid #dee2e6;
                                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="/dashboards/profile">Profile</a></li>
                            <li><a class="dropdown-item" href="/dashboards/account-billing">Account Billing</a></li>
                            <li><a class="dropdown-item" href="/dashboards/subscription-plans">Subscription Plans</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>