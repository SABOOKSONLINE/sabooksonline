<?php

/**
 * Class BookStoreModel
 *
 * Handles data operations related to bookstores.
 */
class BookStoreModel {
    /**
     * @var mysqli Database connection instance
     */
    private $conn;

    /**
     * BookStoreModel constructor.
     *
     * @param mysqli $conn MySQLi database connection
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Fetch all bookstores, optionally filtered by province.
     *
     * @param string|null $province Optional province filter
     * @return array List of bookstores as associative arrays
     */
    public function fetchBookstores(?string $province = null): array {
        // Sanitize input if provided
        $province = $province ? mysqli_real_escape_string($this->conn, $province) : null;

        // Base query for bookstores
        $sql = "SELECT * FROM users WHERE ADMIN_TYPE = 'book-store'";

        // Add province filter if needed
        if ($province) {
            $sql .= " AND ADMIN_PROVINCE = '$province'";
        }

        $result = mysqli_query($this->conn, $sql);
        $bookstores = [];

        // Fetch results if any
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bookstores[] = $row;
            }
        }

        return $bookstores;
    }
}
?>
