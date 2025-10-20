<?php
function renderSectionHeading($heading, $subheading, $button = "", $path = "#", $position = "")
{
    // Sanitize input values
    $heading = htmlspecialchars($heading);
    $subheading = htmlspecialchars($subheading);
    $button = htmlspecialchars($button);
    $path = htmlspecialchars($path);
    $positionClass = !empty($position) ? "text-$position" : "";
?>
    <div class="row align-items-center justify-content-between <?= $positionClass ?>">
        <!-- Left Content -->
        <?php if (empty($position)): ?>
            <div class="col-md-8 col-12 mb-3 mb-md-0">
                <h1 class="typo-heading"><?= $heading ?></h1>
                <?php if (!empty($subheading)): ?>
                    <p class="typo-subheading"><?= $subheading ?></p>
                <?php endif; ?>
            </div>

            <!-- Right Button -->
            <?php if (!empty($button)): ?>
                <div class="col-md-auto col-12 text-md-end">
                    <a href="<?= $path ?>" class="typo-link d-inline-flex align-items-center gap-2">
                        <?= $button ?>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="col-12 mb-3 text-<?= $position ?>">
                <h1 class="typo-heading"><?= $heading ?></h1>
                <?php if (!empty($subheading)): ?>
                    <p class="typo-subheading"><?= $subheading ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php
}
?>