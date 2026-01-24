<?php
$current_path = $_SERVER['REQUEST_URI'];
?>

<div class="col-lg-3 col-xl-2 position-fixed dash-sidebar p-0 bg-white">
    <div class="dashboard__sidebar d-none d-lg-flex flex-column hv-100 pe-3 ps-3 pb-3" id="dashboardSidebar">
        <div class=" mb-auto">
            <p class="text-muted py-3 ps-2 small fw-bold border-bottom">
                <i class="fas fa-tachometer-alt me-1"></i> Dashboard
            </p>

            <div class="nav flex-column gap-1">
                <a href="/admin" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin' || $current_path == '/admin/') ? 'active' : ''; ?>">
                    <i class="fas fa-home me-2"></i> Overview
                </a>
                <a href="/admin/analytics" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/analytics' || $current_path == '/admin/analytics/') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line me-2"></i> Analytics
                </a>

                <a class="nav-link py-2 px-3 rounded-3 text-dark d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" href="#pagesMenu" role="button" aria-expanded="false" aria-controls="pagesMenu">
                    <span><i class="fas fa-file-alt me-2"></i> Pages</span>
                    <i class="fas fa-chevron-down"></i>
                </a>

                <div class="collapse <?php echo (strpos($current_path, '/admin/pages') === 0) ? 'show' : ''; ?>" id="pagesMenu">
                    <div class="nav flex-column ms-3 mt-1">
                        <a href="/admin/pages/home" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/pages/home') ? 'active' : ''; ?>">
                            Home Page
                        </a>
                    </div>
                </div>

                <a href="/admin/users" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/users' || $current_path == '/admin/users/') ? 'active' : ''; ?>">
                    <i class="fas fa-users me-2"></i> Users
                </a>

                <a href="/admin/orders" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/orders' || $current_path == '/admin/orders/') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-bag-shopping me-2"></i> Orders
                </a>

                <a href="/admin/purchases" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/purchases' || $current_path == '/admin/purchases/') ? 'active' : ''; ?>">
                    <i class="fas fa-book me-2"></i> Book Purchases
                </a>

                <a class="nav-link py-2 px-3 rounded-3 text-dark d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" href="#mobileMenu" role="button" aria-expanded="false" aria-controls="mobileMenu">
                    <span><i class="fas fa-mobile-alt me-2"></i> Mobile App</span>
                    <i class="fas fa-chevron-down"></i>
                </a>

                <div class="collapse <?php echo (strpos($current_path, '/admin/mobile') === 0) ? 'show' : ''; ?>" id="mobileMenu">
                    <div class="nav flex-column ms-3 mt-1">
                        <a href="/admin/mobile/banners" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/mobile/banners') ? 'active' : ''; ?>">
                            <i class="fas fa-images me-2"></i> Mobile Banners
                        </a>
                        <a href="/admin/mobile/notifications" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/admin/mobile/notifications') === 0) ? 'active' : ''; ?>">
                            <i class="fas fa-bell me-2"></i> Push Notifications
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>