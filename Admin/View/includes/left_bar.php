<?php
$current_path = $_SERVER['REQUEST_URI'];
?>

<div class="col-lg-3 col-xl-2 position-fixed dash-sidebar p-0 bg-white">
    <div class="dashboard__sidebar d-none d-lg-flex flex-column hv-100 pe-3 ps-3 pb-3" id="dashboardSidebar">
        <div class=" mb-auto">
            <p class="text-muted py-3 ps-2 small fw-bold border-bottom">
                <i class="fas fa-tachometer-alt me-1"></i> Dashboard
            </p>

            <div class="nav flex-column">
                <!-- Main Dashboard -->
                <div class="nav flex-column gap-1 mb-4">
                    <a href="/admin" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin' || $current_path == '/admin/') ? 'active' : ''; ?>">
                        <i class="fas fa-home me-2"></i>Overview
                    </a>
                    <a href="/admin/analytics" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/analytics' || $current_path == '/admin/analytics/') ? 'active' : ''; ?>">
                        <i class="fas fa-chart-line me-2"></i>Analytics
                    </a>
                </div>

                <!-- Content Management Section -->
                <div class="nav-section">
                    <p class="text-muted py-2 ps-2 small fw-bold border-bottom mb-2">
                        <i class="fas fa-file-alt me-1"></i> CONTENT MANAGEMENT
                    </p>
                    <div class="nav flex-column ms-2">
                        <a href="/admin/pages/home" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/pages/home') ? 'active' : ''; ?>">
                            <i class="fas fa-home me-2"></i>Home Page
                        </a>
                    </div>
                </div>

                <!-- User & Sales Management Section -->
                <div class="nav-section mt-4">
                    <p class="text-muted py-2 ps-2 small fw-bold border-bottom mb-2">
                        <i class="fas fa-users me-1"></i> USER & SALES
                    </p>
                    <div class="nav flex-column ms-2">
                        <a href="/admin/users" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/users' || $current_path == '/admin/users/') ? 'active' : ''; ?>">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a href="/admin/orders" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/orders' || $current_path == '/admin/orders/') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-bag-shopping me-2"></i>Orders
                        </a>
                        <a href="/admin/purchases" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/purchases' || $current_path == '/admin/purchases/') ? 'active' : ''; ?>">
                            <i class="fas fa-book me-2"></i>Book Purchases
                        </a>
                    </div>
                </div>

                <!-- Mobile App Section -->
                <div class="nav-section mt-4">
                    <p class="text-muted py-2 ps-2 small fw-bold border-bottom mb-2">
                        <i class="fas fa-mobile-alt me-1"></i> MOBILE APP
                    </p>
                    <div class="nav flex-column ms-2">
                        <a href="/admin/mobile/banners" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/admin/mobile/banners') ? 'active' : ''; ?>">
                            <i class="fas fa-images me-2"></i>Mobile Banners
                        </a>
                        <a href="/admin/mobile/notifications" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/admin/mobile/notifications') === 0) ? 'active' : ''; ?>">
                            <i class="fas fa-bell me-2"></i>Push Notifications
                        </a>
                        <a href="/admin/mobile/notifications/send" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/admin/mobile/notifications/send') === 0) ? 'active' : ''; ?>">
                            <i class="fas fa-paper-plane me-2"></i>Send Notification
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>