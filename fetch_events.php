<?php 

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Include your database connection
include 'includes/database_connections/sabooks.php';

if (isset($_GET['service']) && is_array($_GET['service']) && !empty($_GET['service'])) {
    // Get an array of selected service keywords from checkboxes
    $selectedServices = $_GET['service'];

    // Escape and sanitize each service keyword
    $escapedServices = array_map(function ($service) use ($conn) {
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
    $escapedProvinces = array_map(function ($province) use ($conn) {
        return mysqli_real_escape_string($conn, $province);
    }, $selectedProvinces);

    // Construct the WHERE clause for selected provinces
    $provinceWhereClause = "u.PROVINCE IN ('" . implode("', '", $escapedProvinces) . "')";
} else {
    // If no specific province is requested, retrieve all provinces
    $provinceWhereClause = "1=1"; // All provinces
}

// Combine the service and province WHERE clauses with an AND condition
$whereClause = "$serviceWhereClause AND $provinceWhereClause";

// Prepare and execute the SELECT query with JOIN and combined filter
$query = "SELECT u.ID, u.TITLE, u.EVENTDATE, u.EVENTTIME, u.VENUE, u.COVER, u.CONTENTID
        FROM events u
        WHERE $whereClause AND DURATION > 0
        ORDER BY RAND()";

$result = $conn->query($query);

if ($result) {
    // Create an associative array to hold user details and services
    $userDetails = array();

    // Loop through the result set
    while ($row = $result->fetch_assoc()) {
        // Access columns using $row['column_name']
        $username = $row['TITLE'];
        $address = $row['VENUE'];
        $time = $row['EVENTTIME'];
        $date = $row['EVENTDATE'];
        $id = $row['CONTENTID'];
        $logo = $row['COVER'];

        // Accumulate user details including services
        if (!isset($userDetails[$username])) {
            $userDetails[$username] = array(
                'address' => $address,
                'id' => $id, // Update the id for each event
                'logo' => $logo,
                'time' => $time,
                'date' => $date
                // 'services' => array($service)
            );
        } else {
            $userDetails[$username]['services'][] = $service;
        }
    }

    // Free the result set
    $result->free();

    // Display user details and services
    foreach ($userDetails as $username => $details) {
        echo '
    
    <div class="card info-div col-lg-5 col-md-12 col-sm-12 m-2">
    <div class="image" id="image" style="width: 100% !important;height: 200px !important;"><img src="https://sabooksonline.co.za/cms-data/event-covers/' . $details['logo'] . '">
    </div>
    <div class="infos pt-3">
       
        <div class="info">
            <div>
            <h5 class="name text-dark p-0 m-0">' . $username . '</h5>

               <small class="location"><i class="icon_pin_alt"></i> ' . $details['address'] . '</small>

            </div>

            <div class="d-flex justify-content-start flex-wrap">
               <small class="location"><i class="icon_clock_alt"></i> ' . $details['time'] . '</small>
               <small class="location" style="margin-left: 4%;"><i class="icon_calendar"></i> ' . $details['date'] . '</small>
            </div>

        </div>

        
    </div>

    <a href="event-details?event=' . $details['id'] . '"><button class="request" type="button" id="" style="background-color: #e54750;color: #fff;">
           Event Details<i class="fa fa-arrow-right"></i>
        </button></a>
</div>

<style>
   #map img[src="https://sabooksonline.co.za/cms-data/profile-images/' . $details['logo'] . '"]{
       border: 2px solid rgba(229, 71, 80, .9) !important;
       width: 40px !important;
       height: 40px !important;
       border-radius: 50% !important;
       background: #fff !important;
   }
</style>';
    }
} else {
    echo "Query execution failed: " . $conn->error;
}


//echo "Query: $query"; // Add this line before $result = $conn->query($query);


// Close the database connection
$conn->close();
?>