<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class BannerModel extends Model
{

    private function createStickyBannerTable(): bool
    {
        $columns = [
            "id" => "INT AUTO_INCREMENT PRIMARY KEY",
            "heading" => "VARCHAR(255) NOT NULL",
            "subheading" => "TEXT NOT NULL",
            "button_text" => "VARCHAR(150) NOT NULL",
            "button_link" => "VARCHAR(255) NOT NULL",
            "is_active" => "TINYINT(1) DEFAULT 1",
            "created_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
        ];

        return $this->createTable("sticky_banners", $columns);
    }

    public function getStickyBanners(): array
    {
        if ($this->createStickyBannerTable()) {
            $sql = "SELECT * FROM sticky_banners";
            return $this->fetchAll($sql);
        }
        return [];
    }

    public function addStickyBanner(array $data): int
    {
        $sql = "INSERT INTO sticky_banners (heading, subheading, button_text, button_link)
                VALUES (?, ?, ?, ?)";

        return $this->insert(
            $sql,
            "ssss",
            [$data["heading"], $data["subheading"], $data["button_text"], $data["button_link"]]
        );
    }

    public function removeStickyBanner(int $id): int
    {
        return $this->delete(
            "DELETE FROM sticky_banners WHERE id = ?",
            "i",
            [$id]
        );
    }

    public function getPageBanner(): array
    {
        $sql = "SELECT * FROM banners";
        return $this->fetchAll($sql);
    }

    public function addPageBanner(array $data): int
    {
        $sql = "INSERT INTO banners (SLIDE, IMAGE, UPLOADED, TYPE, sort_order)";
        return $this->insert(
            $sql,
            "ssssi",
            [$data["slide"], $data["image"], $data["upload"], "Home", 0]
        );
    }

    public function removePageBanner(int $id): int
    {
        return $this->delete(
            "DELETE FROM banners WHERE id = ?",
            "i",
            [$id]
        );
    }
}
