<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use FastRoute\RouteCollector;

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', function () {
        require "Application/views/home.php";
    });

    $r->addRoute('GET', '/home', function () {
        require "Application/views/home.php";
    });

    $r->addRoute('GET', '/readBook/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/readBook.php"; 
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

    $r->addRoute('GET', '/payment/notify', function () {
        require "Application/views/payment/notify.php";
    });

     $r->addRoute('POST', '/checkout', function () {
        require "Application/checkout.php";
    });

    $r->addRoute('GET', '/payment/return', function () {
        require "Application/views/payment/return.php";
    });

    $r->addRoute('GET', '/payment/cancel', function () {
        require "Application/views/payment/cancel.php";
    });

    $r->addRoute('GET', '/membership', function () {
        require "Application/views/membership.php";
    });

    $r->addRoute('GET', '/library/book/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/bookpage.php";
    });

    // Route for serving JSON API requests from React Native
    $r->addRoute('GET', '/api/books', function () {
        require "Application/api.php";
    });

     $r->addRoute('GET', '/api/categories', function () {
        $_GET['action'] = 'getAllCategories';
        require "Application/api.php";
    });

     $r->addRoute('GET', '/api/home/{category}', function ($category) {
        $_GET['action'] = 'home';
        $_GET['category'] = $category;
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/book/{id}', function ($id) {
        $_GET['action'] = 'getBook';
        $_GET['id'] = $id;
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/category/{category}', function ($category) {
        $_GET['action'] = 'getBooksByCategory';
        $_GET['category'] = $category;
        require "Application/api.php";
    });

    $r->addRoute('GET', '/api/search/{keyword}', function ($keyword) {
        $_GET['action'] = 'searchBooks';
        $_GET['keyword'] = $keyword;
        require "Application/api.php";
    });


    // audioBook
    $r->addRoute('GET', '/library/audiobook/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/books/audio/audiobook_view.php";
    });

    // user dasboard/analytics
    $r->addRoute('GET', '/dashboard', function () {
        require "dashboard/index.php";
    });

    $r->addRoute('GET', '/dashboard/basic', function () {
        require "dashboard/basic.php";
    });

    $r->addRoute('GET', '/dashboard/upload', function () {
        require "dashboard/uploadbookContent.php";
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

    $r->addRoute('GET', '/google/callback', function () {
        require  "Application/google/callback.php";
    });


    $r->addRoute('GET', '/login', function () {
        require __DIR__ . "/Application/views/auth/login.php";
    });

    $r->addRoute('GET', '/signup', function () {
        require __DIR__ . "/Application/views/auth/signup.php";
    });

    $r->addRoute('GET', '/forgot-password', function () {
        require __DIR__ . "/Application/views/auth/forgot_password.php";
    });

    $r->addRoute('GET', '/reset-password', function () {
        require __DIR__ . "/Application/views/auth/reset_password.php";
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
