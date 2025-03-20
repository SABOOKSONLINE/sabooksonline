<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Destination API endpoint (Website B)
include 'db.php'; // Include your destination database connection

// Receive data from the request
$data = json_decode(file_get_contents("php://input"), true);

// Validate the data
if (!empty($data)) {
    foreach ($data as $row) {
        // Build the INSERT query for the destination table
        $columnNames = implode(", ", array_keys($row));
        $columnValues = "'" . implode("', '", $row) . "'";
        $sqlInsert = "INSERT INTO products (product_id, product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords) VALUES ($columnValues)";
        
        // Execute the INSERT query
        if ($con->query($sqlInsert) === false) {
            echo "Error inserting data: " . $con->error;
            exit;
        }
    }
    echo "success";
} else {
    echo "Invalid request.";

    try {
        // Your HTTP request code here
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}
?>
