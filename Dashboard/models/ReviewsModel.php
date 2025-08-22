<?php

class ReviewsModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    /**
     * Get reviews by content ID
     * @param string $contentid
     * @return array
     */
    public function getReviewsByContentId($contentid)
    {
        $sql = "SELECT * FROM reviews WHERE CONTENTID = ? ORDER BY DATEPOSTED DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param($stmt, "s", $contentid);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }
        $result = mysqli_stmt_get_result($stmt);
        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $reviews;
    }

    /**
     * Get reviews by user key
     * @param string $userkey
     * @return array
     */
    public function getReviewsByUserKey($userkey)
    {
        $sql = "SELECT * FROM reviews WHERE USERKEY = ? ORDER BY DATEPOSTED DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param($stmt, "s", $userkey);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }
        $result = mysqli_stmt_get_result($stmt);
        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $reviews;
    }

    /**
     * Update a review by ID
     * @param int $id
     * @param array $data (expects: review, status, rating, title)
     * @return bool
     */
    public function updateReview($id, $data)
    {
        $sql = "UPDATE reviews SET REVIEW = ?, STATUS = ?, RATING = ?, TITLE = ? WHERE ID = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param(
            $stmt,
            "ssisi",
            $data['review'],
            $data['status'],
            $data['rating'],
            $data['title'],
            $id
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows > 0;
    }

    /**
     * Delete a review by ID
     * @param int $id
     * @return bool
     */
    public function deleteReview($id)
    {
        $sql = "DELETE FROM reviews WHERE ID = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows > 0;
    }
}
