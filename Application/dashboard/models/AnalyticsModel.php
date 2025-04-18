<?php
/**
 * AnalyticsModel - Handles all analytics-related database queries.
 */
class AnalyticsModel {
    private $con;

    /**
     * Constructor to inject the database connection.
     * @param mysqli $con Database connection object.
     */
    public function __construct($con) {
        $this->con = $con;
    }

    /**
     * Get total net income from completed product orders.
     * @return float Net income value.
     */
    public function getNetIncome() {
        $result = mysqli_query($this->con, "SELECT SUM(product_total) AS value_sum FROM product_order WHERE product_current = 'completed'");
        $row = mysqli_fetch_assoc($result);
        return $row['value_sum'] ?? 0;
    }

    /**
     * Get the total number of completed transactions.
     * @return int Number of completed transactions.
     */
    public function getTotalTransactions() {
        $result = mysqli_query($this->con, "SELECT COUNT(*) as number_rows FROM product_order WHERE product_current = 'COMPLETED'");
        $row = mysqli_fetch_assoc($result);
        return $row['number_rows'] ?? 0;
    }

    /**
     * Get the total number of registered users/customers.
     * @return int Total customer count.
     */
    public function getTotalCustomers() {
        $result = mysqli_query($this->con, "SELECT COUNT(*) as number_rows FROM user_info");
        $row = mysqli_fetch_assoc($result);
        return $row['number_rows'] ?? 0;
    }

    /**
     * Get the total number of pending/cart orders.
     * @return int Number of pending orders.
     */
    public function getPendingOrders() {
        $result = mysqli_query($this->con, "SELECT COUNT(*) as number_rows FROM product_order WHERE product_current = 'cart'");
        $row = mysqli_fetch_assoc($result);
        return $row['number_rows'] ?? 0;
    }

    // === SERVICE ANALYTICS ===

    /**
     * Get total views of a service within a date range.
     * @param int $provider_id Service provider's ID.
     * @param string $start_date
     * @param string $end_date
     * @return int Total service views.
     */
    public function getServiceViews($provider_id, $start_date, $end_date) {
        $query = "
            SELECT COUNT(*) AS visit_count
            FROM page_visits
            WHERE page_url LIKE ? AND page_url LIKE ? AND visit_time BETWEEN ? AND ?
        ";
        $stmt = $this->con->prepare($query);
        $like1 = "%provider%";
        $like2 = "%$provider_id%";
        $stmt->bind_param("ssss", $like1, $like2, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['visit_count'] ?? 0;
        $stmt->close();
        return $count;
    }

    /**
     * Get number of unique users who viewed a service.
     * @param int $provider_id
     * @param string $start_date
     * @param string $end_date
     * @return int Unique viewers count.
     */
    public function getUniqueServiceUsers($provider_id, $start_date, $end_date) {
        $query = "
            SELECT COUNT(DISTINCT user_ip) AS unique_count
            FROM page_visits
            WHERE page_url LIKE ? AND page_url LIKE ? AND visit_time BETWEEN ? AND ?
        ";
        $stmt = $this->con->prepare($query);
        $like1 = "%provider%";
        $like2 = "%$provider_id%";
        $stmt->bind_param("ssss", $like1, $like2, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['unique_count'] ?? 0;
        $stmt->close();
        return $count;
    }

    // === BOOK ANALYTICS ===

    /**
     * Get total views for a user's books.
     * @param int $user_id
     * @param string $start_date
     * @param string $end_date
     * @return int Total book views.
     */
    public function getBookViews($user_id, $start_date, $end_date) {
        $book_ids = $this->getBookContentIDs($user_id);
        if (empty($book_ids)) return 0;

        $count = 0;
        foreach ($book_ids as $book_id) {
            $like = "%book%";
            $content_like = "%$book_id%";
            $stmt = $this->con->prepare("
                SELECT COUNT(*) AS visit_count
                FROM page_visits
                WHERE page_url LIKE ? AND page_url LIKE ? AND visit_time BETWEEN ? AND ?
            ");
            $stmt->bind_param("ssss", $like, $content_like, $start_date, $end_date);
            $stmt->execute();
            $result = $stmt->get_result();
            $count += $result->fetch_assoc()['visit_count'] ?? 0;
            $stmt->close();
        }
        return $count;
    }

    /**
     * Get number of unique users who viewed user's books.
     * @param int $user_id
     * @param string $start_date
     * @param string $end_date
     * @return int Unique user count.
     */
    public function getUniqueBookUsers($user_id, $start_date, $end_date) {
        $book_ids = $this->getBookContentIDs($user_id);
        if (empty($book_ids)) return 0;

        $all_ips = [];
        foreach ($book_ids as $book_id) {
            $like = "%book%";
            $content_like = "%$book_id%";
            $stmt = $this->con->prepare("
                SELECT DISTINCT user_ip
                FROM page_visits
                WHERE page_url LIKE ? AND page_url LIKE ? AND visit_time BETWEEN ? AND ?
            ");
            $stmt->bind_param("ssss", $like, $content_like, $start_date, $end_date);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $all_ips[] = $row['user_ip'];
            }
            $stmt->close();
        }
        return count(array_unique($all_ips));
    }

    /**
     * Helper: Get all book content IDs for a specific user.
     * @param int $user_id
     * @return array List of content IDs.
     */
    private function getBookContentIDs($user_id) {
        $stmt = $this->con->prepare("SELECT CONTENTID FROM posts WHERE USERID = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book_ids = [];
        while ($row = $result->fetch_assoc()) {
            $book_ids[] = $row['CONTENTID'];
        }
        $stmt->close();
        return $book_ids;
    }

    // === EVENT ANALYTICS ===

    /**
     * Get total views for a user's event listings.
     * @param int $user_id
     * @param string $start_date
     * @param string $end_date
     * @return int Event views count.
     */
    public function getEventViews($user_id, $start_date, $end_date) {
        $query = "
            SELECT COUNT(*) AS visit_count
            FROM page_visits pv
            JOIN events e ON pv.page_url LIKE CONCAT('%', e.CONTENTID, '%')
            WHERE pv.page_url LIKE '%event-details%'
            AND e.USERID = ?
            AND DATE(pv.visit_time) BETWEEN ? AND ?
        ";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("sss", $user_id, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['visit_count'] ?? 0;
        $stmt->close();
        return $count;
    }

    /**
     * Get number of unique users who viewed user's events.
     * @param int $user_id
     * @param string $start_date
     * @param string $end_date
     * @return int Unique viewer count.
     */
    public function getUniqueEventUsers($user_id, $start_date, $end_date) {
        $query = "
            SELECT COUNT(DISTINCT pv.user_ip) AS unique_users
            FROM page_visits pv
            JOIN events e ON pv.page_url LIKE CONCAT('%', e.CONTENTID, '%')
            WHERE pv.page_url LIKE '%event%'
            AND e.USERID = ?
            AND DATE(pv.visit_time) BETWEEN ? AND ?
        ";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("sss", $user_id, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $unique = $result->fetch_assoc()['unique_users'] ?? 0;
        $stmt->close();
        return $unique;
    }
}
?>
