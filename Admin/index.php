<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . "/Core/Conn.php";
require __DIR__ . "/Controller/HomeController.php";

$uri = $_SERVER['REQUEST_URI'];

$controller = new HomeController($conn);

if ($uri === "/admin") {
    $controller->index();
} else {
    http_response_code(404);
    echo "404 Page Not Found";
}
