<?php

require_once __DIR__ . "/../util/helpers.php";
require_once __DIR__ . "/../layout/sectionHeading.php";

// saveRedirectPage();
// echo $_SESSION["redirect_after_login"];

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

// Generate personalized orders label
$userName = $_SESSION['ADMIN_NAME'] ?? '';
$firstName = !empty($userName) ? explode(' ', trim($userName))[0] : '';
$ordersLabel = !empty($firstName) ? htmlspecialchars($firstName) . "'s Orders" : "Your Orders";

require_once __DIR__ . "/../util/urlRedirect.php";
?>

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVQBTH7N"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<?php include __DIR__ . "/stickyBanner.php"; ?>
<?php include __DIR__ . "/popupBanner.php"; ?>

<!-- <div style="width: 100%;height: 20px;background: url(../../../img/brand/02.jpg);background-size:contain;"></div> -->

<?php
// Load notifications for dropdown using traditional model
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


<nav class="navbar navbar-expand-xl navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand pe-3 border-end" href="/">
            <img src="/public/images/sabo_logo.png" alt="sabooksonline logo" height="42">
        </a>

        <div class="d-flex order-xl-last align-items-center" style="gap: 0.75rem;">
            <!-- Burger Menu (Leftmost) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fas fa-bars"></span>
            </button>

            <!-- Search (Middle) -->
            <button class="btn border-0 navbar-toggler d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch">
                <i class="fas fa-search"></i>
            </button>

            <?php if ($profile != null && isset($_SESSION['ADMIN_USERKEY'])): ?>
                <!-- Mobile Cart (After Search) -->
                <a href="/cart" class="position-relative text-decoration-none text-dark d-xl-none" title="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count-mobile" style="font-size: 0.7em;">
                        <?= $cartItemsCount ?>
                    </span>
                </a>

                <!-- Mobile Profile Dropdown (Far Right) -->
                <div class="dropdown d-xl-none" style="margin-right: 0.75rem; position: relative; z-index: 1001;">
                    <button class="btn btn-outline-secondary rounded-circle p-0 dropdown-toggle" type="button" id="mobileProfileDropdownHeader" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;">
                        <img src="<?= $profile ?>" alt="Admin Profile" class="rounded-circle" style="width:100%; height:100%; object-fit:cover; border:2px solid #dee2e6; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileProfileDropdownHeader" style="z-index: 1001; position: absolute;">
                        <li><a class="dropdown-item" href="/dashboards"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                        <li><a class="dropdown-item" href="/dashboards/bookshelf"><i class="fas fa-book me-2"></i> My Library</a></li>
                        <li><a class="dropdown-item" href="/orders"><i class="fas fa-shopping-bag me-2"></i> <?= $ordersLabel ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <?php foreach ($navItems as $item): ?>
                    <?php 
                    // Hide Library items on mobile since they have their own nav bar
                    $isLibraryItem = ($item['title'] === 'Library');
                    $libraryClass = $isLibraryItem ? ' d-none d-xl-block' : '';
                    ?>
                    <?php if (isset($item['dropdown'])): ?>
                        <li class="nav-item dropdown<?= $libraryClass ?>">
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
                        <li class="nav-item<?= $libraryClass ?>">
                            <a class="nav-link" href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <!-- Search -->
            <div class="d-none d-xl-flex">
                <form class="d-flex me-3" action="/library" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search Title or Publisher" name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
                    </div>
                </form>

                <!-- Profile / Login -->
                <div class="d-flex align-items-center">
                    <?php if ($profile != null && isset($_SESSION['ADMIN_USERKEY'])): ?>
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <button class="btn btn-link text-dark p-0 position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count" style="font-size: 0.7em; <?= $unreadCount > 0 ? '' : 'display: none;' ?>">
                                    <?= $unreadCount ?>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px;">
                                <li class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-bell me-2"></i>Notifications</span>
                                    <?php if ($unreadCount > 0): ?>
                                        <button class="btn btn-sm btn-outline-primary" onclick="markAllAsReadNav()">
                                            <i class="fas fa-check"></i> Mark All Read
                                        </button>
                                    <?php endif; ?>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
                                        <?php if (empty($notifications)): ?>
                                            <div class="text-center text-muted p-3">
                                                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                                <p class="mb-0">No notifications yet</p>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($notifications as $notification): ?>
                                                <div class="notification-item px-3 py-2 <?= !$notification['read'] ? 'unread' : '' ?>" 
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
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-center" href="/notifications">
                                        <i class="fas fa-eye me-2"></i>View All Notifications
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Cart -->
                        <a href="/cart" class="position-relative me-3 text-decoration-none text-dark">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                                <?= $cartItemsCount ?>
                            </span>
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary rounded-circle p-0 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 48px; height: 48px;">
                                <img src="<?= $profile ?>" alt="Admin Profile" class="rounded-circle" style="width:100%; height:100%; object-fit:cover; border:2px solid #dee2e6; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
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
                        <a href="/login" class="btn btn-red">Login <i class="fas fa-sign-in-alt"></i></a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile Sign In -->
            <div class="d-xl-none bg-light p-3">
                <?php if (!isset($_SESSION['ADMIN_USERKEY'])): ?>
                    <div class="d-grid gap-2">
                        <a href="/login" class="btn btn-danger btn-red">Login <i class="fas fa-sign-in-alt"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Library Quick Access Bar - Simple Text Links -->
