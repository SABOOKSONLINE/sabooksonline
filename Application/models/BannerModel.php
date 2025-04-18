<?php
// models/Banner.php

/**
 * Class BannerModel
 *
 * Handles operations related to fetching banners by type.
 */
class BannerModel
{
    /**
     * @var mysqli
     */
    private $conn;

    /**
     * BannerModel constructor.
     *
     * @param mysqli $conn MySQLi connection object
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Get the first banner of a given type.
     *
     * @param string $type The type of banner to fetch
     * @return array|null Returns the first banner row or null if not found
     */
    public function getFirstBannerByType(string $type): ?array
    {
        $type = mysqli_real_escape_string($this->conn, $type);
        $sql = "SELECT * FROM banners WHERE TYPE = '$type' ORDER BY ID ASC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        return mysqli_fetch_assoc($result) ?: null;
    }

    /**
     * Get all remaining banners of a given type (excluding the first one).
     *
     * @param string $type The type of banner to fetch
     * @return array An array of remaining banners (excluding the first)
     */
    public function getRemainingBannersByType(string $type): array
    {
        $type = mysqli_real_escape_string($this->conn, $type);
        $sql = "SELECT * FROM banners WHERE TYPE = '$type' ORDER BY ID ASC";
        $result = mysqli_query($this->conn, $sql);

        $banners = [];
        $skipFirst = true;

        // Skip the first row and collect the rest
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
