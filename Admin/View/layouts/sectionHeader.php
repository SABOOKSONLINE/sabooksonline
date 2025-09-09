<?php
function renderSectionHeader($heading, $subheading = "")
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
    </div>
<?php } ?>