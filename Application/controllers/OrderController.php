<?php
require_once __DIR__ . '/../models/CartModel.php';
require_once __DIR__ . '/../Config/connection.php';

class OrderController
{
    private $cartModel;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->cartModel = new CartModel($conn);
    }

    public function myOrders()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['ADMIN_ID'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['ADMIN_ID'];
        $orders = $this->cartModel->getOrders($userId);

        include __DIR__ . '/../views/orders/myOrders.php';
    }

    public function orderDetails(int $orderId)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['ADMIN_ID'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['ADMIN_ID'];
        $order = $this->cartModel->getOrderDetails($orderId, $userId);

        if (!$order) {
            header('Location: /orders?error=not_found');
            exit;
        }

        include __DIR__ . '/../views/orders/orderDetails.php';
    }

    public function retryPayment(int $orderId)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['ADMIN_ID'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['ADMIN_ID'];
        $order = $this->cartModel->getOrderDetails($orderId, $userId);

        if (!$order) {
            header('Location: /orders?error=not_found');
            exit;
        }

        // Check if order is already paid
        if (($order['payment_status'] ?? 'pending') === 'paid') {
            header('Location: /orders/' . $orderId . '?error=already_paid');
            exit;
        }

        // Get user details - query directly since UserModel doesn't have getUserById
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE ADMIN_ID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user) {
            header('Location: /orders?error=user_not_found');
            exit;
        }

        // Store order ID in session for payment return
        $_SESSION['pending_order_id'] = $orderId;

        // Total amount already includes shipping fee
        $totalAmount = $order['total_amount'] ?? 0;

        // Generate payment using CheckoutController
        require_once __DIR__ . '/CheckoutController.php';
        $checkoutController = new CheckoutController($this->conn);
        $checkoutController->generatePayment($totalAmount, $user, $orderId);
    }
}
