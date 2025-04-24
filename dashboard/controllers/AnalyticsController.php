<?php
header('Content-Type: application/json');

// Include the model file and DB connection
require_once '../models/AnalyticsModel.php';
require_once '../config/db_connection.php'; // Your DB connection file

// Initialize model with DB connection
$model = new AnalyticsModel($con);

// Get request parameters safely
$action      = $_GET['action'] ?? '';
$user_id     = $_GET['user_id'] ?? null;
$provider_id = $_GET['provider_id'] ?? null;
$start_date  = $_GET['start_date'] ?? '2024-01-01';
$end_date    = $_GET['end_date'] ?? date('Y-m-d');

// Response placeholder
$response = [];

switch ($action) { 
    case 'net_income':
        $response['net_income'] = $model->getNetIncome();
        break;

    case 'total_transactions':
        $response['total_transactions'] = $model->getTotalTransactions();
        break;

    case 'total_customers':
        $response['total_customers'] = $model->getTotalCustomers();
        break;

    case 'pending_orders':
        $response['pending_orders'] = $model->getPendingOrders();
        break;

    case 'service_views':
        if ($provider_id) {
            $response['service_views'] = $model->getServiceViews($provider_id, $start_date, $end_date);
        } else {
            http_response_code(400);
            $response['error'] = 'Missing provider_id';
        }
        break;

    case 'unique_service_users':
        if ($provider_id) {
            $response['unique_users'] = $model->getUniqueServiceUsers($provider_id, $start_date, $end_date);
        } else {
            http_response_code(400);
            $response['error'] = 'Missing provider_id';
        }
        break;

    case 'book_views':
        if ($user_id) {
            $response['book_views'] = $model->getBookViews($user_id, $start_date, $end_date);
        } else {
            http_response_code(400);
            $response['error'] = 'Missing user_id';
        }
        break;

    case 'unique_book_users':
        if ($user_id) {
            $response['unique_users'] = $model->getUniqueBookUsers($user_id, $start_date, $end_date);
        } else {
            http_response_code(400);
            $response['error'] = 'Missing user_id';
        }
        break;

    case 'event_views':
        if ($user_id) {
            $response['event_views'] = $model->getEventViews($user_id, $start_date, $end_date);
        } else {
            http_response_code(400);
            $response['error'] = 'Missing user_id';
        }
        break;

    case 'unique_event_users':
        if ($user_id) {
            $response['unique_users'] = $model->getUniqueEventUsers($user_id, $start_date, $end_date);
        } else {
            http_response_code(400);
            $response['error'] = 'Missing user_id';
        }
        break;

    default:
        http_response_code(400);
        $response['error'] = 'Invalid or missing action parameter';
}

// Output result as JSON
echo json_encode($response);
