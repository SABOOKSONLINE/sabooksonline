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

    $r->addRoute('GET', '/library', function () {
        require "Application/views/library.php";
    });

    $r->addRoute('GET', '/membership', function () {
        require "Application/views/membership.php";
    });

    $r->addRoute('GET', '/library/book/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/bookpage.php";
    });


    $r->addRoute('GET', '/login', function () {
        require "/login.php";
    });

    $r->addRoute('GET', '/google/callback', function () {
        require  "Application/google/callback.php";
    });

    // audioBook
    $r->addRoute('GET', '/library/audio-book', function () {
        require "Application/views/audio/audioBookPage.php";
    });

    // user dasboard/analytics
    $r->addRoute('GET', '/dashboard', function () {
        require "dashboard/index.php";
    });

    $r->addRoute('GET', '/dashboard/listings', function () {
        require "dashboard/views/manage_books.php";
    });
    $r->addRoute('GET', '/dashboard/events', function () {
        require "dashboard/views/manage_events.php";
    });
    $r->addRoute('GET', '/dashboard/services', function () {
        require "dashboard/views/manage_services.php";
    });
    $r->addRoute('GET', '/dashboard/reviews', function () {
        require "dashboard/views/manage_reviews.php";
    });
    $r->addRoute('GET', '/bookreader', function () {
        require "dashboard/bookReader.php";
    });

    $r->addRoute('GET', '/creators/creator/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/creatorpage.php";
    });

    // providers
    $r->addRoute('GET', '/providers', function () {
        require "Application/views/providers.php";
    });

    // gallery
    $r->addRoute('GET', '/gallery', function () {
        require "Application/views/gallery.php";
    });

    // services
    $r->addRoute('GET', '/services', function () {
        require "Application/views/ourServices.php";
    });

    // event routes
    $r->addRoute('GET', '/events', function () {
        require "Application/views/events.php";
    });

    $r->addRoute('GET', '/events/event/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/eventpage.php";
    });

    // documentation pages
    $r->addRoute('GET', '/content-removal', function () {
        require __DIR__ . "/Application/views/documentations/content-removal.php";
    });

    $r->addRoute('GET', '/popia-compliance', function () {
        require __DIR__ . "/Application/views/documentations/popia-compliance.php";
    });

    $r->addRoute('GET', '/privacy-policy', function () {
        require __DIR__ . "/Application/views/documentations/privacy-policy.php";
    });

    $r->addRoute('GET', '/termination-policy', function () {
        require __DIR__ . "/Application/views/documentations/termination-policy.php";
    });

    $r->addRoute('GET', '/terms-and-conditions', function () {
        require __DIR__ . "/Application/views/documentations/terms-and-conditions.php";
    });

    $r->addRoute('GET', '/refund-policy', function () {
        require __DIR__ . "/Application/views/documentations/refund-policy.php";
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
