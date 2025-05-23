<?php

/**
 * AnalyticsModel - Handles all analytics-related database queries.
 */
class AnalyticsModel
{
    private $conn;

    /**
     * Constructor to inject the database connection.
     * @param mysqli $conn Database connection object.
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Get the count of titles for a specific user.
     * @param string $user_id The ID of the user.
     * @return int The count of titles.
     */
    public function getTitlesCount($user_id)
    {
        $query = "SELECT COUNT(*) as total FROM posts WHERE USERID = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $count = 0;
        if ($row = $result->fetch_assoc()) {
            $count = (int)$row['total'];
        }
        $stmt->close();

        return $count;
    }
}
