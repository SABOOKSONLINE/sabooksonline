<?php
// models/ProviderModel.php

/**
 * Class ProviderModel
 * 
 * Handles data access related to individual verified service providers.
 */
class ProviderModel {
    /**
     * @var mysqli $conn Database connection
     */
    private $conn;

    /**
     * ProviderModel constructor.
     *
     * @param mysqli $connection A MySQLi database connection instance
     */
    public function __construct($connection) {
        $this->conn = $connection;
    }

    /**
     * Fetch a verified service provider by their unique ID.
     *
     * @param string $providerId The unique ID of the provider (ADMIN_ID)
     * @return array|null Returns provider data as an associative array, or null if not found
     */
    public function getProviderById($providerId) {
        // Prepare SQL query to fetch only verified users by ID
        $sql = "SELECT * FROM users WHERE USER_STATUS = 'Verified' AND ADMIN_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $providerId);
        $stmt->execute();

        // Get the result set from the executed statement
        $result = $stmt->get_result();

        // Return null if no matching provider was found
        if ($result->num_rows === 0) {
            return null;
        }

        // Fetch the single provider record
        $row = $result->fetch_assoc();

        // Structure and return provider data in a clean format
        return [
            'name' => ucfirst($row['ADMIN_NAME']),
            'id' => $row['ADMIN_ID'],
            'google_map_address' => $row['ADMIN_GOOGLE'],
            'type' => ucfirst(str_replace('-', ' ', $row['ADMIN_TYPE'])),
            'bio' => $row['ADMIN_BIO'],
            'email' => $row['ADMIN_EMAIL'],
            'number' => $row['ADMIN_NUMBER'],
            'cover_image' => $row['ADMIN_PROFILE_IMAGE'],
            'userkey' => $row['ADMIN_USERKEY'],
            'services' => explode(',', $row['ADMIN_SERVICES']) // Convert comma-separated string to array
        ];
    }
}
