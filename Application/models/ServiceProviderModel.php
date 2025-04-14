<?php
class ServiceProviderModel {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function getServiceProviders($service = null) {
        if ($service) {
            $query = "SELECT u.ADMIN_NAME, u.ADMIN_GOOGLE, u.ADMIN_PROFILE_IMAGE, s.SERVICE, u.ADMIN_PROVINCE
                      FROM users u
                      JOIN services s ON u.ADMIN_USERKEY = s.USERID
                      WHERE s.SERVICE = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $service);
        } else {
            $query = "SELECT u.ADMIN_NAME, u.ADMIN_GOOGLE, u.ADMIN_PROFILE_IMAGE, s.SERVICE, u.ADMIN_PROVINCE
                      FROM users u
                      JOIN services s ON u.ADMIN_USERKEY = s.USERID";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $providers = [];
        while ($row = $result->fetch_assoc()) {
            $providers[] = $row;
        }

        return $providers;
    }
}
?>
