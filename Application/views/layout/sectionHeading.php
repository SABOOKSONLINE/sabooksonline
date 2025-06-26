<?php
function renderSectionHeading($heading, $subheading, $button, $path)
{
?>
    <div class="row align-items-center justify-content-between">
        <!-- Left Content -->
        <div class="col-md-8 col-12 mb-3 mb-md-0">
            <h1 class="typo-heading"><?= $heading ?></h1>
            <p class="typo-subheading">
                <?= $subheading ?>
            </p>
        </div>

        <!-- Right Button -->
        <div class="col-md-auto col-12 text-md-end">
            <a href="<?= $path ?>" class="typo-link">
                <?= $button ?>
                <div class="fas fa-arrow-right"></div>
            </a>
        </div>
    </div>
<?php } ?>