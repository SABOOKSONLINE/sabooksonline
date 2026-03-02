<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class CreatorModel
{

    private $conn;

    // Constructor: Initialize database connection
    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function getCreatorByContentId($contentId)
    {
        $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = ? OR ADMIN_NAME = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "ss", $contentId, $contentId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (!$result || mysqli_num_rows($result) === 0) {
            mysqli_stmt_close($stmt);
            return null;
        }

        $creatorData = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $creatorData;
    }
}
