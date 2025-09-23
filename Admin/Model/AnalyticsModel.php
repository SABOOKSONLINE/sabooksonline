<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class AnalyticsModel extends Model
{

    function paymentPlans(): array
    {
        $sql = "SELECT * FROM payment_plans";
        return $this->fetchAll($sql);
    }

    function paymentPlansRevenue(): array
    {
        $sql = "SELECT SUM(amount_paid) AS revenue FROM payment_plans";
        return $this->fetch($sql);
    }

    function bookPurchases(): array
    {
        $sql = "SELECT * FROM book_purchases";
        return $this->fetchAll($sql);
    }

    function bookPurchasesRevenue(): array
    {
        $sql = "SELECT SUM(amount) AS revenue FROM book_purchases";
        return $this->fetch($sql);
    }
}
