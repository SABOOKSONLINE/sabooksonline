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

    public function getDownloadsByEmail($email) {
    $sql = "
        SELECT COUNT(*) AS book_count
        FROM book_purchases
        WHERE user_email = ?
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return (int)$row['book_count'];
    } else {
        error_log("❌ Failed to count purchased books: " . $stmt->error);
        return 0;
    }
}


    public function getBookViews($user_id) {
    // Get book content IDs posted by user
    $query = "SELECT CONTENTID FROM posts WHERE USERID = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) return ['unique_user_count' => 0];

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book_ids = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (empty($book_ids)) return ['unique_user_count' => 0];

    $contentIds = array_column($book_ids, 'CONTENTID');

    // Build placeholders for IN clause
    $placeholders = implode(',', array_fill(0, count($contentIds), '?'));
    $query = "SELECT COUNT(DISTINCT user_ip) AS unique_user_count
              FROM page_visits AS pv
              INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
              WHERE pv.page_url LIKE ? AND p.CONTENTID IN ($placeholders)";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) return ['unique_user_count' => 0];

    $likePattern = "%book%";
    $params = array_merge([$likePattern], $contentIds);
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->bind_result($unique_user_count);
    $stmt->fetch();
    $stmt->close();

    return ['unique_user_count' => $unique_user_count];
}



public function getUserMostViewedBooks($user_id) {
    // Step 1: Get all books posted by the user
    $query = "SELECT CONTENTID, TITLE, COVER FROM posts WHERE USERID = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) return [];

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (empty($books)) return [];

    // Step 2: Build dynamic placeholders for content IDs
    $contentIds = array_column($books, 'CONTENTID');
    $placeholders = implode(',', array_fill(0, count($contentIds), '?'));

    // Step 3: Query to count views per book
    $query = "SELECT p.CONTENTID, p.TITLE, p.COVER, COUNT(DISTINCT pv.user_ip) AS view_count
              FROM page_visits AS pv
              INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
              WHERE p.CONTENTID IN ($placeholders)
              GROUP BY p.CONTENTID
              ORDER BY view_count DESC";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) return [];

    // All params are strings
    $types = str_repeat("s", count($contentIds));
    $stmt->bind_param($types, ...$contentIds);
    $stmt->execute();
    $result = $stmt->get_result();
    $viewedBooks = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $viewedBooks;
}


    public function getProfileViews($user_id) {
        $likePattern = "%$user_id%";
        $sql = "SELECT COUNT(*) AS visit_count FROM page_visits WHERE page_url LIKE ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return ['visit_count' => 0];

        $stmt->bind_param("s", $likePattern);
        $stmt->execute();
        $stmt->bind_result($visit_count);
        $stmt->fetch();
        $stmt->close();

        return ['visit_count' => $visit_count];
    }

    public function getServiceViews($provider_id, $start_date = null, $end_date = null) {
    $likePattern = "%provider%";
    $providerPattern = "%$provider_id%";

    // If no date range is provided, remove BETWEEN clause
    if ($start_date && $end_date) {
        $query = "SELECT COUNT(*) AS visit_count
                  FROM page_visits
                  WHERE page_url LIKE ? AND page_url LIKE ? AND visit_time BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $likePattern, $providerPattern, $start_date, $end_date);
    } else {
        $query = "SELECT COUNT(*) AS visit_count
                  FROM page_visits
                  WHERE page_url LIKE ? AND page_url LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $likePattern, $providerPattern);
    }

    if (!$stmt) return 0;

    $stmt->execute();
    $stmt->bind_result($visit_count);
    $stmt->fetch();
    $stmt->close();

    return ['visit_count' => $visit_count];
}


