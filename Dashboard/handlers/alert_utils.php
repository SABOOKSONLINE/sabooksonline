<?php
function setAlert(string $type, string $message): void
{
    $_SESSION['alerts'][] = [
        'type' => $type,
        'message' => $message
    ];
}

function getAlerts(): array
{
    $alerts = $_SESSION['alerts'] ?? [];
    unset($_SESSION['alerts']);
    return $alerts;
}
