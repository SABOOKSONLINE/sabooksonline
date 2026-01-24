<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class MobileBannerModel extends Model
{
    public function createMobileBannerTable(): bool
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
        
        $sql = "SELECT id, title, description, image_url, action_url, screen, priority, start_date, end_date, created_at, updated_at, 1 as is_active FROM Mobile_banners ORDER BY screen, priority DESC, created_at DESC";
        return $this->fetchAll($sql);
    }

    public function getMobileBannersByScreen(string $screen): array
    {
        $this->createMobileBannerTable();
        
        $sql = "SELECT id, title, description, image_url, action_url, screen, priority, start_date, end_date, created_at, updated_at, 1 as is_active FROM Mobile_banners 
                WHERE screen = ? 
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
        try {
            $this->createMobileBannerTable();
            
            // Debug: Log the data being processed
            error_log("📱 MobileBannerModel->addMobileBanner data: " . print_r($data, true));
            
            $sql = "INSERT INTO Mobile_banners (title, description, image_url, action_url, screen, priority, start_date, end_date)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            error_log("📝 SQL Query: " . $sql);
            error_log("📋 Parameters: " . print_r([
                $data["title"],
                $data["description"],
                $data["image"],
                $data["action_url"],
                $data["screen"],
                $data["priority"],
                $data["start_date"],
                $data["end_date"]
            ], true));

            $result = $this->insert(
                $sql,
                "sssssiis",
                [
                    $data["title"],
                    $data["description"],
                    $data["image_url"], // Use correct field name for database
                    $data["action_url"],
                    $data["screen"],
                    $data["priority"],
                    $data["start_date"],
                    $data["end_date"]
                ]
            );
            
            error_log("✅ Insert result: " . ($result ? "Success (ID: $result)" : "Failed"));
            return $result;
            
        } catch (Exception $e) {
            error_log("❌ Database error in addMobileBanner: " . $e->getMessage());
            error_log("❌ Stack trace: " . $e->getTraceAsString());
            throw $e; // Re-throw to be caught by controller
        }
    }

    public function updateMobileBanner(int $id, array $data): bool
    {
        $sql = "UPDATE Mobile_banners 
                SET title = ?, description = ?, image_url = ?, action_url = ?, 
                    screen = ?, priority = ?, start_date = ?, end_date = ?
                WHERE id = ?";

        return $this->update(
            $sql,
            "sssssissi",
            [
                $data["title"],
                $data["description"],
                $data["image_url"], // Use correct field name for database
                $data["action_url"],
                $data["screen"],
                $data["priority"],
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
        $sql = "SELECT id, title, description, image_url, action_url, screen, priority, start_date, end_date, created_at, updated_at, 1 as is_active FROM Mobile_banners WHERE id = ?";
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
        // Since the existing table doesn't have is_active column, we'll use start_date/end_date
        // Toggle by setting end_date to NOW() if null, or NULL if set
        $sql = "UPDATE Mobile_banners SET end_date = CASE WHEN end_date IS NULL THEN NOW() ELSE NULL END WHERE id = ?";
        return $this->update($sql, "i", [$id]);
    }
}