<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/Core/Conn.php";
require_once __DIR__ . "/Controller/HomeController.php";
require_once __DIR__ . "/Controller/PagesController.php";

$uri = $_SERVER['REQUEST_URI'];

$homeController = new HomeController($conn);
$pagesController = new PagesController($conn);

if ($uri === "/admin") {
    $homeController->index();
} else if ($uri === "/admin/pages/home") {
    $pagesController->pages();
} else {
    http_response_code(404);
    echo "404 Page Not Found";
}
