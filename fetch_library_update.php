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
    $provinceWhereClause = "u.TYPE IN ('" . implode("', '", $selectedProvinces) . "')";
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
        WHERE $whereClause AND STATUS = 'active' ORDER BY u.TITLE ASC";

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
        if (!isset($userDetails[$category])) {
            $userDetails[$category] = array(
                'category' => $category,
                'books' => array() // Initialize books as an array
            );
        } 

        // Add book details to the existing array
        $userDetails[$category]['books'][] = array(
            'id' => $id,
            'cover' => $cover,
            'creator' => $creator,
            'publisher' => $publisher,
            'title' => $username,
        );
    }

    // Free the result set
    $result->free();

    // Sort the user details array by category name
usort($userDetails, function($a, $b) {
    return strcmp($a['category'], $b['category']);
});

// Display user details and services in rows with scrollable overflow and navigation buttons
echo '<div class="scroll-container">';
foreach ($userDetails as $categoryDetails) {
    echo '<div class="category-row">';
    echo '<h2>' . $categoryDetails['category'] . '</h2>'; // Display category title

    // Navigation buttons for each category row
    echo '<button class="scroll-left">&lt;</button>'; // Left scroll button
    echo '<button class="scroll-right">&gt;</button>'; // Right scroll button

    // Display book details within the category row    
    echo '<div class="book-row">';
    foreach ($categoryDetails['books'] as $book) {
        echo '<div class="book-div col-lg-3" id="booklisting">';       
        echo '<a href="book?q=' . strtolower($book['id']) . '" class="text-dark head-t">';
        echo '<span class="ribbon off">' . $categoryDetails['category'] . '</span>';
        echo '<img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['cover'] . '" data-src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['cover'] . '" class="img- lazy" alt="">';
        echo '</a>';
        echo '<hr>';                         
        echo '<a href="creator?q=' . strtolower($book['creator']) . '" class="text-dark head-t" >' . ucwords($book['publisher']) . ' <small class="icon_check_alt text-success" style="font-size:12px"></small></a>';
        echo '<p class="mt-1 head-p"><a href="book?q=' . strtolower($book['id']) . '" class="m-book-title"> ' . substr($book['title'], 0, 50) . '</a></p>';
        echo '</div>';              
    }  
    echo '</div>'; // Close the book row                      

    echo '</div>'; // Close the category row    
}
echo '</div>'; // Close the scroll container
       

} else {  
    echo "Query execution failed: " . $conn->error;
}  

// Close the database connection
$conn->close();


echo '
<script>
$(document).ready(function() {  
    $(".scroll-right").click(function() {
        $(this).siblings(".book-row").animate({scrollLeft: "+=300px"}, "slow");
    });

    $(".scroll-left").click(function() {
        $(this).siblings(".book-row").animate({scrollLeft: "-=300px"}, "slow");
    });
});
</script>      
';
?>   