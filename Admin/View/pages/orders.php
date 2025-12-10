<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/tables/uTable.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Orders";
ob_start();

renderHeading(
    "Orders",
    "Manage and update Orders.",
);

renderAlerts();
?>

<?php

?>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
