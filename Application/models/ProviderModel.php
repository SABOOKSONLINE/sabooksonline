<?php
// models/ProviderModel.php

class ProviderModel {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    /**
     * Fetch a verified service provider by ID
     */
    public function getProviderById($providerId) {
        $sql = "SELECT * FROM users WHERE USER_STATUS = 'Verified' AND ADMIN_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $providerId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $row = $result->fetch_assoc();

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
            'services' => explode(',', $row['ADMIN_SERVICES'])
        ];
    }
}
