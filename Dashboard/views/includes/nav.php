<?php



if (session_status() === PHP_SESSION_NONE) {
    $cookieDomain = ".sabooksonline.co.za";
    session_set_cookie_params(0, '/', $cookieDomain);
    session_start();
}
$userKey = $_SESSION['ADMIN_USERKEY'];
$userId = $_SESSION['ADMIN_ID'];

if (isset($userKey)) {
    $adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'] ?? null;

    if (isset($adminProfileImage)) {

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
?>
<nav class="navbar navbar-expand-xl navbar-light fixed-top border-bottom bg-white">
    <div class="container-fluid">
        <button class="btn me-3 d-lg-none" type="button" id="sidebarToggle">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <a class="navbar-brand pe-3 ms-3" href="/">
            <img src="/public/images/sabo_logo.png" alt="sabooksonline logo" height="42">
        </a>

        <div class="d-flex order-xl-last">
            <div class="dropdown d-xl-none">
                <button class="btn btn-outline-secondary rounded-circle p-0 dropdown-toggle me-3" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 48px; height: 48px;">
                    <img src="<?php echo htmlspecialchars($profile); ?>" alt="Admin Profile"
                        class="rounded-circle"
                        style="width: 48px; height: 48px; object-fit: cover;
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

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fas fa-bars"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="d-none d-xl-flex ms-auto me-3">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/library">Library</a>
                    </li>
                </ul>
            </div>
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
                            <li><a class="dropdown-item" href="/membership">Subscription Plans</a></li>
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