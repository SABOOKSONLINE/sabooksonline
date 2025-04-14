<?php
class EventModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Fetch filtered events based on services and provinces
    public function getFilteredEvents($selectedServices, $selectedProvinces) {
        // Default WHERE clause
        $serviceWhereClause = "1=1";
        $provinceWhereClause = "1=1";

        // Apply service filters
        if (is_array($selectedServices) && !empty($selectedServices)) {
            $escapedServices = array_map(function ($service) {
                return mysqli_real_escape_string($this->conn, $service);
            }, $selectedServices);
            $serviceWhereClause = "s.SERVICE IN ('" . implode("','", $escapedServices) . "')";
        }

        // Apply province filters
        if (is_array($selectedProvinces) && !empty($selectedProvinces)) {
            $escapedProvinces = array_map(function ($province) {
                return mysqli_real_escape_string($this->conn, $province);
            }, $selectedProvinces);
            $provinceWhereClause = "u.PROVINCE IN ('" . implode("','", $escapedProvinces) . "')";
        }

        // Combined WHERE clause
        $whereClause = "$serviceWhereClause AND $provinceWhereClause";

        // Query filtered events
        $query = "SELECT u.ID, u.TITLE, u.EVENTDATE, u.EVENTTIME, u.VENUE, u.COVER, u.CONTENTID
                  FROM events u
                  WHERE $whereClause AND DURATION > 0
                  ORDER BY RAND()";

        return $this->conn->query($query);
    }
}