<nav class="navbar navbar-expand d-xl-none bg-white border-bottom fixed-top" style="top: 60px; z-index: 999; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 0.5rem 0;">
    <div class="container-fluid px-2">
        <div class="d-flex overflow-x-auto w-100" style="scrollbar-width: none; -ms-overflow-style: none;">
            <div class="d-flex gap-3 align-items-center" style="flex-wrap: nowrap; padding: 0 1rem;">
                <a href="/" class="text-decoration-none text-dark" style="white-space: nowrap; font-size: 14px; font-weight: 500;">
                    Home
                </a>
                <a href="/library" class="text-decoration-none text-dark" style="white-space: nowrap; font-size: 14px; font-weight: 500;">
                    All Books
                </a>
                <a href="/library/academic" class="text-decoration-none text-dark" style="white-space: nowrap; font-size: 14px; font-weight: 500;">
                    Academic
                </a>
                <a href="/media" class="text-decoration-none text-dark" style="white-space: nowrap; font-size: 14px; font-weight: 500;">
                    Media
                </a>
                <a href="/events" class="text-decoration-none text-dark" style="white-space: nowrap; font-size: 14px; font-weight: 500;">
                    Events
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
.notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid #e9ecef;
    cursor: pointer;
    transition: background-color 0.2s;
}
.notification-item:hover {
    background-color: #f8f9fa;
}
.notification-item.unread {
    background-color: #e3f2fd;
    border-left: 3px solid #2196f3;
}
.notification-item .notification-title {
    font-weight: 600;
    margin-bottom: 4px;
}
.notification-item .notification-message {
    color: #6c757d;
    font-size: 0.9em;
    margin-bottom: 4px;
}
.notification-item .notification-time {
    color: #adb5bd;
    font-size: 0.8em;
}
</style>

<script>
// Traditional notification functionality (server-rendered with PHP)
function markAsReadNav(notificationId, actionUrl) {
    fetch('/notifications/action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            action: 'mark_read',
            notification_id: notificationId 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Navigate to action URL if provided, otherwise refresh to update display
            if (actionUrl && actionUrl !== '#') {
                window.location.href = actionUrl;
            } else {
                window.location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        // Navigate anyway if there's an action URL
        if (actionUrl && actionUrl !== '#') {
            window.location.href = actionUrl;
        }
    });
}

function markAllAsReadNav() {
    fetch('/notifications/action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            action: 'mark_all_read'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh page to show updated state
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
        alert('Error updating notifications. Please refresh the page.');
    });
}

// formatTime functionality now handled by PHP NotificationModel::formatTime()
</script>

<div class="collapse container-fluid bg-light p-3" id="mobileSearch">
    <form class="d-flex" action="/library" method="GET">
        <input class="form-control" type="search" placeholder="Search Title or Publisher" aria-label="Search" name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
    </form>
</div>