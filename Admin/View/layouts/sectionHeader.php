<?php
function renderSectionHeader($heading, $subheading = "", $button = "", $path = "", $btnType = "button")
{
?>
    <div class="row align-items-center justify-content-between mt-5 mb-3">
        <!-- Left Content -->
        <div class="col-md-8 col-12">
            <h1 class="h2 fw-bold"><?= htmlspecialchars($heading) ?></h1>
            <?php if (!empty($subheading)): ?>
                <p class="text-muted mb-0"><?= htmlspecialchars($subheading) ?></p>
            <?php endif; ?>
        </div>

        <!-- Right Button -->
        <?php if (!empty($button)): ?>
            <div class="col-md-auto col-12 text-md-end">
                <button
                    type="<?= htmlspecialchars($btnType) ?>"
                    class="btn btn-outline-dark"
                    <?php if (!empty($path)): ?> onclick="window.location.href='<?= htmlspecialchars($path) ?>'" <?php endif; ?>>
                    <?= htmlspecialchars($button) ?>
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php } ?>