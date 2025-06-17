<?php 
function renderHeading($heading, $lead, $buttonUrl = "", $buttonInnerText = "", $printToPdf = false)
{ ?>
    <div class="row align-items-center justify-content-between border-bottom pb-3 mb-4">
        <div class="col">
            <h1 class="display-5 fw-bold"><?= $heading ?></h1>
            <p class="lead text-muted"><?= $lead ?></p>
        </div>

            <?php if (!empty($buttonUrl) && !empty($buttonInnerText)): ?>
                <a href="<?= $buttonUrl ?>" class="btn btn-outline-dark shadow-sm">
                    <?= $buttonInnerText ?>
                </a>
            <?php endif; ?>

            <?php if ($printToPdf): ?>
                <button id="printPDF" class="btn btn-outline-dark shadow-sm">
                    <i class="fas fa-file-pdf me-2"></i> Print Screen                
                </button>
            <?php endif; ?>
    </div>
<?php } ?>
