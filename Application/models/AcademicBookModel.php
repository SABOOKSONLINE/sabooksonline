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

    public function selectBooks(int $publisher_id): array
    {
        $sql = "SELECT * FROM academic_books WHERE publisher_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $publisher_id);

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

    public function selectBookById(int $id, int $publisher_id): ?array
    {
        $sql = "SELECT * FROM academic_books WHERE id = ? AND publisher_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "ii", $id, $publisher_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $book = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $book ?: null;
    }
}
