<?php
include __DIR__ . "/../layouts/cards/aCard.php";
include __DIR__ . "/../layouts/cards/pCard.php";

$title = "Admin Overviews";
ob_start(); ?>

<div class="row">
    <?php
    $cardMetrics = [
        ["title" => "Active Users", "amount" => $data["users"]["all"]["total"], "icon" => "fas fa-user-check"],
        ["title" => "Subscribed Users", "amount" => $data["users"]["subscribed"]["total"], "icon" => "fas fa-user-clock"],
        ["title" => "Subscription Revenue", "amount" => $data["users"]["subscribtion_gross"]["gross"], "icon" => "fas fa-dollar-sign"],
        ["title" => "Published Content", "amount" => $data["books"]["total"], "icon" => "fas fa-book"]
    ];

    foreach ($cardMetrics as $card) {
        renderAnalysisCard($card["title"], $card["amount"], $card["icon"]);
    }

    $pieMetrics = [
        ["title" => "Active Users", "value" => $data["users"]["all"]["total"], "total" => 1000],
    ];

    foreach ($pieMetrics as $pie) {
        renderAnalysisPie($pie["title"], $pie["value"], $pie["total"]);
    }

    ?>
</div>

<?php
$content = ob_get_clean();

require __DIR__ . "/../layouts/base.php";
