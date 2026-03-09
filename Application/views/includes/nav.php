<?php

require_once __DIR__ . "/../util/helpers.php";
require_once __DIR__ . "/../layout/sectionHeading.php";

if (!isset($conn)) {
    require_once __DIR__ . "/../../Config/connection.php";
}

require_once __DIR__ . "/../../models/PageVisitsModel.php";
require_once __DIR__ . "/../../controllers/PageVisitsController.php";

require_once __DIR__ . "/../../models/CartModel.php";
require_once __DIR__ . "/../../controllers/CartController.php";

if (isset($conn)) {
    $cartController = new CartController($conn);
    $cartItemsCount = $cartController->getItemsCount();
} else {
    $cartItemsCount = 0;
}

require_once __DIR__ . "/../../controllers/HomeController.php";
if (isset($conn)) {
    $homeController = new HomeController($conn);
    $tracker = new PageVisitsController($conn);
    $tracker->trackVisits();
}

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
    if (isset($cartController)) {
        $cartItemsCount = $cartController->getItemsCount();
    }
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

$userName = $_SESSION['ADMIN_NAME'] ?? '';
$firstName = !empty($userName) ? explode(' ', trim($userName))[0] : '';
$ordersLabel = !empty($firstName) ? htmlspecialchars($firstName) . "'s Orders" : "Your Orders";

require_once __DIR__ . "/../util/urlRedirect.php";
?>

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVQBTH7N"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<?php include __DIR__ . "/stickyBanner.php"; ?>
<?php include __DIR__ . "/popupBanner.php"; ?>

<?php
$notifications = [];
$unreadCount = 0;

if (isset($_SESSION['ADMIN_EMAIL'])) {
    try {
        require_once __DIR__ . "/../../models/NotificationModel.php";
        $notificationModel = new NotificationModel();
        $notifications = $notificationModel->getUserNotifications($_SESSION['ADMIN_EMAIL'], 5);
        $unreadCount = $notificationModel->getUnreadCount($_SESSION['ADMIN_EMAIL']);
    } catch (Exception $e) {
        error_log("Notification loading error in nav: " . $e->getMessage());
        $notifications = [];
        $unreadCount = 0;
    }
}

$navItems = [
    ["title" => "Home", "url" => "/"],
    ["title" => "Library", "dropdown" => [
        ["title" => "All Books", "url" => "/library"],
        ["title" => "Academic Collection", "url" => "/library/academic"],
        ["title" => "Media Hub", "url" => "/media"],
    ]],
    ["title" => "Community", "dropdown" => [
        ["title" => "Events", "url" => "/events"],
        ["title" => "Membership Pricing", "url" => "/membership"]
    ]],
    ["title" => "Sell", "url" => "/sell"],
    ["title" => "Our Story", "url" => "/about"],
    ["title" => "Our Services", "url" => "/services"],
];
?>

