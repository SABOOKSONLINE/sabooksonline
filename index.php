<?php



require 'vendor/autoload.php';

use FastRoute\RouteCollector;

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', function () {
        require "Application/views/home.php";
    });

    $r->addRoute('GET', '/home', function () {
        require "Application/views/home.php";
    });

    $r->addRoute('GET', '/about', function () {
        require "Application/views/about.php";
    });

    $r->addRoute('GET', '/contact', function () {
        require "Application/views/contact.php";
    });

    $r->addRoute('GET', '/library/book/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/bookpage.php";
    });

    $r->addRoute('GET', '/creators/creator/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/creatorpage.php";
    });
});

// Fetch method and URI from server
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        require "Application/views/404.php";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        call_user_func_array($handler, $vars);
        break;
}
