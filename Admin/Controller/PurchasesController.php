<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/AnalyticsModel.php";

class PurchasesController extends Controller
{
    private AnalyticsModel $analyticsModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->analyticsModel = new AnalyticsModel($conn);
    }

    public function purchases(): void
    {
        $bookPurchases = $this->analyticsModel->getDetailedBookPurchases();
        $bookPurchasesRevenue = $this->analyticsModel->bookPurchasesRevenue();

        $this->render("purchases", [
            "book_purchases" => [
                "all" => $bookPurchases,
                "revenue" => $bookPurchasesRevenue["revenue"]
            ]
        ]);
    }
}