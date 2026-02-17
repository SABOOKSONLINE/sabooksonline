<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/forms/pbForm.php";
require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

ob_start();

renderHeading(
    "Book Upload Management",
    "Manage hardcopy book publishers and upload permissions.",
);
renderAlerts();

$publishers = $publishers ?? [];
$users = $users ?? [];

renderAddPublisherForm($users);
renderPublishersTable($publishers);

$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
