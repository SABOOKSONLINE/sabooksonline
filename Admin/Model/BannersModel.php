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
        $this->createStickyBannerTable();

        $sql = "SELECT * FROM sticky_banners";
        return $this->fetchAll($sql);
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

    private function createPopupBannerTable(): bool
    {
        $columns = [
            "id" => "INT AUTO_INCREMENT PRIMARY KEY",
            "book_public_key" => "VARCHAR(255) NOT NULL", // renamed from book_id
            "description" => "TEXT",
            "subtext" => "TEXT",
            "button_text" => "VARCHAR(150) DEFAULT 'Read Now'",
            "link" => "VARCHAR(255)",
            "date_from" => "DATE",
            "date_to" => "DATE",
            "time_from" => "TIME",
            "time_to" => "TIME",
            "is_active" => "TINYINT(1) DEFAULT 1",
            "created_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        return $this->createTable("popup_banners", $columns);
    }

    public function getPopupBanners(): array
    {
        $this->createPopupBannerTable();

        $sql = "SELECT * FROM popup_banners";
        return $this->fetchAll($sql);
    }

    public function addPopupBanner(array $data): int
    {
        $sql = "INSERT INTO popup_banners 
        (book_public_key, button_text, link, description, subtext, date_from, date_to, time_from, time_to) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->insert(
            $sql,
            "sssssssss", // all strings because book_public_key is VARCHAR
            [
                $data["book_public_key"],           // public key
                $data["button_text"] ?? 'Read Now',
                $data["link"],
                $data["description"],
                $data["subtext"],
                $data["date_from"],
                $data["date_to"],
                $data["time_from"],
                $data["time_to"]
            ]
        );
    }

    public function updatePopupBanner(string $bookPublicKey, array $data): int
    {
        $sql = "UPDATE popup_banners SET 
            button_text = ?, 
            link = ?, 
            description = ?, 
            subtext = ?, 
            date_from = ?, 
            date_to = ?, 
            time_from = ?, 
            time_to = ?
        WHERE book_public_key = ?"; // use public key

        return $this->update(
            $sql,
            "sssssssss",
            [
                $data["button_text"] ?? 'Read Now',
                $data["link"],
                $data["description"],
                $data["subtext"],
                $data["date_from"],
                $data["date_to"],
                $data["time_from"],
                $data["time_to"],
                $bookPublicKey
            ]
        );
    }

    public function removePopupBanner(string $bookPublicKey): int
    {
        return $this->delete(
            "DELETE FROM popup_banners WHERE book_public_key = ?",
            "s",
            [$bookPublicKey]
        );
    }
}
