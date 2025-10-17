<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION["sticky_banner_active"] = true;


if ($_SESSION["sticky_banner_active"]): ?>

    <nav class="navbar" id="sticky-banner">
        <div class="sticky-slider">
            <div class="container-fluid d-flex justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="typo-heading banner-heading mb-1">
                        Introducing SA Books Academic 1
                    </h5>
                    <p class="typo-subheading banner-subheading mb-0">
                        Empowering students, educators, and researchers with quality academic content.
                    </p>
                </div>

                <div class="d-flex align-items-center">
                    <a href="/library/academic" class="btn btn-white">
                        Explore Now <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="container-fluid d-flex justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="typo-heading banner-heading mb-1">
                        Introducing SA Books Academic 2
                    </h5>
                    <p class="typo-subheading banner-subheading mb-0">
                        Empowering students, educators, and researchers with quality academic content.
                    </p>
                </div>

                <div class="d-flex align-items-center">
                    <a href="/library/academic" class="btn btn-white">
                        Explore Now <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

<?php endif; ?>