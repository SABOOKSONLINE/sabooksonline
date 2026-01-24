<?php

require_once __DIR__ . "/../util/helpers.php";
require_once __DIR__ . "/../layout/sectionHeading.php";

// saveRedirectPage();
// echo $_SESSION["redirect_after_login"];

require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../../models/PageVisitsModel.php";
require_once __DIR__ . "/../../controllers/PageVisitsController.php";

require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../../models/CartModel.php";
require_once __DIR__ . "/../../controllers/CartController.php";
$cartController = new CartController($conn);
$cartItemsCount = 0;

require_once __DIR__ . "/../../controllers/HomeController.php";
$homeController = new HomeController($conn);

$tracker = new PageVisitsController($conn);
$tracker->trackVisits();

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
    $cartItemsCount = $cartController->getItemsCount();
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

require_once __DIR__ . "/../util/urlRedirect.php";
?>

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NVQBTH7N"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<?php include __DIR__ . "/stickyBanner.php"; ?>
<?php include __DIR__ . "/popupBanner.php"; ?>

<!-- <div style="width: 100%;height: 20px;background: url(../../../img/brand/02.jpg);background-size:contain;"></div> -->

<?php
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
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count" style="font-size: 0.7em;">
                                    0
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px;">
                                <li class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-bell me-2"></i>Notifications</span>
                                    <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                                        <i class="fas fa-check"></i> Mark All Read
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <div id="notificationList" class="notification-list" style="max-height: 400px; overflow-y: auto;">
                                        <div class="text-center text-muted p-3">
                                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                            <p class="mb-0">No notifications yet</p>
                                        </div>
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
                <div class="d-grid gap-2">
                    <a href="/login" class="btn btn-danger btn-red">Login <i class="fas fa-sign-in-alt"></i></a>
                </div>
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
// Notification functionality
let notifications = [];
let notificationCount = 0;

// Load notifications when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    
    // Check for new notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});

function loadNotifications() {
    fetch('/api/user/notifications')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notifications = data.notifications || [];
                updateNotificationDisplay();
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
        });
}

function updateNotificationDisplay() {
    const unreadCount = notifications.filter(n => !n.read).length;
    const countBadge = document.getElementById('notification-count');
    const notificationList = document.getElementById('notificationList');
    
    // Update count badge
    countBadge.textContent = unreadCount;
    countBadge.style.display = unreadCount > 0 ? 'block' : 'none';
    
    // Update notification list
    if (notifications.length === 0) {
        notificationList.innerHTML = `
            <div class="text-center text-muted p-3">
                <i class="fas fa-bell-slash fa-2x mb-2"></i>
                <p class="mb-0">No notifications yet</p>
            </div>
        `;
    } else {
        notificationList.innerHTML = notifications.map(notification => `
            <div class="notification-item ${!notification.read ? 'unread' : ''}" 
                 onclick="markAsRead(${notification.id})" 
                 data-url="${notification.action_url || '#'}">
                <div class="notification-title">${notification.title}</div>
                <div class="notification-message">${notification.message}</div>
                <div class="notification-time">${formatTime(notification.created_at)}</div>
            </div>
        `).join('');
    }
}

function markAsRead(notificationId) {
    fetch('/api/user/notifications/read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update local notification status
            const notification = notifications.find(n => n.id === notificationId);
            if (notification) {
                notification.read = true;
                updateNotificationDisplay();
                
                // Navigate to action URL if available
                if (notification.action_url) {
                    window.location.href = notification.action_url;
                }
            }
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

function markAllAsRead() {
    fetch('/api/user/notifications/read-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            notifications.forEach(n => n.read = true);
            updateNotificationDisplay();
        }
    })
    .catch(error => console.error('Error marking all notifications as read:', error));
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) {
        return 'Just now';
    } else if (diff < 3600000) {
        return Math.floor(diff / 60000) + 'm ago';
    } else if (diff < 86400000) {
        return Math.floor(diff / 3600000) + 'h ago';
    } else {
        return Math.floor(diff / 86400000) + 'd ago';
    }
}
</script>

<div class="collapse container-fluid bg-light p-3" id="mobileSearch">
    <form class="d-flex" action="/library" method="GET">
        <input class="form-control" type="search" placeholder="Search Title or Publisher" aria-label="Search" name="k" value="<?= htmlspecialchars($_GET['k'] ?? '') ?>">
    </form>
</div>