<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/tables/oTable.php"; // Updated table file

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Orders";
ob_start();

renderHeading(
    "Orders",
    "View, manage, and update customer orders."
);

renderAlerts();
?>

<?php
$orders = $data["orders"] ?? [];
$itemsByOrder = $data["items"] ?? [];

// echo "<pre>";
// print_r($itemsByOrder);
// echo "</pre>";

renderOrdersTable($orders, $itemsByOrder);
?>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>
