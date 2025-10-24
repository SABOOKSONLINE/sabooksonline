<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$currentUri = $_SERVER['REQUEST_URI'];

// save the current uri
$prefixes = ["/library", "/sell", "/membership"];
$matched = false;

foreach ($prefixes as $prefix) {
    if (str_starts_with($currentUri, $prefix)) {
        $matched = true;
        break;
    }
}

if ($matched) {
    $_SESSION["redirect_uri"] = $_SERVER['REQUEST_URI'];
} else {
    $currentUri = "/";
}
