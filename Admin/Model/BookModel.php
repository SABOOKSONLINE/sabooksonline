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
        return $this->fetchAll("SELECT ID, COVER, TITLE, CONTENTID, PUBLISHER FROM posts");
    }

    public function getFullBooks(): array
    {
        return $this->fetchAll("SELECT * FROM posts");
    }

    public function addListing(string $publicKey, string $category): int
    {
        return $this->insert(
            "INSERT INTO listings (CONTENTID, CATEGORY)
         VALUES (?, ?)
         ON DUPLICATE KEY UPDATE CATEGORY = VALUES(CATEGORY)",
            "ss",
            [$publicKey, $category]
        );
    }


    public function deleteListing(string $publicKey): int
    {
        return $this->delete("DELETE FROM listings WHERE CONTENTID = ?", "s", [$publicKey]);
    }

    public function createHardcopyPublishersTable(): bool
    {
        return $this->createTable('hardcopy_publishers', [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'user_id' => 'VARCHAR(255) NOT NULL',
            'email' => 'VARCHAR(255) NOT NULL UNIQUE',
            'name' => 'VARCHAR(255)',
            'can_publish' => 'TINYINT(1) DEFAULT 1',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);
    }

    public function addHardcopyPublisher(string $userId, string $email, string $name = ''): int
    {
        return $this->insert(
            "INSERT INTO hardcopy_publishers (user_id, email, name) VALUES (?, ?, ?)",
            "sss",
            [$userId, $email, $name]
        );
    }

    public function removeHardcopyPublisher(string $email): int
    {
        return $this->delete(
            "DELETE FROM hardcopy_publishers WHERE email = ?",
            "s",
            [$email]
        );
    }

    public function isAllowedToPublishHardcopy(string $userId): bool
    {
        $result = $this->fetch(
            "SELECT can_publish FROM hardcopy_publishers WHERE user_id = ? AND can_publish = 1"
        );
        return !empty($result);
    }

    public function getAllHardcopyPublishers(): array
    {
        return $this->fetchAll(
            "SELECT * FROM hardcopy_publishers ORDER BY created_at DESC"
        );
    }

    public function toggleHardcopyPublisher(string $email, bool $canPublish): int
    {
        return $this->update(
            "UPDATE hardcopy_publishers SET can_publish = ? WHERE email = ?",
            "is",
            [$canPublish ? 1 : 0, $email]
        );
    }

    public function getHardcopyPublisherByEmail(string $email): array
    {
        $result = $this->fetchPrepared(
            "SELECT * FROM hardcopy_publishers WHERE email = ?",
            "s",
            [$email]
        );
        return $result[0] ?? [];
    }

    public function getBooksTable(): array
    {
        return $this->fetchAll("SELECT * FROM books ORDER BY pub_date DESC, title ASC");
    }
}
