<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/forms/caForm.php";
require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

ob_start();

renderHeading(
    "Collection Addresses",
    "Manage book collection addresses for The Courier Guy shipments.",
);

renderAlerts();

$users        = $users        ?? [];
$userId       = $user_id      ?? 0;
$selectedUser = $selected_user ?? [];
$addresses    = $addresses    ?? [];
$editAddress  = $edit_address  ?? [];

renderUserSelector($users, $userId);

if ($userId) {
    renderAddressForm($userId, $editAddress);
}

renderAddressesTable($addresses, $userId);

$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
