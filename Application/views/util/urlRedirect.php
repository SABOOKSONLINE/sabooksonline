<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$currentUri = $_SERVER['REQUEST_URI'];

// save the current uri
if (str_starts_with($currentUri, "/library")) {
    $_SESSION["redirect_uri"] = $_SERVER['REQUEST_URI'];
} else {
    $currentUri = "/";
}
