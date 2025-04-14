<?php
// Error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection
include 'includes/database_connections/sabooks.php';

// Get search term, price range, and other filters
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$priceRange = isset($_GET['priceRange']) ? $_GET['priceRange'] : '0-100'; // Default price range is 0-100

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : ''; // Correctly escape the search term

// Split the price range into minimum and maximum values
list($minPrice, $maxPrice) = explode('-', $priceRange);

// Handle selected service filters
if (isset($_GET['service']) && is_array($_GET['service']) && !empty($_GET['service'])) {
    $selectedServices = $_GET['service'];
    $selectedServices = array_map(function ($service) use ($conn) {
        return mysqli_real_escape_string($conn, str_replace(['-', 'and', '_'], [' ', '&', ','], $service));
    }, $selectedServices);
    $serviceWhereClause = "u.CATEGORY IN ('" . implode("', '", $selectedServices) . "')";
} else {
    $serviceWhereClause = "1=1"; // All rows
}

// Handle selected province filters
if (isset($_GET['type']) && is_array($_GET['type']) && !empty($_GET['type'])) {
    $selectedProvinces = $_GET['type'];
    $selectedProvinces = array_map(function ($province) use ($conn) {
        return mysqli_real_escape_string($conn, $province);
    }, $selectedProvinces);
    $provinceWhereClause = "u.TYPE IN ('" . implode(separator: "', '", $selectedProvinces) . "')";
} else {
    $provinceWhereClause = "1=1"; // All provinces
}

// Handle selected language filters
if (isset($_GET['language']) && is_array($_GET['language']) && !empty($_GET['language'])) {
    $selectedLanguages = $_GET['language'];
    $selectedLanguages = array_map(function ($language) use ($conn) {
        return mysqli_real_escape_string($conn, $language);
    }, $selectedLanguages);
    $languageWhereClause = "u.LANGUAGES IN ('" . implode("', '", $selectedLanguages) . "')";
} else {
    $languageWhereClause = "1=1"; // All languages
}

// Construct the WHERE clause for the search term
$searchWhereClause = "u.TITLE LIKE '%" . mysqli_real_escape_string($conn, $searchTerm) . "%' OR u.PUBLISHER LIKE '%" . mysqli_real_escape_string($conn, $searchTerm) . "%'";

// Construct the WHERE clause for the price range
$priceWhereClause = "u.RETAILPRICE >= $minPrice AND u.RETAILPRICE <= $maxPrice";

// Combine all WHERE clauses with an AND condition
$whereClause = "$serviceWhereClause AND $languageWhereClause AND $provinceWhereClause AND ($searchWhereClause) AND ($priceWhereClause)";

// Prepare and execute the SELECT query with JOIN and combined filter
$query = "SELECT u.TITLE, u.CATEGORY, u.CONTENTID, u.COVER, u.USERID, u.PUBLISHER, u.RETAILPRICE
        FROM posts u
        WHERE $whereClause AND STATUS = 'active' ORDER BY RAND()";

$result = $conn->query($query);

if ($result) {
    // Create an associative array to hold user details and services
    $userDetails = array();

    // Loop through the result set
    while ($row = $result->fetch_assoc()) {
        // Access columns using $row['column_name']
        $username = $row['TITLE'];
        $category = $row['CATEGORY'];
        $id = $row['CONTENTID'];
        $cover = $row['COVER'];
        $creator = $row['USERID'];
        $publisher = $row['PUBLISHER'];

        // Accumulate user details including services
        if (!isset($userDetails[$username])) {
            $userDetails[$username] = array(
                'username' => $username,
                'id' => $id,
                'cover' => $cover,
                'creator' => $creator,
                'publisher' => $publisher,
                'category' => $category,
                'services' => array() // Initialize services as an array
            );
        }

        // Add services to the existing array
        $userDetails[$username]['services'][] = $category;
    }

    // Free the result set
    $result->free();

    // Display user details and services
    foreach ($userDetails as $username => $details) {
        echo '<div class="book-div col-lg-3 col-sm-4" id="booklisting">
        <a href="book?q=' . strtolower($details['id']) . '" class="text-dark head-t">
                <span class="ribbon off">' . $details['category'] . '</span>
                <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $details['cover'] . '" data-src="https://my.sabooksonline.co.za/cms-data/book-covers/' . $details['cover'] . '" class="img-fluid lazy" alt="" width="100px">
            </a>
            <hr>

            <a href="creator?q=' . strtolower($details['creator']) . '" class="text-dark head-t" >' . ucwords($details['publisher']) . ' <small class="icon_check_alt text-success" style="font-size:12px"></small></a>
            <p class="mt-1 head-p"><a href="book?q=' . strtolower($details['id']) . '"><i class="icon_link"></i> ' . substr($details['username'], 0, 50) . '</a></p>
            
        </div>';
    }
} else {
    echo "Query execution failed: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
