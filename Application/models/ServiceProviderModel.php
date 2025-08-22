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
     * @param string|null $service Optional filter by service type
     * @return array An array of associative arrays, each containing provider and service info
     */
    public function getServiceProviders($service)
    {
        $query = "SELECT * 
                    FROM users  
                    JOIN services 
                    ON users.ADMIN_USERKEY = services.USERID
                    WHERE users.ADMIN_TYPE = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $service);
        $stmt->execute();
        $result = $stmt->get_result();

        $providers = [];
        $providers = [];

        while ($row = $result->fetch_assoc()) {
            $id = $row['ADMIN_ID'];

            if (isset($providers[$id])) {
                $current = array_filter(explode(',', $providers[$id]['ADMIN_SERVICES'] ?? ''));
                $new = array_filter(explode(',', $row['ADMIN_SERVICES'] ?? ''));

                $merged = array_unique(array_merge($current, $new));

                $merged = array_filter(array_map('trim', $merged));

                $providers[$id]['SERVICE'] = !empty($merged)
                    ? implode(' | ', $merged)
                    : '';
            } else {
                $services = array_filter(explode(',', $row['ADMIN_SERVICES'] ?? ''));
                $services = array_filter(array_map('trim', $services));
                $row['SERVICE'] = !empty($services)
                    ? implode(' | ', array_unique($services))
                    : '';
                $providers[$id] = $row;
            }
        }

        return $providers;
    }
}
