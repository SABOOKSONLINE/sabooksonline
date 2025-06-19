<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use FastRoute\RouteCollector;

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {

    // =================== Public Site Routes ===================
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
    $r->addRoute('GET', '/library/audiobook/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/books/audio/audiobook_view.php";
    });
    $r->addRoute('GET', '/library/readBook/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/readBook.php";
    });

    // =================== Payment Routes ===================
    $r->addRoute('POST', '/payment/notify', function () {
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

    // =================== API Routes ===================
    $r->addRoute('GET', '/api/onix', function () {
        require "Application/onix.php";
    });
    $r->addRoute('GET', '/api/books', function () {
        require "Application/api.php";
    });
    $r->addRoute('GET', '/api/Ebooks', function () {
        $_GET['action'] = 'Ebooks';
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
    $r->addRoute('POST', '/api/login', function ($keyword) {
        $_GET['action'] = 'google login';
        $_GET['keyword'] = $keyword;
        require "Application/api.php";
    });
    $r->addRoute('GET', '/api/user/books', function ($keyword) {
        $_GET['action'] = 'userBooks';
        $_GET['keyword'] = $keyword;
        require "Application/api.php";
    });
    $r->addRoute('GET', '/api/user/purchasedBooks', function ($keyword) {
        $_GET['action'] = 'userPurchasedBooks';
        $_GET['keyword'] = $keyword;
        require "Application/api.php";
    });

    // =================== Dashboard Routes ===================
    // --- Main Dashboard ---
    $r->addRoute('GET', '/dashboards', function () {
        require "Dashboard/index.php";
    });

    // --- cloudinary save content ---
    $r->addRoute('POST', '/includes/save-pdf-url', function () {
        require __DIR__ . "/Dashboard/views/includes/save-pdf-url.php";
    });
    // --- Book Listings ---
    $r->addRoute('GET', '/dashboards/add/listings', function () {
        require "Dashboard/views/add/add_book.php";
    });
    $r->addRoute('GET', '/dashboards/listings', function () {
        require "Dashboard/views/book_listings.php";
    });
    $r->addRoute('POST', '/dashboards/listings/insert', function () {
        $_GET['action'] = 'insert';
        require "Dashboard/handlers/book_handler.php";
    });
    $r->addRoute('POST', '/dashboards/listings/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['id'] = $id;
        require "Dashboard/handlers/book_handler.php";
    });
    $r->addRoute('GET', '/dashboards/listings/{id}', function ($id) {
        $_GET['id'] = $id;
        require "Dashboard/views/add/add_book.php";
    });
    $r->addRoute('GET', '/dashboards/listings/delete/{id}', function ($id) {
        $_GET['action'] = 'delete';
        $_GET['id'] = $id;
        require "Dashboard/handlers/book_handler.php";
    });

    // --- Events ---
    $r->addRoute('GET', '/dashboards/add/event', function () {
        require "Dashboard/views/add/add_event.php";
    });
    $r->addRoute('GET', '/dashboards/events', function () {
        require "Dashboard/views/manage_events.php";
    });
    $r->addRoute('POST', '/dashboards/events/insert', function () {
        $_GET['action'] = 'insert';
        require "Dashboard/handlers/event_handler.php";
    });
    $r->addRoute('POST', '/dashboards/events/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['id'] = $id;
        require "Dashboard/handlers/event_handler.php";
    });
    $r->addRoute('GET', '/dashboards/events/delete/{id}', function ($id) {
        $_GET['action'] = 'delete';
        $_GET['id'] = $id;
        require "Dashboard/handlers/event_handler.php";
    });
    $r->addRoute('GET', '/dashboards/events/{id}', function ($id) {
        $_GET['id'] = $id;
        require "Dashboard/views/add/add_event.php";
    });

    // --- Services ---
    $r->addRoute('GET', '/dashboards/add/service', function () {
        require "Dashboard/views/add/add_services.php";
    });
    $r->addRoute('GET', '/dashboards/services', function () {
        require "Dashboard/views/manage_services.php";
    });
    $r->addRoute('POST', '/dashboards/services/insert', function () {
        $_GET['action'] = 'insert';
        require "Dashboard/handlers/service_handler.php";
    });
    $r->addRoute('POST', '/dashboards/services/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['id'] = $id;
        require "Dashboard/handlers/service_handler.php";
    });
    $r->addRoute('GET', '/dashboards/services/delete/{id}', function ($id) {
        $_GET['action'] = 'delete';
        $_GET['id'] = $id;
        require "Dashboard/handlers/service_handler.php";
    });
    $r->addRoute('GET', '/dashboards/services/{id}', function ($id) {
        $_GET['id'] = $id;
        require "Dashboard/views/add/add_services.php";
    });

    // --- Reviews, Profile, Billing ---
    $r->addRoute('GET', '/dashboards/reviews', function () {
        require "Dashboard/views/manage_reviews.php";
    });
    $r->addRoute('GET', '/dashboards/profile', function () {
        require "Dashboard/views/manage_profile.php";
    });
    $r->addRoute('POST', '/dashboards/profile/update/{id}', function ($id) {
        $_GET['action'] = 'update';
        $_GET['id'] = $id;
        require "Dashboard/handlers/user_handler.php";
    });
    $r->addRoute('GET', '/dashboards/billing', function () {
        require "Dashboard/views/account_billing.php";
    });

    // --- Bookshelf & Audiobooks ---
    $r->addRoute('GET', '/dashboards/bookshelf', function () {
        require "Dashboard/views/bookshelf.php";
    });
    $r->addRoute('GET', '/dashboards/audiobooks', function () {
        require "Dashboard/views/audiobooks.php";
    });

    // --- Audiobook Handling Routes ---
    $r->addRoute('POST', '/dashboards/listings/insertAudio', function () {
        require "Dashboard/handlers/audiobook_handler.php";
    });
    $r->addRoute('POST', '/dashboards/listings/updateAudio/{id}', function ($id) {
        $_GET['id'] = $id;
        $_GET['action'] = 'updateAudio';
        require "Dashboard/handlers/audiobook_handler.php";
    });
    $r->addRoute('GET', '/dashboards/listings/deleteAudio/{id}', function ($id) {
        $_GET['id'] = $id;
        $_GET['action'] = 'deleteAudio';
        require "Dashboard/handlers/audiobook_handler.php";
    });
    $r->addRoute('GET', '/dashboards/add/audiobook/{id}', function ($id) {
        $_GET['id'] = $id;
        require "Dashboard/views/add/add_audiobook.php";
    });

    // --- Audiobook chapter Handling Routes ---
    $r->addRoute('POST', '/dashboards/listings/insertAudioChapter', function () {
        require "Dashboard/handlers/audiobook_chapter_handler.php";
    });
    $r->addRoute('POST', '/dashboards/listings/updateAudioChapter/{id}', function ($id) {
        $_GET['id'] = $id;
        $_GET['action'] = 'updateAudioChapter';
        require "Dashboard/handlers/audiobook_chapter_handler.php";
    });
    $r->addRoute('GET', '/dashboards/listings/deleteAudioChapter/{chapterId}', function ($chapterId) {
        $_GET['id'] = $chapterId;
        $_GET['action'] = 'deleteAudioChapter';
        require "Dashboard/handlers/audiobook_chapter_handler.php";
    });

    // =================== Creator, Provider, Gallery, Services, Events (Public) ===================
    $r->addRoute('GET', '/creators/creator/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/creatorpage.php";
    });
    $r->addRoute('GET', '/providers', function () {
        require "Application/views/providers.php";
    });
    $r->addRoute('GET', '/gallery', function () {
        require "Application/views/gallery.php";
    });
    $r->addRoute('GET', '/services', function () {
        require "Application/views/ourServices.php";
    });
    $r->addRoute('GET', '/events', function () {
        require "Application/views/events.php";
    });
    $r->addRoute('GET', '/events/event/{id}', function ($id) {
        $_GET['q'] = $id;
        require "Application/views/eventpage.php";
    });

    // =================== Documentation Routes ===================
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

    // =================== Auth Routes ===================
    $r->addRoute('GET', '/login', function () {
        require __DIR__ . "/Application/views/auth/login.php";
    });
    $r->addRoute('GET', '/logout', function () {
        require __DIR__ . "/Application/views/auth/logout.php";
    });
    $r->addRoute('POST', '/formLogin', function () {
        require __DIR__ . "/Application/views/includes/loginWithForm.php";
    });
    $r->addRoute('GET', '/signup', function () {
        require __DIR__ . "/Application/views/auth/signup.php";
    });
    $r->addRoute('GET', '/forgot-password', function () {
        require __DIR__ . "/Application/views/auth/forgot_password.php";
    });
    $r->addRoute('GET', '/reset-password/{token}', function ($token) {
        $_GET['token'] = $token;
        require __DIR__ . "/Application/views/auth/reset_password.php";
    });
    $r->addRoute('POST', '/auth/signup-handler', function () {
        require __DIR__ . "/Application/views/auth/signup_handler.php";
    });
    $r->addRoute('POST', '/auth/login-handler', function () {
        require __DIR__ . "/Application/views/auth/login_handler.php";
    });
    $r->addRoute('POST', '/auth/reset-password-handler', function () {
        require __DIR__ . "/Application/views/auth/reset_password_handler.php";
    });
    $r->addRoute('POST', '/auth/forgot-password-handler', function () {
        require __DIR__ . "/Application/views/auth/forgot_password_handler.php";
    });
    $r->addRoute('GET', '/verify/{token}', function ($token) {
        $_GET['token'] = $token;
        require __DIR__ . "/Application/views/auth/verify.php";
    });
    $r->addRoute('GET', '/registration_success', function () {
        require __DIR__ . "/Application/views/auth/registration_success.php";
    });
    // =================== Google OAuth Callback ===================
    $r->addRoute('GET', '/google/callback', function () {
        require  "Application/google/callback.php";
    });

    // =================== File Download & Views ===================
    $r->addRoute('GET', '/view/pdfs/{filename}', function ($filename) {
        $safeFilename = basename($filename); // prevent directory traversal
        $path = __DIR__ . '/cms-data/book-pdfs/' . $safeFilename;

        if (!file_exists($path)) {
            http_response_code(404);
            echo "PDF not found.";
            return;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $safeFilename . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    });

    // =================== Tanci API ===================
    $r->addRoute('GET', '/api/read_remote_data/{apiKey}/{userKey}', function ($apiKey, $userKey) {
        $_GET['api_key'] = $apiKey;
        $_GET['userkey'] = $userKey;
        require __DIR__ . "/API/read_remote_data.php";
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

// Strip trailing slash (except for root)
if ($uri !== '/' && substr($uri, -1) === '/') {
    $uri = rtrim($uri, '/');
}

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        require "Application/views/404.php";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        require "Application/views/405.php";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        call_user_func_array($handler, $vars);
        break;
}
