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
        // Try to match regular books first (numeric book_id)
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
                    COALESCE(p.title, ab.title) AS book_title,
                    COALESCE(p.cover, ab.cover_image_path) AS book_cover,
                    COALESCE(p.publisher, CAST(ab.publisher_id AS CHAR)) AS book_publisher,
                    COALESCE(p.contentid, ab.public_key) AS book_contentid,
                    COALESCE(p.ebookprice, ab.ebook_price) AS ebook_price,
                    COALESCE(p.abookprice, 0) AS audiobook_price,
                    CASE 
                        WHEN p.id IS NOT NULL THEN 'regular'
                        WHEN ab.public_key IS NOT NULL THEN 'academic'
                        ELSE 'unknown'
                    END AS book_type
                FROM book_purchases bp
                LEFT JOIN posts p ON bp.book_id REGEXP '^[0-9]+$' AND CAST(bp.book_id AS UNSIGNED) = p.id
                LEFT JOIN academic_books ab ON bp.book_id NOT REGEXP '^[0-9]+$' AND bp.book_id = ab.public_key
                ORDER BY bp.payment_date DESC";
        return $this->fetchAll($sql);
    }
}
