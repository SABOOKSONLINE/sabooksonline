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
            </div>
        </div>

        <div class="mt-4 pt-2 mt-auto">
            <p class="text-muted py-3 ps-2 small fw-bold border-bottom">
                <i class="fas fa-user-circle me-1"></i> My Account
            </p>

            <div class="nav flex-column gap-1">
                <a href="/dashboards/profile" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/profile') === 0) ? 'active' : ''; ?>">
                    <i class="fas fa-user-edit me-2"></i> My Profile
                </a>
                <a href="../includes/logout" class="nav-link py-2 px-3 rounded-3 text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>