<?php
/**
 * Dashboard Analytics API - Lazy Loading Endpoint
 * This endpoint loads heavy analytics data asynchronously to improve page load time
 */

// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    $cookieDomain = ".sabooksonline.co.za";
    session_set_cookie_params(0, '/', $cookieDomain);
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION["ADMIN_USERKEY"])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../controllers/AnalysisController.php";

$analysisController = new AnalysisController($conn);
$userKey = $_SESSION["ADMIN_USERKEY"];
$userID = $_SESSION["ADMIN_ID"];

$start = $_GET['start_date'] ?? null;
$end = $_GET['end_date'] ?? null;
$start_date = $start ? $start . ' 00:00:00' : null;
$end_date = $end ? $end . ' 23:59:59' : null;

$type = $_GET['type'] ?? 'all';

try {
    $data = [];

    switch ($type) {
        case 'charts':
            // Time-based analytics
            $data['bookViewsByMonthYear'] = $analysisController->getBookViewsByMonthYear($userKey);
            $data['profileViewsByMonthYear'] = $analysisController->getProfileViewsByMonthYear($userKey);
            $data['serviceViewsByMonthYear'] = $analysisController->getServiceViewsByMonthYear($userKey);
            $data['eventViewsByMonthYear'] = $analysisController->getEventViewsByMonthYear($userKey);
            
            // Geographic analytics
            $data['bookViewsByCountry'] = $analysisController->getBookViewsByCountry($userKey);
            $data['bookViewsByProvince'] = $analysisController->getBookViewsByProvince($userKey);
            $data['bookViewsByCity'] = $analysisController->getBookViewsByCity($userKey);
            break;

        case 'topbooks':
            $data['topBooks'] = $analysisController->getTopBooks($userKey);
            break;

        case 'all':
        default:
            // Load all analytics
            $data['bookViewsByMonthYear'] = $analysisController->getBookViewsByMonthYear($userKey);
            $data['profileViewsByMonthYear'] = $analysisController->getProfileViewsByMonthYear($userKey);
            $data['serviceViewsByMonthYear'] = $analysisController->getServiceViewsByMonthYear($userKey);
            $data['eventViewsByMonthYear'] = $analysisController->getEventViewsByMonthYear($userKey);
            $data['bookViewsByCountry'] = $analysisController->getBookViewsByCountry($userKey);
            $data['bookViewsByProvince'] = $analysisController->getBookViewsByProvince($userKey);
            $data['bookViewsByCity'] = $analysisController->getBookViewsByCity($userKey);
            $data['topBooks'] = $analysisController->getTopBooks($userKey);
            break;
    }

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
