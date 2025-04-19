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

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $contentId, $contentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!mysqli_num_rows($result)) {
            return null;
        }

        $creatorData = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $creatorData;
    }
}
