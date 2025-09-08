<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class BookModel extends Model
{
    public function countPublishedContent(): array
    {
        $result = $this->fetch(
            "SELECT SUM(total) AS total
            FROM (
                SELECT COUNT(*) AS total FROM posts
                UNION ALL
                SELECT COUNT(*) AS total FROM magazines
                UNION ALL
                SELECT COUNT(*) AS total FROM newspapers
                UNION ALL
                SELECT COUNT(*) AS total FROM academic_books
            ) AS counts"
        );

        return $result;
    }

    public function bookPurchase(): array
    {
        $result = $this->fetch("SELECT SUM(amount) AS gross FROM book_purchases");
        return $result;
    }

    public function countBookPurchase(): array
    {
        $result = $this->fetch("SELECT COUNT(*) AS total FROM book_purchases");
        return $result;
    }

    public function countBookViews(): array
    {
        $result = $this->fetch("SELECT COUNT(*) AS total
                                FROM page_visits
                                WHERE page_url REGEXP '^/library/book/[A-Za-z0-9]+$'");
        return $result;
    }

    public function countMediaViews(): array
    {
        $result = $this->fetch("SELECT COUNT(*) AS total
                                FROM page_visits
                                WHERE page_url LIKE '/media/%'");
        return $result;
    }

    public function getBooksListings(): array
    {
        $result = $this->fetchAll("SELECT p.*, l.CATEGORY AS section
                                    FROM posts AS p
                                    JOIN listings AS l 
                                        ON p.CONTENTID = l.CONTENTID
                                    ORDER BY l.CATEGORY, p.TITLE");
        return $result;
    }


    public function getAllBooks(): array
    {
        return $this->fetchAll("SELECT COVER, TITLE, CONTENTID, PUBLISHER FROM posts");
    }

    public function deleteListing(string $publicKey): int
    {
        return $this->delete("DELETE FROM listings WHERE CONTENTID = ?", "s", [$publicKey]);
    }
}
