
    
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
    
    $sql = "DELETE FROM products WHERE product_id ='$id';";

    mysqli_query($userconn, $sql);
    
    // Close the connection
    $userconn->close();
?>
