<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    public function getBannersByType(string $type): ?array
    {
        $sql = "SELECT * FROM banners WHERE TYPE = '$type' ORDER BY ID ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $banners = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }

        return $banners;
    }
}
