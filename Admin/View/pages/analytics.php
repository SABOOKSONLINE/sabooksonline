<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/cards/aCard.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Analytics";
ob_start();

renderHeading(
    "Analytics",
    "Monitor book sales, downloads, and publisher performance.",
);

renderAlerts();
// echo "<pre>";
// print_r($data);
// echo "</pre>";

renderSectionHeader(
    "Income Analytics",
    "Track revenue streams and monitor overall earnings.",
);
?>
<div class="row">
    <?php
    $cards = [
        [
            "title" => "Subscribed Publishers",
            "value" => count($data["payment_plans"]["all"]),
            "icon"  => "fas fa-users",
            "color" => "success"
        ],
        [
            "title" => "Subscription Revenue",
            "value" => $data["payment_plans"]["revenue"],
            "icon"  => "fas fa-dollar-sign",
            "color" => "success"
        ],
        [
            "title" => "Purchased Books",
            "value" => count($data["book_purchases"]["all"]),
            "icon"  => "fas fa-book",
            "color" => "success"
        ],
        [
            "title" => "Purchase Revenue",
            "value" => $data["book_purchases"]["revenue"],
            "icon"  => "fas fa-coins",
            "color" => "success"
        ],
    ];

    foreach ($cards as $card) {
        renderAnalysisCard($card["title"], $card["value"], $card["icon"], $card["color"]);
    }
    ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
