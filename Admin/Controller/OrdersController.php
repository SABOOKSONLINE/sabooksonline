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
        $this->render("orders", [
            "orders" => ""
        ]);
    }
}
