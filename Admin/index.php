<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/Core/Conn.php";
require_once __DIR__ . "/Controller/HomeController.php";
require_once __DIR__ . "/Controller/PagesController.php";
require_once __DIR__ . "/Controller/UsersController.php";
require_once __DIR__ . "/Controller/AnalyticsController.php";
require_once __DIR__ . "/Controller/OrdersController.php";
require_once __DIR__ . "/Controller/PurchasesController.php";
require_once __DIR__ . "/Controller/MobileController.php";
require_once __DIR__ . "/Controller/BooksController.php";
require_once __DIR__ . "/Controller/PublishersBooksController.php";
require_once __DIR__ . "/Controller/UploadManagementController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Debug: Log the URI for troubleshooting
error_log("Admin routing - URI: " . $uri . ", Full REQUEST_URI: " . $_SERVER['REQUEST_URI']);

$homeController = new HomeController($conn);
$pagesController = new PagesController($conn);
$usersController = new UsersController($conn);
$analyticsController = new AnalyticsController($conn);
$ordersController = new OrdersController($conn);
$purchasesController = new PurchasesController($conn);
$mobileController = new MobileController($conn);
$booksController = new BooksController($conn);
$publishersBooksController = new PublishersBooksController($conn);
$uploadManagementController = new UploadManagementController($conn);

if ($uri === "/admin") {
    $homeController->index();
} else if ($uri === "/admin/pages/home") {
    $pagesController->pages();
} else if ($uri === "/admin/users") {
    $usersController->users();
} else if ($uri === "/admin/books") {
    $booksController->books();
} else if ($uri === "/admin/books/upload-management") {
    $uploadManagementController->index();
} else if ($uri === "/admin/analytics") {
    $analyticsController->analytics();
} else if ($uri === "/admin/orders") {
    $ordersController->orders();
} else if ($uri === "/admin/purchases") {
    $purchasesController->purchases();
} else if ($uri === "/admin/publishers/books" || $uri === "/admin/publishers/books/") {
    $publishersBooksController->books();
} else if ($uri === "/admin/mobile/banners") {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mobileController->handleBannerForm();
    } else {
        $mobileController->banners();
    }
} else if (preg_match('#^/admin/mobile/notifications/resend/(\d+)$#', $uri, $matches)) {
    // Check regex patterns BEFORE exact matches
    $mobileController->resendNotification((int)$matches[1]);
} else if (preg_match('#^/admin/mobile/notifications/delete/(\d+)$#', $uri, $matches)) {
    $mobileController->deleteNotification((int)$matches[1]);
} else if ($uri === "/admin/mobile/notifications") {
    $mobileController->notifications();
} else if ($uri === "/admin/mobile/notifications/send") {
    $mobileController->sendNotification();
} else if ($uri === "/admin/mobile/notifications/preview") {
    $mobileController->previewNotificationRecipients();
} else {
    // Debug: Log unmatched URI
    error_log("Admin 404 - URI: " . $uri . ", Full REQUEST_URI: " . $_SERVER['REQUEST_URI']);
    http_response_code(404);
    echo "404 Page Not Found";
    echo "<br>URI: " . htmlspecialchars($uri);
    echo "<br>Full REQUEST_URI: " . htmlspecialchars($_SERVER['REQUEST_URI']);
}
