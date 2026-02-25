<?php
/**
 * Order Helper Functions
 * 
 * Reusable functions for handling order operations like deletion and validation.
 * Each function performs a single, well-defined task.
 */

/**
 * Validates if a user can delete an order
 * 
 * @param mysqli $conn Database connection
 * @param int $orderId The order ID
 * @param int $userId The user ID
 * @return array Returns ['can_delete' => bool, 'order' => array|null, 'error' => string|null]
 */
function canUserDeleteOrder(mysqli $conn, int $orderId, int $userId): array {
    // Get order details and verify ownership
    $stmt = $conn->prepare("SELECT id, user_id, payment_status, order_status FROM orders WHERE id = ? AND user_id = ?");
    if (!$stmt) {
        return ['can_delete' => false, 'order' => null, 'error' => 'Database error'];
    }
    
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
    
    if (!$order) {
        return ['can_delete' => false, 'order' => null, 'error' => 'Order not found or access denied'];
    }
    
    // Only allow deletion of unpaid orders
    $paymentStatus = strtolower($order['payment_status'] ?? 'pending');
    if ($paymentStatus === 'paid') {
        return ['can_delete' => false, 'order' => $order, 'error' => 'Cannot delete paid orders'];
    }
    
    return ['can_delete' => true, 'order' => $order, 'error' => null];
}

/**
 * Deletes an order and its associated items
 * 
 * @param mysqli $conn Database connection
 * @param int $orderId The order ID
 * @return bool True if deletion successful, false otherwise
 */
function deleteOrder(mysqli $conn, int $orderId): bool {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete order items first (foreign key constraint)
        $stmtItems = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        if (!$stmtItems) {
            throw new Exception("Failed to prepare delete order items statement");
        }
        
        $stmtItems->bind_param("i", $orderId);
        if (!$stmtItems->execute()) {
            throw new Exception("Failed to delete order items: " . $stmtItems->error);
        }
        $stmtItems->close();
        
        // Delete the order
        $stmtOrder = $conn->prepare("DELETE FROM orders WHERE id = ?");
        if (!$stmtOrder) {
            throw new Exception("Failed to prepare delete order statement");
        }
        
        $stmtOrder->bind_param("i", $orderId);
        if (!$stmtOrder->execute()) {
            throw new Exception("Failed to delete order: " . $stmtOrder->error);
        }
        $stmtOrder->close();
        
        // Commit transaction
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        error_log("Order deletion failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Checks if an order can be deleted (unpaid status)
 * 
 * @param string $paymentStatus The payment status
 * @return bool True if order can be deleted, false otherwise
 */
function isOrderDeletable(string $paymentStatus): bool {
    $status = strtolower($paymentStatus);
    // Only unpaid orders can be deleted
    return in_array($status, ['pending', 'failed', 'refunded']);
}

/**
 * Gets the delete button HTML for an order
 * 
 * @param int $orderId The order ID
 * @param string $buttonClass Additional CSS classes for the button
 * @param string $buttonText Button text (default: "Remove Order")
 * @return string HTML for the delete button
 */
function getOrderDeleteButton(int $orderId, string $buttonClass = '', string $buttonText = 'Remove Order'): string {
    $classes = !empty($buttonClass) ? " $buttonClass" : '';
    $html = '<button type="button" class="btn btn-outline-danger delete-order-btn' . htmlspecialchars($classes) . '" ';
    $html .= 'data-order-id="' . $orderId . '" ';
    $html .= 'title="Remove this unpaid order">';
    $html .= '<i class="fas fa-trash me-2"></i>' . htmlspecialchars($buttonText);
    $html .= '</button>';
    return $html;
}
