<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class AnalyticsModel extends Model
{

    public function paymentPlans(): array
    {
        $sql = "SELECT * FROM payment_plans";
        return $this->fetchAll($sql);
    }

    public function paymentPlansRevenue(): array
    {
        $sql = "SELECT SUM(amount_paid) AS revenue FROM payment_plans";
        return $this->fetch($sql);
    }

    public function bookPurchases(): array
    {
        $sql = "SELECT 
                    bp.id,
                    bp.user_email,
                    bp.book_id,
                    bp.user_key,
                    bp.format,
                    bp.payment_id,
                    bp.amount,
                    bp.payment_status,
                    bp.payment_date,
                    p.title AS book_title,
                    p.cover AS book_cover,
                    p.publisher AS book_publisher,
                    p.contentid AS book_contentid
                FROM book_purchases bp
                LEFT JOIN posts p ON bp.book_id = p.id
                ORDER BY bp.payment_date DESC";
        return $this->fetchAll($sql);
    }

    public function bookPurchasesRevenue(): array
    {
        $sql = "SELECT SUM(amount) AS revenue FROM book_purchases";
        return $this->fetch($sql);
    }


    public function pageVisits(): array
    {
        $sql = "SELECT * FROM page_visits ORDER BY page_visits.id DESC";
        return $this->fetchAll($sql);
    }

    public function getDetailedBookPurchases(): array
    {
        $sql = "SELECT 
                    bp.id,
                    bp.user_email,
                    bp.book_id,
                    bp.user_key,
                    bp.format,
                    bp.payment_id,
                    bp.amount,
                    bp.payment_status,
                    bp.payment_date,
                    p.title AS book_title,
                    p.cover AS book_cover,
                    p.publisher AS book_publisher,
                    p.contentid AS book_contentid,
                    p.ebookprice AS ebook_price,
                    p.abookprice AS audiobook_price
                FROM book_purchases bp
                LEFT JOIN posts p ON bp.book_id = p.id
                ORDER BY bp.payment_date DESC";
        return $this->fetchAll($sql);
    }
}
