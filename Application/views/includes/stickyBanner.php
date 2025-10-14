<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION["sticky_banner_active"] = true;


if ($_SESSION["sticky_banner_active"]): ?>

    <nav class="navbar" id="sticky-banner">
        <div class="container-fluid d-flex justify-content-between align-items-center gap-3">
            <div>
                <h5 class="typo-heading banner-heading mb-1">
                    Introducing SA Books Academic
                </h5>
                <p class="typo-subheading banner-subheading mb-0">
                    Empowering students, educators, and researchers with quality academic content.
                </p>
            </div>

            <div class="d-flex align-items-center">
                <a href="/library/academic" class="btn btn-white">
                    Explore Now <i class="fas fa-arrow-right ms-1"></i>
                </a>

                <!-- <button
                    type="button"
                    id="close-banner"
                    aria-label="Close banner"
                    class="btn-close-icon">
                    <i class="fas fa-times"></i>
                </button> -->
            </div>
        </div>
    </nav>

<?php endif; ?>