<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/CartModel.php';
require_once __DIR__ . '/../models/BookModel.php';
require_once __DIR__ . '/../models/AcademicBookModel.php';
require_once __DIR__ . '/../Config/connection.php';

class OrderController
{
    private $cartModel;
    private $bookModel;
    private $academicBookModel;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->cartModel = new CartModel($conn);
        $this->bookModel = new BookModel($conn);
        $this->academicBookModel = new AcademicBookModel($conn);
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
        // Book details are already included via JOIN queries in CartModel

        include __DIR__ . '/../views/orders/myOrders.php';
    }

    public function orderDetails($orderId)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['ADMIN_ID'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['ADMIN_ID'];
        $orderData = $this->cartModel->getOrderDetails($orderId);

        if (!$orderData) {
            header('Location: /orders?error=not_found');
            exit;
        }

        // Verify order belongs to user
        if ($orderData['order']['user_id'] != $userId) {
            header('Location: /orders?error=unauthorized');
            exit;
        }
        // Book details are already included via JOIN queries in CartModel

        include __DIR__ . '/../views/orders/orderDetails.php';
    }
}
