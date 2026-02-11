<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/tables/uTable.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Users";
ob_start();

renderHeading(
    "Users",
    "Manage and update user accounts.",
);

renderAlerts();

$users = $data["details"];

renderUserTable($users);

$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
