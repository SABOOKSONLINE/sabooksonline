<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class MediaModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function selectMagazines(): array
    {
        $sql = "SELECT * FROM magazines";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Get result failed: " . mysqli_stmt_error($stmt));
        }

        $magazines = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $magazines[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $magazines;
    }

    public function selectMagazineById($publicKey): ?array
    {
        $sql = "SELECT * FROM magazines WHERE public_key = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $publicKey);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $magazine = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $magazine ?: null;
    }

    // NEWSPAPER METHODS
    public function selectNewspapers(): array
    {
        $sql = "SELECT * FROM newspapers";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $newspapers = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $newspapers[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $newspapers;
    }

    public function selectNewspaperById($publicKey): ?array
    {
        $sql = "SELECT * FROM newspapers WHERE public_key = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $publicKey);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $newspaper = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $newspaper ?: null;
    }

    public function selectUserById($id): ?array
    {
        $sql = "SELECT * FROM users WHERE ADMIN_ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $user ?: null;
    }
}
