<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/OrdersModel.php";

class OrdersController extends Controller
{
    private OrdersModel $ordersModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->ordersModel = new OrdersModel($conn);
    }

    public function orders()
    {
        $orders = $this->ordersModel->getAllOrders();
        $allItems = [];

        foreach ($orders as $order) {
            $allItems[$order['id']] = $this->ordersModel->getOrderItems($order['id']);
        }

        $this->render("orders", [
            "orders" => $orders,
            "items"  => $allItems
        ]);
    }


    public function orderItems(int $orderId)
    {
        $items = $this->ordersModel->getOrderItems($orderId);

        $this->render("order_items", [
            "items" => $items
        ]);
    }
}
