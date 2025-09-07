<?php
include __DIR__ . "/../layouts/cards/aCard.php";
include __DIR__ . "/../layouts/cards/pCard.php";
include __DIR__ . "/../layouts/cards/tCard.php";

$title = "Admin Overviews";
ob_start();
?>

<div class="row">
    <?php
    $cardMetrics = [
        ["title" => "Active Users", "amount" => $data["users"]["countAll"]["total"], "icon" => "fas fa-user-check", "theme" => "primary"],
        ["title" => "Subscribed Users", "amount" => $data["users"]["subscribed"]["total"], "icon" => "fas fa-user-clock", "theme" => "success"],
        ["title" => "Subscription Revenue", "amount" => $data["users"]["subscribtion_gross"]["gross"], "icon" => "fas fa-dollar-sign", "theme" => "warning"],
        ["title" => "Published Content", "amount" => $data["books"]["total"], "icon" => "fas fa-book", "theme" => "info"]
    ];

    foreach ($cardMetrics as $card) {
        renderAnalysisCard(
            $card["title"],
            $card["amount"],
            $card["icon"],
            $card["theme"] ?? "primary"
        );
    }

    $pieMetrics = [
        ["title" => "Active Users", "value" => $data["users"]["countAll"]["total"], "total" => 1000],
    ];

    foreach ($pieMetrics as $pie) {
        renderAnalysisPie($pie["title"], $pie["value"], $pie["total"]);
    }

    $tableMetrics = [
        "headers" => ["ID", "Name", "Email", "Subscription"],
        "rows" => $data["users"]["details"]
    ];

    renderTable($tableMetrics["headers"], $tableMetrics["rows"]);

    ?>
</div>

<?php
$content = ob_get_clean();

require __DIR__ . "/../layouts/base.php";
