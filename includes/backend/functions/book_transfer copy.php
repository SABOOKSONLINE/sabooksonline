
    
    <?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $servername_user = "localhost";
    $username_user = $customerUsername;
    $password_user = $customerPassword;
    $dbh_user = $customerUsername;

    // Create connection
    $userconn = new mysqli($servername_user, $username_user, $password_user, $dbh_user);

    // Source API endpoint (Website A)
    $userid = $_SESSION['ADMIN_USERKEY'];
    
    // Check the connection
    if ($userconn->connect_error) {
        die("Connection failed: " . $userconn->connect_error);
    }
    
    // Step 1: Retrieve data from the source table
    $sourceTable = "posts";
    $sqlSelect = "SELECT ID, CATEGORY, TYPE, TITLE, RETAILPRICE, DESCRIPTION, COVER, ISBN FROM $sourceTable WHERE USERID = '$userid'";
    $result = $conn->query($sqlSelect);
    
    // Step 2: Insert or update the data in the destination table
    $destinationTable = "products";
    if ($result->num_rows > 0) {
        // Loop through each row of the result
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['ID'];
            
            // Build the INSERT query for the destination table
            $columnNames = implode(", ", array_keys($row));
            $columnValues = "'" . implode("', '", $row) . "'";
            $sqlInsertOrUpdate = "INSERT INTO $destinationTable (product_id, product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords) VALUES ($columnValues) ON DUPLICATE KEY UPDATE product_cat = VALUES(product_cat), product_brand = VALUES(product_brand), product_title = VALUES(product_title), product_price = VALUES(product_price), product_desc = VALUES(product_desc), product_image = VALUES(product_image), product_keywords = VALUES(product_keywords)";
    
            // Execute the INSERT or UPDATE query
            if ($userconn->query($sqlInsertOrUpdate) === false) {
                echo "Error inserting/updating data: " . $userconn->error;
            }
        }
        //echo "Data transferred successfully!";
    } else {
       // echo "No data found in the source table.";
    }
    
    // Close the connection
    $userconn->close();
?>
