<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class MobileBannerModel extends Model
{
    private function createMobileBannerTable(): bool
    {
        $columns = [
            "id" => "INT AUTO_INCREMENT PRIMARY KEY",
            "title" => "VARCHAR(255) NOT NULL",
            "description" => "TEXT",
            "image_url" => "VARCHAR(500) NOT NULL",
            "action_url" => "VARCHAR(500)",
            "screen" => "ENUM('home', 'books', 'profile', 'cart', 'search') NOT NULL DEFAULT 'home'",
            "priority" => "INT DEFAULT 0",
            "is_active" => "TINYINT(1) DEFAULT 1",
            "start_date" => "DATETIME DEFAULT CURRENT_TIMESTAMP",
            "end_date" => "DATETIME NULL",
            "created_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        return $this->createTable("Mobile_banners", $columns);
    }

    public function getAllMobileBanners(): array
    {
        $this->createMobileBannerTable();
        
        $sql = "SELECT * FROM Mobile_banners ORDER BY screen, priority DESC, created_at DESC";
        return $this->fetchAll($sql);
    }

    public function getMobileBannersByScreen(string $screen): array
    {
        $this->createMobileBannerTable();
        
        $sql = "SELECT * FROM Mobile_banners 
                WHERE screen = ? 
                AND is_active = 1 
                AND (start_date <= NOW()) 
                AND (end_date IS NULL OR end_date >= NOW())
                ORDER BY priority DESC, created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $screen);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $banners = [];
        while ($row = $result->fetch_assoc()) {
            $banners[] = $row;
        }
        
        $stmt->close();
        return $banners;
    }

    public function addMobileBanner(array $data): int
    {
        $this->createMobileBannerTable();
        
        $sql = "INSERT INTO Mobile_banners (title, description, image_url, action_url, screen, priority, is_active, start_date, end_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->insert(
            $sql,
            "sssssiiss",
            [
                $data["title"],
                $data["description"],
                $data["image_url"],
                $data["action_url"],
                $data["screen"],
                $data["priority"],
                $data["is_active"],
                $data["start_date"],
                $data["end_date"]
            ]
        );
    }

    public function updateMobileBanner(int $id, array $data): bool
    {
        $sql = "UPDATE Mobile_banners 
                SET title = ?, description = ?, image_url = ?, action_url = ?, 
                    screen = ?, priority = ?, is_active = ?, start_date = ?, end_date = ?
                WHERE id = ?";

        return $this->update(
            $sql,
            "sssssiissi",
            [
                $data["title"],
                $data["description"],
                $data["image_url"],
                $data["action_url"],
                $data["screen"],
                $data["priority"],
                $data["is_active"],
                $data["start_date"],
                $data["end_date"],
                $id
            ]
        );
    }

    public function deleteMobileBanner(int $id): int
    {
        return $this->delete(
            "DELETE FROM Mobile_banners WHERE id = ?",
            "i",
            [$id]
        );
    }

    public function getMobileBannerById(int $id): ?array
    {
        $sql = "SELECT * FROM Mobile_banners WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $banner = $result->fetch_assoc();
        $stmt->close();
        
        return $banner ?: null;
    }

    public function toggleMobileBannerStatus(int $id): bool
    {
        $sql = "UPDATE Mobile_banners SET is_active = NOT is_active WHERE id = ?";
        return $this->update($sql, "i", [$id]);
    }
}