public function getEventViews($user_id, $start_date = null, $end_date = null) {
    // Get event CONTENTIDs
    $query = "SELECT CONTENTID FROM events WHERE USERID = ?";
    $stmt = $this->conn->prepare($query);

    if (!$stmt) return ['visit_count' => 0];

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event_ids = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (empty($event_ids)) return ['visit_count' => 0];

    $event_ids = array_column($event_ids, 'CONTENTID');
    $likePattern = "%event-details%";

    $combined_visit_count = 0;
    $query = "SELECT COUNT(*) AS visit_count
              FROM page_visits AS pv
              INNER JOIN events AS e ON pv.page_url LIKE CONCAT('%', e.CONTENTID, '%')
              WHERE pv.page_url LIKE ? AND e.CONTENTID = ?";

    // Append date filter if provided
    if ($start_date && $end_date) {
        $query .= " AND DATE(pv.visit_time) BETWEEN ? AND ?";
    }

    $stmt = $this->conn->prepare($query);

    if (!$stmt) return ['visit_count' => 0];

    foreach ($event_ids as $event_id) {
        if ($start_date && $end_date) {
            $stmt->bind_param("ssss", $likePattern, $event_id, $start_date, $end_date);
        } else {
            $stmt->bind_param("ss", $likePattern, $event_id);
        }

        $stmt->execute();
        $stmt->bind_result($visit_count);
        $stmt->fetch();
        $combined_visit_count += $visit_count;
    }

    $stmt->close();
    return ['visit_count' => $combined_visit_count];
}



    public function viewSubscription($userId) {
        $sql = "SELECT admin_subscription, billing_cycle, subscription_status 
                FROM users 
                WHERE ADMIN_USERKEY = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $userId); // using "s" since userId is a string
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
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

        // ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function getBookViewsByMonthYear($user_id)
    {
        $query = "SELECT CONTENTID FROM posts WHERE USERID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book_ids = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($book_ids)) return [];

        $contentIds = array_column($book_ids, 'CONTENTID');
        $placeholders = implode(',', array_fill(0, count($contentIds), '?'));

        $query = "SELECT DATE_FORMAT(visit_time, '%Y-%m') AS month_year, COUNT(*) AS views
              FROM page_visits AS pv
              INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
              WHERE pv.page_url LIKE ? AND p.CONTENTID IN ($placeholders)
              GROUP BY month_year ORDER BY month_year DESC";

        $likePattern = "%book%";
        $params = array_merge([$likePattern], $contentIds);
        $types = str_repeat("s", count($params));
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProfileViewsByMonthYear($user_id)
    {
        $likePattern = "%$user_id%";
        $query = "SELECT DATE_FORMAT(visit_time, '%Y-%m') AS month_year, COUNT(*) AS views
              FROM page_visits
              WHERE page_url LIKE ?
              GROUP BY month_year ORDER BY month_year DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $likePattern);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getServiceViewsByMonthYear($provider_id)
    {
        $likePattern = "%provider%";
        $providerPattern = "%$provider_id%";

        $query = "SELECT DATE_FORMAT(visit_time, '%Y-%m') AS month_year, COUNT(*) AS views
              FROM page_visits
              WHERE page_url LIKE ? AND page_url LIKE ?
              GROUP BY month_year ORDER BY month_year DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $likePattern, $providerPattern);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEventViewsByMonthYear($user_id)
    {
        $query = "SELECT CONTENTID FROM events WHERE USERID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event_ids = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($event_ids)) return [];

        $event_ids = array_column($event_ids, 'CONTENTID');
        $results = [];

        $query = "SELECT DATE_FORMAT(pv.visit_time, '%Y-%m') AS month_year, COUNT(*) AS views
              FROM page_visits AS pv
              INNER JOIN events AS e ON pv.page_url LIKE CONCAT('%', e.CONTENTID, '%')
              WHERE pv.page_url LIKE ? AND e.CONTENTID = ?
              GROUP BY month_year ORDER BY month_year DESC";

        $likePattern = "%event-details%";

        foreach ($event_ids as $event_id) {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $likePattern, $event_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $month_year = $row['month_year'];
                $views = (int)$row['views'];
                if (!isset($results[$month_year])) {
                    $results[$month_year] = 0;
                }
                $results[$month_year] += $views;
            }
            $stmt->close();
        }

        // Convert associative array to indexed array
        $finalResult = [];
        foreach ($results as $month_year => $views) {
            $finalResult[] = ['month_year' => $month_year, 'views' => $views];
        }

        return $finalResult;
    }

    // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get book views by country
     */
    public function getBookViewsByCountry($user_id)
    {
        $book_ids = $this->getUserBookIds($user_id);
        if (empty($book_ids)) return [];

        $placeholders = implode(',', array_fill(0, count($book_ids), '?'));
        $likePattern = "%book%";

        $query = "SELECT pv.user_country AS country, COUNT(*) AS views
          FROM page_visits AS pv
          INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
          WHERE pv.page_url LIKE ? AND p.CONTENTID IN ($placeholders) 
          AND pv.user_country IS NOT NULL AND pv.user_country != 'Unknown'
          GROUP BY pv.user_country
          ORDER BY views DESC";

        $stmt = $this->conn->prepare($query);
        $params = array_merge([$likePattern], $book_ids);
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get book views by province/state
     */
    public function getBookViewsByProvince($user_id, $country = null)
    {
        $book_ids = $this->getUserBookIds($user_id);
        if (empty($book_ids)) return [];

        $placeholders = implode(',', array_fill(0, count($book_ids), '?'));
        $likePattern = "%book%";

        $query = "SELECT pv.user_province AS province, COUNT(*) AS views
          FROM page_visits AS pv
          INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
          WHERE pv.page_url LIKE ? AND p.CONTENTID IN ($placeholders)
          AND pv.user_province IS NOT NULL AND pv.user_province != 'Unknown'";

        $params = [$likePattern];
        $types = "s";

        if ($country) {
            $query .= " AND pv.user_country = ?";
            $params[] = $country;
            $types .= "s";
        }

        $query .= " GROUP BY pv.user_province ORDER BY views DESC";

        $params = array_merge($params, $book_ids);
        $types .= str_repeat("s", count($book_ids));

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get book views by city
     */
    public function getBookViewsByCity($user_id, $country = null, $province = null)
    {
        $book_ids = $this->getUserBookIds($user_id);
        if (empty($book_ids)) return [];

        $placeholders = implode(',', array_fill(0, count($book_ids), '?'));
        $likePattern = "%book%";

        $query = "SELECT pv.user_city AS city, COUNT(*) AS views
          FROM page_visits AS pv
          INNER JOIN posts AS p ON pv.page_url LIKE CONCAT('%', p.CONTENTID, '%')
          WHERE pv.page_url LIKE ? AND p.CONTENTID IN ($placeholders)
          AND pv.user_city IS NOT NULL AND pv.user_city != 'Unknown'";

        $params = [$likePattern];
        $types = "s";

        if ($country) {
            $query .= " AND pv.user_country = ?";
            $params[] = $country;
            $types .= "s";
        }

        if ($province) {
            $query .= " AND pv.user_province = ?";
            $params[] = $province;
            $types .= "s";
        }

        $query .= " GROUP BY pv.user_city ORDER BY views DESC";

        $params = array_merge($params, $book_ids);
        $types .= str_repeat("s", count($book_ids));

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Helper method to get book IDs for a user
     * @param string $user_id
     * @return array Array of book content IDs
     */
    private function getUserBookIds($user_id)
    {
        $query = "SELECT CONTENTID FROM posts WHERE USERID = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return [];

        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book_ids = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return array_column($book_ids, 'CONTENTID');
    }
}
