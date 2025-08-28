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

        $stmt = mysqli_pofpare($this->conn, $sql);
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

      public function getNewspapers($updatedSince = null): array
    {
        $sql = "SELECT * FROM newspapers";

        if ($updatedSince) {
            try {
                $lastUpdatedDateTime = new DateTime($updatedSince);
                $formattedDate = $lastUpdatedDateTime->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                throw new Exception("Invalid 'updatedSince' timestamp format: " . $e->getMessage());
            }

            $sql .= " WHERE updated_at > ?"; // Filter records updated after the given timestamp
            $sql .= " ORDER BY updated_at ASC"; // Order by update time for consistent delta fetching
        }

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement for newspapers: " . mysqli_error($this->conn));
        }

        if ($updatedSince) {
            if (!mysqli_stmt_bind_param($stmt, "s", $formattedDate)) {
                throw new Exception("Failed to bind parameters for newspapers: " . mysqli_stmt_error($stmt));
            }
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement for newspapers: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to get result for newspapers: " . mysqli_stmt_error($stmt));
        }

        $newspapers = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($row['updated_at'])) {
                try {
                    $row['updated_at'] = (new DateTime($row['updated_at']))->format(DateTime::ISO8601);
                } catch (Exception $e) {
                    error_log("Error formatting updated_at for newspaper ID {$row['ID']}: " . $e->getMessage());
                }
            }
            $newspapers[] = $row;
        }

        mysqli_stmt_close($stmt);

        return $newspapers;
    }
    public function getMagazines($updatedSince = null): array
    {
        $sql = "SELECT * FROM magazines";

        // Add a conditional WHERE clause for delta syncing
        if ($updatedSince) {
            // Create a DateTime object from the ISO 8601 string.
            try {
                $lastUpdatedDateTime = new DateTime($updatedSince);
                $formattedDate = $lastUpdatedDateTime->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                throw new Exception("Invalid 'updatedSince' timestamp format: " . $e->getMessage());
            }

            $sql .= " WHERE updated_at > ?"; // Filter records updated after the given timestamp
            $sql .= " ORDER BY updated_at ASC"; // Order by update time for consistent delta fetching
        } else {
            $sql .= " ORDER BY RAND()"; // Or replace with specific order if RAND() is not desired
        }

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement for magazines: " . mysqli_error($this->conn));
        }

        // Bind the parameter if $updatedSince is provided
        if ($updatedSince) {
            if (!mysqli_stmt_bind_param($stmt, "s", $formattedDate)) {
                throw new Exception("Failed to bind parameters for magazines: " . mysqli_stmt_error($stmt));
            }
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement for magazines: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to get result for magazines: " . mysqli_stmt_error($stmt));
        }

        $magazines = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Important: Reformat the 'updated_at' timestamp to ISO 8601 before returning to the client.
            if (isset($row['updated_at'])) {
                try {
                    $row['updated_at'] = (new DateTime($row['updated_at']))->format(DateTime::ISO8601);
                } catch (Exception $e) {
                    error_log("Error formatting updated_at for magazine ID {$row['ID']}: " . $e->getMessage());
                }
            }
            $magazines[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $magazines;
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
