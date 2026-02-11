<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/tables/bkTable.php";
include __DIR__ . "/../layouts/cards/aCard.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Books";

ob_start();

renderHeading(
    "Books",
    "Manage and update books catalog.",
);

renderAlerts();

$totalBooks = count($books);

$activeBooks = count(array_filter($books, fn($b) => $b['STATUS'] === 'Active'));

$freeBooks = count(array_filter($books, fn($b) => (float)$b['RETAILPRICE'] == 0));
$paidBooks = $totalBooks - $freeBooks;

?>

<div class="row mb-4">

    <?php
    renderAnalysisCard("Total Books", $totalBooks, "fas fa-book", "primary", 500);
    renderAnalysisCard("Active Books", $activeBooks, "fas fa-check-circle", "success", $totalBooks ?: 1);
    renderAnalysisCard("Free Books", $freeBooks, "fas fa-gift", "secondary", $totalBooks ?: 1);
    renderAnalysisCard("Paid Books", $paidBooks, "fas fa-coins", "warning", $totalBooks ?: 1);
    ?>

</div>


<?php

renderBooksTable($books);

$content = ob_get_clean();

require __DIR__ . "/../layouts/base.php";
