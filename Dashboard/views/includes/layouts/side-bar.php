<div class="dashboard__sidebar d-none d-lg-flex flex-column bg-light p-3">
    <div class="mb-auto">
        <p class="text-muted mb-3 mt-3 ps-2 small fw-bold">
            <i class="fas fa-info-circle ms-1 me-1" data-bs-toggle="tooltip" data-bs-placement="right" title="Add books, events, services and manage reviews"></i>
            Start Here
        </p>

        <div class="nav flex-column gap-1">
            <a href="/index.php" class="nav-link py-2 px-3 rounded-3 text-dark active">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            <a href="/views/book_listings.php" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-book me-2"></i> Book Listings
            </a>
            <a href="/views/manage_events.php" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-calendar-alt me-2"></i> Manage Events
            </a>
            <a href="/views/manage_services.php" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-concierge-bell me-2"></i> Manage Services
            </a>
            <a href="/views/manage_reviews.php" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-star me-2"></i> Reviews
            </a>
            <!-- <hr class="my-3">
            <a href="/views/manage_customers.php" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-users me-2"></i> Manage Customers
            </a>
            <a href="/views/manage_order.php" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-box me-2"></i> Manage Orders
            </a> -->
        </div>
    </div>

    <div class="mt-4 pt-2 mt-auto">
        <p class="text-muted mb-3 ps-2 small fw-bold">
            <i class="fas fa-user-circle me-1"></i> My Account
        </p>

        <div class="nav flex-column gap-1">
            <a href="/views/account_billing.php" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-file-invoice-dollar me-2"></i> Account Billing
            </a>
            <a href="plan" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-credit-card me-2"></i> Subscription Plans
            </a>
            <a href="/views/manage_profile.php" class="nav-link py-2 px-3 rounded-3 text-dark">
                <i class="fas fa-user-edit me-2"></i> My Profile
            </a>
            <a href="../includes/logout" class="nav-link py-2 px-3 rounded-3 text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>
</div>