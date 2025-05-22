<?php function renderHeading($heading, $lead, $buttonUrl = "", $buttonInnerText = "")
{ ?>

    <div class="row align-items-center justify-content-between">
        <div class="col">
            <h1 class="display-5 fw-bold"><?= $heading ?></h1>
            <p class="lead text-muted"><?= $lead ?></p>
        </div>
        <?php if (!empty($buttonUrl) || !empty($buttonInnerText)): ?>
            <div class="col text-end">
                <a href="<?= htmlspecialchars($buttonUrl) ?>" class="btn btn-dark btn-lg">
                    <?= htmlspecialchars($buttonInnerText) ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <hr>

<?php } ?>