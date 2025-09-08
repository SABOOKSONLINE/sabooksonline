<?php
// ============================
// Session Alert Helper
// ============================

function setAlert(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['alerts'][] = [
        'type' => $type,
        'message' => $message
    ];
}

function renderAlerts(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!empty($_SESSION['alerts'])) {
        foreach ($_SESSION['alerts'] as $alert) {
            $type = htmlspecialchars($alert['type']);
            $message = htmlspecialchars($alert['message']);
            echo <<<HTML
<div class="alert alert-{$type} alert-dismissible fade show rounded-3 shadow-sm" role="alert">
    {$message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
HTML;
        }
        unset($_SESSION['alerts']);
    }
}
