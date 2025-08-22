<?php
function renderHeading($heading, $lead, $buttonUrl = "", $buttonInnerText = "", $printToPdf = false)
{ ?>

    <div class="row g-3 align-items-center mt-4 mt-lg-0">
        <div class="col-md-8 col-lg-9">
            <h1 class="display-5 fw-bold mb-2 mb-md-0"><?= $heading ?></h1>
            <p class="lead text-muted"><?= $lead ?></p>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                <?php if (!empty($buttonUrl) && !empty($buttonInnerText)): ?>
                    <a href="<?= $buttonUrl ?>" class="btn btn-outline-dark shadow-sm flex-grow-1 flex-md-grow-0">
                        <?= $buttonInnerText ?>
                    </a>
                <?php endif; ?>

                <?php if ($printToPdf): ?>
                    <button id="printPDF" class="btn btn-outline-dark shadow-sm flex-grow-1 flex-md-grow-0">
                        Print Screen
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <hr class="my-4">

<?php } ?>