<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class AcademicBookModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function selectBooks(): array
    {
        $sql = "SELECT academic_books.*, users.ADMIN_NAME, users.ADMIN_USERKEY
                FROM academic_books
                LEFT JOIN users
                    ON academic_books.publisher_id = users.ADMIN_ID
                WHERE academic_books.publish_date <= CURDATE();
                ";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $books;
    }


    public function selectBookByPublicKey(string $public_key): ?array
    {
        $sql = "SELECT * FROM academic_books WHERE public_key = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $public_key);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $book = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $book ?: null;
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
