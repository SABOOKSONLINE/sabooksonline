<?php

/**
 * Class ServiceProviderModel
 * 
 * Handles database interactions related to service providers and their services.
 */
class ServiceProviderModel {
    /**
     * @var mysqli $conn Database connection instance
     */
    private $conn;

    /**
     * Constructor
     *
     * @param mysqli $connection The MySQLi database connection
     */
    public function __construct($connection) {
        $this->conn = $connection;
    }

    /**
     * Retrieves service providers along with their related service and location details.
     *
     * @param string|null $service Optional filter by service type (e.g. "Plumbing", "Tutoring")
     * @return array An array of associative arrays, each containing provider and service info
     */
    public function getServiceProviders($service = null) {
        // If a specific service is requested, filter based on service
        if ($service) {
            $query = "SELECT u.ADMIN_NAME, u.ADMIN_GOOGLE, u.ADMIN_PROFILE_IMAGE, s.SERVICE, u.ADMIN_PROVINCE
                      FROM users u
                      JOIN services s ON u.ADMIN_USERKEY = s.USERID
                      WHERE s.SERVICE = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $service);
        } else {
            // Otherwise, fetch all service providers
            $query = "SELECT u.ADMIN_NAME, u.ADMIN_GOOGLE, u.ADMIN_PROFILE_IMAGE, s.SERVICE, u.ADMIN_PROVINCE
                      FROM users u
                      JOIN services s ON u.ADMIN_USERKEY = s.USERID";
            $stmt = $this->conn->prepare($query);
        }

        // Execute the query
        $stmt->execute();

        // Fetch the result set
        $result = $stmt->get_result();

        // Store all provider records in an array
        $providers = [];
        while ($row = $result->fetch_assoc()) {
            $providers[] = $row;
        }

        return $providers;
    }
}
?>
