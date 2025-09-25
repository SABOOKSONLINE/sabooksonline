<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/AnalyticsModel.php";

class AnalyticsController extends Controller
{
    private AnalyticsModel $analyticsModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->analyticsModel = new AnalyticsModel($conn);
    }

    public function analytics(): void
    {
        $paymentPlans = $this->analyticsModel->paymentPlans();
        $paymentPlansRevenue = $this->analyticsModel->paymentPlansRevenue();

        $bookPurchases = $this->analyticsModel->bookPurchases();
        $bookPurchasesRevenue = $this->analyticsModel->bookPurchasesRevenue();

        $pageVisits = $this->analyticsModel->pageVisits();

        $this->render("analytics", [
            "payment_plans" => [
                "all" => $paymentPlans,
                "revenue" => $paymentPlansRevenue["revenue"]
            ],
            "book_purchases" => [
                "all" => $bookPurchases,
                "revenue" => $bookPurchasesRevenue["revenue"]
            ],
            "page_visits" => [
                "all" => $pageVisits
            ]
        ]);
    }
}
