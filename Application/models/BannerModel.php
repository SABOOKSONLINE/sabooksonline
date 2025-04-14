<?php
// models/Banner.php

class BannerModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getFirstBannerByType($type)
    {
        $type = mysqli_real_escape_string($this->conn, $type);
        $sql = "SELECT * FROM banners WHERE TYPE = '$type' ORDER BY ID ASC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function getRemainingBannersByType($type)
    {
        $type = mysqli_real_escape_string($this->conn, $type);
        $sql = "SELECT * FROM banners WHERE TYPE = '$type' ORDER BY ID ASC";
        $result = mysqli_query($this->conn, $sql);

        $banners = [];
        $skipFirst = true;

        while ($row = mysqli_fetch_assoc($result)) {
            if ($skipFirst) {
                $skipFirst = false;
                continue;
            }
            $banners[] = $row;
        }

        return $banners;
    }
}
?>
