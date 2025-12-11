<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once __DIR__ . "/sessionAlerts.php";
require_once __DIR__ . "/../Core/Conn.php";
require_once __DIR__ . "/../Model/OrdersModel.php";

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['order_id'], $data['order_status'])) {
        throw new Exception("Invalid request data");
    }

    $orderId = (int)$data['order_id'];
    $newStatus = trim($data['order_status']);

    $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if (!in_array($newStatus, $validStatuses)) {
        throw new Exception("Invalid order status");
    }

    $ordersModel = new OrdersModel($conn);

    $order = $ordersModel->getOrder($orderId);
    if (empty($order)) {
        throw new Exception("Order not found");
    }

    $updatedRows = $ordersModel->updateOrderStatus($orderId, $newStatus);

    echo json_encode([
        'success' => $updatedRows > 0,
        'message' => $updatedRows > 0 ? 'Status updated' : 'No changes made'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
