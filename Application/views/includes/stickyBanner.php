<?php
$uri = $_SERVER["REQUEST_URI"];

if ($uri !== "/media" && $uri !== "/library/academic"): ?>

    <nav class="navbar" id="sticky-banner">
        <div class="sticky-slider">
            <div class="">
                <div>
                    <h5 class="typo-heading banner-heading mb-1">
                        Explore Our Academic Collection
                    </h5>
                    <p class="typo-subheading banner-subheading mb-0">
                        Empowering students, educators, and researchers with high-quality textbooks and scholarly materials.
                    </p>
                </div>

                <div class="">
                    <a href="/library/academic" class="btn btn-white">
                        Browse Academic Books <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="">
                <div>
                    <h5 class="typo-heading banner-heading mb-1">
                        Discover the Media Hub
                    </h5>
                    <p class="typo-subheading banner-subheading mb-0">
                        Dive into the latest magazines, newspapers, and multimedia stories from across South Africa.
                    </p>
                </div>

                <div class="">
                    <a href="/media" class="btn btn-white">
                        Explore Media Content <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </nav>

<?php endif; ?>