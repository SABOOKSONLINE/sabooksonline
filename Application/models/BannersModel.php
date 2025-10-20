<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class BannersModel extends Model
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

    private function createPageBannerTable(): bool
    {
        $columns = [
            "id" => "INT AUTO_INCREMENT PRIMARY KEY",
            "banner_image" => "VARCHAR(255) NOT NULL",
            "link" => "VARCHAR(2083) NOT NULL",
            "show_page" => "VARCHAR(255) NOT NULL",
            "is_active" => "TINYINT(1) DEFAULT 1",
            "created_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        return $this->createTable("page_banners", $columns);
    }

    public function getPageBanner(): array
    {
        $this->createPageBannerTable();

        $sql = "SELECT * FROM page_banners";
        return $this->fetchAll($sql);
    }

    private function createPopupBannerTable(): bool
    {
        $columns = [
            "id" => "INT AUTO_INCREMENT PRIMARY KEY",
            "book_public_key" => "TEXT",
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

        $sql = "SELECT pb.*, p.*
                FROM popup_banners AS pb
                LEFT JOIN posts AS p
                    ON pb.book_public_key COLLATE utf8mb4_general_ci = p.CONTENTID
                ORDER BY pb.id DESC
                LIMIT 1;";
        return $this->fetchAll($sql);
    }
}
