<?php
class BookStoreModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Fetch bookstores by province or all
    public function fetchBookstores($province = null) {
        $province = $province ? mysqli_real_escape_string($this->conn, $province) : null;

        $sql = "SELECT * FROM users WHERE ADMIN_TYPE = 'book-store'";
        if ($province) {
            $sql .= " AND ADMIN_PROVINCE = '$province'";
        }

        $result = mysqli_query($this->conn, $sql);
        $bookstores = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bookstores[] = $row;
            }
        }

        return $bookstores;
    }
}
?>
