<?php
$current_path = $_SERVER['REQUEST_URI'];
$subscriptionPlan = $_SESSION['ADMIN_SUBSCRIPTION'];
?>
<div class="col-lg-3 col-xl-2 position-fixed dash-sidebar p-0 bg-white">
    <div class="dashboard__sidebar d-none d-lg-flex flex-column hv-100 pe-3 ps-3 pb-3" id="dashboardSidebar">
        <div class=" mb-auto">
            <?php if (isset($subscriptionPlan) && strtolower($subscriptionPlan) == 'pro' || strtolower($subscriptionPlan) == 'premium'): ?>
                <?= $subscriptionPlan ?>
                <p class="text-muted py-3 ps-2 small fw-bold border-bottom">
                    <i class="fas fa-info-circle ms-1 me-1" data-bs-toggle="tooltip" data-bs-placement="right" title="Add books, events, services, and manage reviews"></i>
                    Start Here
                </p>

                <div class="nav flex-column gap-1">
                    <a href="/dashboards" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo ($current_path == '/dashboards' || $current_path == '/dashboards/') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="/dashboards/listings" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/listings') === 0) ? 'active' : ''; ?>">
                        <i class="fas fa-book me-2"></i> Book Listings
                    </a>
                    <a href="/dashboards/events" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/events') === 0) ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-alt me-2"></i> Manage Events
                    </a>
                    <a href="/dashboards/services" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/services') === 0) ? 'active' : ''; ?>">
                        <i class="fas fa-tools me-2"></i> Manage Services
                    </a>
                    <a href="/dashboards/reviews" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/reviews') === 0) ? 'active' : ''; ?>">
                        <i class="fas fa-star me-2"></i> Reviews
                    </a>
                </div>
            <?php endif; ?>

            <p class="text-muted py-3 ps-2 small fw-bold border-bottom">
                <i class="fas fa-book-open ms-1 me-1" data-bs-toggle="tooltip" data-bs-placement="right" title="Your personal library"></i>
                My Library
            </p>


            <div class="nav flex-column gap-1">
                <a href="/dashboards/bookshelf" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/bookshelf') === 0) ? 'active' : ''; ?>">
                    <i class="fas fa-book-reader me-2"></i> Bookshelf
                </a>
                <a href="/dashboards/audiobooks" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/audiobooks') === 0) ? 'active' : ''; ?>">
                    <i class="fas fa-headphones me-2"></i> Audiobooks
                </a>
            </div>
        </div>

        <div class="mt-4 pt-2 mt-auto">
            <p class="text-muted py-3 ps-2 small fw-bold border-bottom">
                <i class="fas fa-user-circle me-1"></i> My Account
            </p>

            <div class="nav flex-column gap-1">
                <a href="/dashboards/billing" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/billing') === 0) ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Account Billing
                </a>
                <a href="/dashboards/subscriptions" class="nav-link py-2 px-3 rounded-3 text-dark <?php echo (strpos($current_path, '/dashboards/subscriptions') === 0) ? 'active' : ''; ?>">
                    <i class="fas fa-credit-card me-2"></i> Subscription Plans
                </a>
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