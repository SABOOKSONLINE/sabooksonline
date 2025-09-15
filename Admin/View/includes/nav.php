<?php
require_once __DIR__ . "/../../Helpers/session_setup.php";
require_once __DIR__ . "/../../../Application/views/util/urlRedirect.php";

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
                    <img src="<?= $profile ?>" alt="Admin Profile"
                        class="rounded-circle"
                        style="width: 48px; height: 48px; object-fit: cover;
                                        border: 2px solid #dee2e6;
                                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="/dashboards/profile">Profile</a></li>
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
            <div class="d-none d-xl-flex ms-auto align-items-center">
                <div class="me-3 text-end">
                    <span class="text-dark fw-medium text-capitalize mb-1">
                        <?= $userName ?>
                    </span>
                    <br>
                    <small class="text-muted">Admin</small>
                </div>
                <div class="btn-group">
                    <div class="dropdown">
                        <button class="btn rounded-circle p-0 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 48px; height: 48px;">
                            <img src="<?= html_entity_decode($profile)  ?>" alt="<?= $userName ?> Profile"
                                class="rounded-circle"
                                style="width: 100%; height: 100%; object-fit: cover;
                                        border: 2px solid #dee2e6;
                                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>