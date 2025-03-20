<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection
include 'includes/database_connections/sabooks.php';

if (isset($_GET['service']) && is_array($_GET['service']) && !empty($_GET['service'])) {
    // Get an array of selected service keywords from checkboxes
    $selectedServices = $_GET['service'];

    // Escape and sanitize each service keyword
    $escapedServices = array_map(function($service) use ($conn) {
        return mysqli_real_escape_string($conn, $service);
    }, $selectedServices);

    // Construct the WHERE clause for selected services
    $serviceWhereClause = "s.SERVICE IN ('" . implode("', '", $escapedServices) . "')";
} else {
    // If no specific service is requested, retrieve all users and services
    $serviceWhereClause = "1=1"; // All rows
}

if (isset($_GET['province']) && is_array($_GET['province']) && !empty($_GET['province'])) {
    // Get an array of selected provinces from checkboxes
    $selectedProvinces = $_GET['province'];

    // Escape and sanitize each province
    $escapedProvinces = array_map(function($province) use ($conn) {
        return mysqli_real_escape_string($conn, $province);
    }, $selectedProvinces);

    // Construct the WHERE clause for selected provinces
    $provinceWhereClause = "u.ADMIN_PROVINCE IN ('" . implode("', '", $escapedProvinces) . "')";
} else {
    // If no specific province is requested, retrieve all provinces
    $provinceWhereClause = "1=1"; // All provinces
}

// Combine the service and province WHERE clauses with an AND condition
$whereClause = "$serviceWhereClause AND $provinceWhereClause";

// Prepare and execute the SELECT query with JOIN and combined filter
$query = "SELECT u.ADMIN_NAME, u.ADMIN_GOOGLE, u.ADMIN_ID, u.ADMIN_PROFILE_IMAGE, s.SERVICE
        FROM users u
        JOIN services s ON u.ADMIN_USERKEY = s.USERID
        WHERE $whereClause AND s.STATUS = 'Active' ORDER BY RAND()";

$result = $conn->query($query);

if ($result) {
    // Create an associative array to hold user details and services
    $userDetails = array();

    // Loop through the result set
    while ($row = $result->fetch_assoc()) {
        // Access columns using $row['column_name']
        $username = $row['ADMIN_NAME'];
        $address = $row['ADMIN_GOOGLE'];
        $id = $row['ADMIN_ID'];
        $logo = $row['ADMIN_PROFILE_IMAGE'];
        $service = $row['SERVICE'];

        // Accumulate user details including services
        if (!isset($userDetails[$username])) {
            $userDetails[$username] = array(
                'address' => $address,
                'id' => $id,
                'logo' => $logo,
                'services' => array($service) // Initialize services as an array
            );
        } else {
            $userDetails[$username]['services'][] = $service; // Add services to the existing array
        }
    }

    // Free the result set
    $result->free();

    // Display user details and services
    foreach ($userDetails as $username => $details) {
        echo '<div class="card info-div col-lg-4 col-md-12">
        <div class="infos">
            <div class="image" id="image"><img src="https://sabooksonline.co.za/cms-data/profile-images/'.$details['logo'].'"> 
            
            <!-- Additional HTML code for reviews could go here -->
            
            </div>
            <div class="info">
                <div>
                    <h5 class="name text-dark p-0 m-0">'.$username.'</h5>
                    <small class="location"><i class="fa fa-map-marker"></i> '.$details['address'].'</small>
                </div>

                <div class="d-flex justify-content-start flex-wrap">
                    <span class="badge badge-dark text-dark b-services mr-2 text-dark" style="margin: 1%;">'.implode(', ', $details['services']).'</span>
                </div>
            </div>
        </div>
        <a href="provider?provider='.str_replace(' ', '_', strtolower($details['id'])).'" class="request text-center" type="button" id="'.str_replace(' ', '_', strtolower($username)).'_event" style="background-color: #e54750;color: #fff;">
            More Details <i class="fa fa-arrow-right"></i>
        </a>  
    </div>';
    }
} else {
    echo "Query execution failed: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
