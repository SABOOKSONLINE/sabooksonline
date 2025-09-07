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
        ["title" => "Active Users",          "amount" => $data["users"]["countAll"]["total"],          "icon" => "fas fa-user-check",    "theme" => "primary"],
        ["title" => "Subscribed Users",      "amount" => $data["users"]["subscribed"]["total"],        "icon" => "fas fa-user-clock",    "theme" => "success"],
        ["title" => "Subscription Revenue",  "amount" => $data["users"]["subscribtion_gross"]["gross"], "icon" => "fas fa-dollar-sign",   "theme" => "success"],
        ["title" => "Published Content",     "amount" => $data["books"]["countAll"]["total"],          "icon" => "fas fa-book-open",     "theme" => "primary"],
        ["title" => "Purchased Books",   "amount" => $data["books"]["book_purchase_count"]["total"], "icon" => "fas fa-shopping-cart", "theme" => "danger"],
        ["title" => "Purchased Books Income",  "amount" => $data["books"]["book_purchase_gross"]["gross"], "icon" => "fas fa-credit-card", "theme" => "success"],
        ["title" => "Book Views",            "amount" => $data["books"]["book_views"]["total"],        "icon" => "fas fa-eye",           "theme" => "primary"],
        ["title" => "Media Views",           "amount" => $data["books"]["media_views"]["total"],       "icon" => "fas fa-photo-video",   "theme" => "danger"]
    ];

    foreach ($cardMetrics as $card) {
        renderAnalysisCard(
            $card["title"],
            $card["amount"],
            $card["icon"],
            $card["theme"]
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
