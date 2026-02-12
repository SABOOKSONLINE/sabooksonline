<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/cards/aCard.php";
include __DIR__ . "/../layouts/tables/bkTable.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Books";
ob_start();

renderHeading(
    "Books",
    "Manage and update all books.",
);

renderAlerts();

$books = $data["books"];

// Calculate statistics
$totalBooks = count($books);
$activeBooks = count(array_filter($books, fn($b) => !empty($b['STATUS']) && strtolower($b['STATUS']) === 'active'));
$freeBooks = count(array_filter($books, fn($b) => empty($b['RETAILPRICE']) || (float)$b['RETAILPRICE'] == 0));
$paidBooks = count(array_filter($books, fn($b) => !empty($b['RETAILPRICE']) && (float)$b['RETAILPRICE'] > 0));

renderSectionHeader(
    "Overview Statistics",
    "Key metrics and insights about your books"
);
?>

<div class="row">
    <?php
    $cards = [
        [
            "title" => "Total Books",
            "value" => $totalBooks,
            "icon"  => "fas fa-book",
            "color" => "primary"
        ],
        [
            "title" => "Active Books",
            "value" => $activeBooks,
            "icon"  => "fas fa-check-circle",
            "color" => "success"
        ],
        [
            "title" => "Free Books",
            "value" => $freeBooks,
            "icon"  => "fas fa-gift",
            "color" => "info"
        ],
        [
            "title" => "Paid Books",
            "value" => $paidBooks,
            "icon"  => "fas fa-dollar-sign",
            "color" => "warning"
        ],
    ];

    foreach ($cards as $card) {
        renderAnalysisCard($card["title"], $card["value"], $card["icon"], $card["color"]);
    }
    ?>
</div>

<?php
renderSectionHeader(
    "Books Catalog",
    "Search, filter, and manage your books"
);

renderBooksTable($books);

$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
