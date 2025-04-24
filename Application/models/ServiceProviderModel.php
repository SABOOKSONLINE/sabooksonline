<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Class ServiceProviderModel
 * 
 * Handles database interactions related to service providers and their services.
 */
class ServiceProviderModel
{
    /**
     * @var mysqli $conn Database connection instance
     */
    private $conn;

    /**
     * Constructor
     *
     * @param mysqli $connection The MySQLi database connection
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Retrieves service providers along with their related service and location details.
     *
     * @param string|null $service Optional filter by service type (e.g. "Plumbing", "Tutoring")
     * @return array An array of associative arrays, each containing provider and service info
     */
    public function getServiceProviders($service)
    {
        $query = "SELECT * 
                    FROM users  
                    JOIN services ON users.ADMIN_USERKEY = services.USERID 
                    WHERE users.ADMIN_TYPE = ? 
                    AND users.USER_STATUS = 'Verified'";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $service);
        $stmt->execute();
        $result = $stmt->get_result();

        $providers = [];
        while ($row = $result->fetch_assoc()) {
            $providers[] = $row;
        }

        return $providers;
    }
}