<nav class="navbar navbar-expand-xl navbar-light bg-white border-bottom">
    <div class="container-fluid gap-3">

        <!-- Logo -->
        <a class="navbar-brand pe-3 border-end" href="/">
            <img src="/public/images/sabo_logo.png" alt="sabooksonline logo" height="42">
        </a>

        <!-- Desktop links (collapse on mobile) -->
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-xl-0">
                <?php foreach ($navItems as $item): ?>
                    <?php if (isset($item['dropdown'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <?= $item['title'] ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach ($item['dropdown'] as $sub): ?>
                                    <li><a class="dropdown-item" href="<?= $sub['url'] ?>"><?= $sub['title'] ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <!-- Mobile login -->
            <div class="d-xl-none p-3">
                <?php if (!isset($_SESSION['ADMIN_USERKEY'])): ?>
                    <a href="/login" class="btn btn-danger btn-red w-100">Login <i class="fas fa-sign-in-alt"></i></a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right side: search, bell, cart, profile, burger — always visible -->
        <div class="d-flex align-items-center gap-3 ms-auto">

            <!-- Search: input on desktop, icon on mobile -->
            <form class="d-none d-xl-flex" action="/library" method="GET">
                <input class="form-control nav-search" type="search" placeholder="Search Title or Publisher" name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
            </form>
            <button class="btn border-0 nav-icon d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch">
                <i class="fas fa-search"></i>
            </button>

            <?php if ($profile != null && isset($_SESSION['ADMIN_USERKEY'])): ?>

                <!-- Bell -->
                <div class="dropdown">
                    <button class="nav-icon btn p-0 position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count" style="font-size: 0.7em; <?= $unreadCount > 0 ? '' : 'display:none;' ?>">
                            <?= $unreadCount ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end nav-notification-menu">
                        <li class="dropdown-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-bell me-2"></i>Notifications</span>
                            <?php if ($unreadCount > 0): ?>
                                <button class="btn btn-sm btn-outline-primary" onclick="markAllAsReadNav()">
                                    <i class="fas fa-check"></i> Mark All Read
                                </button>
                            <?php endif; ?>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
                                <?php if (empty($notifications)): ?>
                                    <div class="text-center text-muted p-3">
                                        <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                        <p class="mb-0">No notifications yet</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <div class="notification-item <?= !$notification['read'] ? 'unread' : '' ?>"
                                            onclick="markAsReadNav(<?= $notification['id'] ?>, '<?= htmlspecialchars($notification['action_url'] ?? '') ?>')"
                                            style="cursor: <?= $notification['action_url'] ? 'pointer' : 'default' ?>;">
                                            <div class="notification-title"><?= htmlspecialchars($notification['title']) ?></div>
                                            <div class="notification-message"><?= htmlspecialchars(substr($notification['message'], 0, 60)) ?><?= strlen($notification['message']) > 60 ? '...' : '' ?></div>
                                            <div class="notification-time"><?= NotificationModel::formatTime($notification['created_at']) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center" href="/notifications"><i class="fas fa-eye me-2"></i>View All</a></li>
                    </ul>
                </div>

                <!-- Cart -->
                <a href="/cart" class="nav-icon position-relative text-decoration-none">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count" style="font-size: 0.7em;">
                        <?= $cartItemsCount ?>
                    </span>
                </a>

                <!-- Profile -->
                <div class="dropdown">
                    <button class="btn p-0 nav-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <img src="<?= $profile ?>" alt="Profile" class="rounded-circle nav-profile-img nav-profile-img-lg">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/dashboards"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                        <li><a class="dropdown-item" href="/dashboards/bookshelf"><i class="fas fa-book me-2"></i> My Library</a></li>
                        <li><a class="dropdown-item" href="/orders"><i class="fas fa-shopping-bag me-2"></i> <?= $ordersLabel ?></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>

            <?php else: ?>
                <a href="/login" class="btn btn-red d-none d-xl-inline-flex">Login <i class="fas fa-sign-in-alt"></i></a>
            <?php endif; ?>

            <!-- Burger — always last -->
            <button class="navbar-toggler border-0 d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fas fa-bars"></span>
            </button>

        </div>
    </div>
</nav>

<!-- Mobile Quick Nav -->
<nav class="mobile-quick-nav d-xl-none">
    <div class="mobile-quick-nav-inner">
        <a href="/" class="mobile-quick-link">Home</a>
        <a href="/library" class="mobile-quick-link">All Books</a>
        <a href="/library/academic" class="mobile-quick-link">Academic</a>
        <a href="/media" class="mobile-quick-link">Media</a>
        <a href="/sell" class="mobile-quick-link">Sell</a>
        <a href="/about" class="mobile-quick-link">Our Story</a>
        <a href="/services" class="mobile-quick-link">Services</a>
    </div>
</nav>

<!-- Mobile Search -->
<div class="collapse bg-light border-bottom d-xl-none" id="mobileSearch">
    <div class="container-fluid p-3">
        <form class="d-flex" action="/library" method="GET">
            <input class="form-control" type="search" placeholder="Search Title or Publisher" name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
        </form>
    </div>
</div>

<script>
    function markAsReadNav(notificationId, actionUrl) {
        fetch('/notifications/action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'mark_read',
                    notification_id: notificationId
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (actionUrl && actionUrl !== '#') window.location.href = actionUrl;
                    else window.location.reload();
                }
            })
            .catch(() => {
                if (actionUrl && actionUrl !== '#') window.location.href = actionUrl;
            });
    }

    function markAllAsReadNav() {
        fetch('/notifications/action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'mark_all_read'
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) window.location.reload();
            })
            .catch(() => alert('Error updating notifications. Please refresh the page.'));
    }
</script>