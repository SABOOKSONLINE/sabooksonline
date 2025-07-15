<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ReviewsModel
{

    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function insertReview($data)
    {
        $sql = "INSERT INTO reviews (name, user_img_url, rating, comment, user_id, book_id) 
            VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $data['name'],
            $data['user_img_url'],
            $data['rating'],
            $data['comment'],
            $data['user_id'],
            $data['book_id']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        return true;
    }


    public function getReviewsByBookId($bookId)
    {
        $sql = "SELECT * FROM reviews WHERE book_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $bookId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to fetch result: " . mysqli_error($this->conn));
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);

        return $rows;
    }

    public function updateReview($data)
    {
        $sql = "UPDATE reviews SET comment = ? WHERE user_id = ? AND book_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "sss", $data['comment'], $data['user_id'], $data['book_id']);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows > 0;
    }
}
