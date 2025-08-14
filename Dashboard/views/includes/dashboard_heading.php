<?php
function renderHeading($heading, $lead, $buttonUrl = "", $buttonInnerText = "", $printToPdf = false)
{ ?>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="display-5 fw-bold"><?= $heading ?></h1>
            <p class="lead text-muted"><?= $lead ?></p>
        </div>

        <div>
            <?php if (!empty($buttonUrl) && !empty($buttonInnerText)): ?>
                <a href="<?= $buttonUrl ?>" class="btn btn-outline-dark shadow-sm">
                    <?= $buttonInnerText ?>
                </a>
            <?php endif; ?>

            <?php if ($printToPdf): ?>
                <button id="printPDF" class="btn btn-outline-dark shadow-sm">
                    Print Screen
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>