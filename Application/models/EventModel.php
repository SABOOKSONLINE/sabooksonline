<?php

/**
 * Class EventModel
 *
 * Manages event-related data operations, including retrieval and filtering.
 */
class EventModel {
    /**
     * @var mysqli Database connection
     */
    private $conn;

    /**
     * Constructor
     *
     * @param mysqli $conn MySQLi database connection
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Get a single active event by its content ID.
     *
     * @param string $contentId Unique content identifier
     * @return array|null Event details or null if not found
     */
    public function getEventByContentId(string $contentId): ?array {
        $contentId = mysqli_real_escape_string($this->conn, $contentId);
        $query = "SELECT * FROM events WHERE STATUS = 'Active' AND CONTENTID = '$contentId' LIMIT 1";
        $result = mysqli_query($this->conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    /**
     * Retrieve a list of filtered events based on selected services and provinces.
     *
     * @param array $selectedServices List of service filters
     * @param array $selectedProvinces List of province filters
     * @return mysqli_result|false Result set on success or false on failure
     */
    public function getFilteredEvents(array $selectedServices, array $selectedProvinces) {
        // Default filter clauses
        $serviceWhereClause = "1=1";
        $provinceWhereClause = "1=1";

        // Filter by service(s)
        if (!empty($selectedServices)) {
            $escapedServices = array_map(function ($service) {
                return mysqli_real_escape_string($this->conn, $service);
            }, $selectedServices);
            $serviceWhereClause = "s.SERVICE IN ('" . implode("','", $escapedServices) . "')";
        }

        // Filter by province(s)
        if (!empty($selectedProvinces)) {
            $escapedProvinces = array_map(function ($province) {
                return mysqli_real_escape_string($this->conn, $province);
            }, $selectedProvinces);
            $provinceWhereClause = "u.PROVINCE IN ('" . implode("','", $escapedProvinces) . "')";
        }

        // Build final WHERE clause
        $whereClause = "$serviceWhereClause AND $provinceWhereClause";

        // Fetch events matching filters and with positive duration
        $query = "SELECT u.ID, u.TITLE, u.EVENTDATE, u.EVENTTIME, u.VENUE, u.COVER, u.CONTENTID
                  FROM events u
                  WHERE $whereClause AND DURATION > 0
                  ORDER BY RAND()";

        return $this->conn->query($query);
    }
}
