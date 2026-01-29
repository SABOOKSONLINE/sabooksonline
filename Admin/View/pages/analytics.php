<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/cards/aCard.php";
include __DIR__ . "/../layouts/cards/bCard.php";
include __DIR__ . "/../layouts/cards/lCard.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Analytics";
ob_start();

renderHeading(
    "Analytics",
    "Monitor book sales, downloads, and publisher performance."
);

renderAlerts();

renderSectionHeader(
    "Income Analytics",
    "Track revenue streams and monitor overall earnings."
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

    function getMonth($date)
    {
        return date('Y-m', strtotime($date));
    }

    $monthlySubscriptions = [];
    foreach ($data['payment_plans']['all'] as $payment) {
        $month = getMonth($payment['payment_date']);
        $monthlySubscriptions[$month] = ($monthlySubscriptions[$month] ?? 0) + (float)$payment['amount_paid'];
    }

    $monthlyPublisherCount = [];
    foreach ($data['payment_plans']['all'] as $payment) {
        $month = getMonth($payment['payment_date']);
        $monthlyPublisherCount[$month] = ($monthlyPublisherCount[$month] ?? 0) + 1;
    }

    $monthlyBookSales = [];
    foreach ($data['book_purchases']['all'] as $purchase) {
        $month = getMonth($purchase['payment_date']);
        $monthlyBookSales[$month] = ($monthlyBookSales[$month] ?? 0) + (float)$purchase['amount'];
    }

    $monthlyBookSalesCount = [];
    foreach ($data['book_purchases']['all'] as $purchase) {
        $month = getMonth($purchase['payment_date']);
        $monthlyBookSalesCount[$month] = ($monthlyBookSalesCount[$month] ?? 0) + 1;
    }

    $allMonths = array_unique(array_merge(array_keys($monthlySubscriptions), array_keys($monthlyBookSales)));
    sort($allMonths);

    $subscriptionValues = [];
    $bookSalesValues = [];
    foreach ($allMonths as $month) {
        $subscriptionValues[] = $monthlySubscriptions[$month] ?? 0;
        $bookSalesValues[] = $monthlyBookSales[$month] ?? 0;
    }

    renderAnalysisBar(
        "Monthly New Publishers",
        $allMonths,
        array_map(fn($m) => $monthlyPublisherCount[$m] ?? 0, $allMonths),
        "success"
    );

    renderAnalysisLine(
        "Monthly Subscription Revenue",
        $allMonths,
        $subscriptionValues,
        "success"
    );

    renderAnalysisBar(
        "Monthly Books Sold",
        $allMonths,
        array_map(fn($m) => $monthlyBookSalesCount[$m] ?? 0, $allMonths),
        "success"
    );

    renderAnalysisLine(
        "Monthly Book Sales",
        $allMonths,
        $bookSalesValues,
        "success"
    );
    ?>
</div>

<?php
renderSectionHeader(
    "Recent Book Purchases",
    "Latest book purchases with detailed information"
);
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($data["book_purchases"]["all"])): ?>
                    <p class="text-muted">No book purchases yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Customer</th>
                                    <th>Format</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($data["book_purchases"]["all"], 0, 10) as $purchase): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($purchase['book_cover'])): ?>
                                                    <img src="/cms-data/book-covers/<?= htmlspecialchars($purchase['book_cover']) ?>" 
                                                         alt="Cover" class="me-2 rounded" style="width: 40px; height: 60px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-bold">
                                                        <?= htmlspecialchars($purchase['book_title'] ?? 'Unknown Book') ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($purchase['book_publisher'] ?? 'Unknown Publisher') ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?= htmlspecialchars($purchase['user_email']) ?>
                                            </div>
                                            <small class="text-muted">ID: <?= htmlspecialchars($purchase['user_key']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $purchase['format'] === 'Ebook' ? 'primary' : 'secondary' ?>">
                                                <?= htmlspecialchars(ucfirst($purchase['format'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong>R<?= number_format($purchase['amount'], 2) ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $purchase['payment_status'] === 'COMPLETE' ? 'success' : 'warning' ?>">
                                                <?= htmlspecialchars($purchase['payment_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= date('M j, Y', strtotime($purchase['payment_date'])) ?>
                                            <br>
                                            <small class="text-muted"><?= date('g:i A', strtotime($purchase['payment_date'])) ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($data["book_purchases"]["all"]) > 10): ?>
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Showing 10 of <?= count($data["book_purchases"]["all"]) ?> total purchases
                            </small>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
renderSectionHeader(
    "Traffic Analytics",
    "Still under development (Coming Soon)!"
);
?>
<div class="row">
    <?php
    // echo "<pre>";
    // print_r($data["page_visits"]);
    // echo "</pre>";

    $pageVisits = [];
    foreach ($data['page_visits']['all'] as $pageVisit) {
        $ip = $pageVisit['user_ip'];
        $pageVisits[$ip] = ($pageVisits[$ip] ?? 0) + 1;
    }

    $bookViews = 0;
    $mediaViews = 0;
    foreach ($data['page_visits']['all'] as $pageVisit) {

        if (str_starts_with("/library/book/", $pageVisit['page_url'])) {
            $bookViews = $bookViews + 1;
        } else if (str_starts_with("/media/", $pageVisit['page_url'])) {
            $mediaViews = $mediaViews + 1;
        }
    }

    // echo "<pre>";
    // print_r($pageVisits);
    // echo "</pre>";

    $cards = [
        [
            "title" => "Total Visits",
            "value" => count($data["page_visits"]["all"]),
            "icon"  => "fas fa-users",
            "color" => "primary"
        ],
        [
            "title" => "Unique IP Visits",
            "value" => count($pageVisits),
            "icon"  => "fas fa-users",
            "color" => "primary"
        ],
        [
            "title" => "Book Views",
            "value" => $bookViews,
            "icon"  => "fas fa-users",
            "color" => "primary"
        ],
        [
            "title" => "Media Views",
            "value" => $mediaViews,
            "icon"  => "fas fa-users",
            "color" => "primary"
        ]
    ];

    foreach ($cards as $card) {
        renderAnalysisCard($card["title"], $card["value"], $card["icon"], $card["color"]);
    }

    // $allMonthly = array_unique(array_merge(array_keys($monthlySubscriptions), array_keys($monthlyBookSales)));

    // $monthlyViews = [];
    // foreach ($data['page_visits']['all'] as $pageVisit) {
    //     $month = getMonth($pageVisit['visit_time']);
    //     $monthlyViews[$month] = ($monthlyViews[$month] ?? 0) + 1;
    // }

    // renderAnalysisBar(
    //     "Monthly Visits",
    //     $monthlyViews,
    //     array_map(fn($m) => $monthlyViews[$m] ?? 0, $allMonths),
    //     "priamry"
    // );
    ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>