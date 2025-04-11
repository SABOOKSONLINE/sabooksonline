<?php 

// Check if the session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is authenticated
if (!isset($_SESSION['ADMIN_USERKEY'])) {
    // Handle unauthenticated user
    // You might want to redirect or display an error message
    exit("You are not authorized to access this page.");
}

// Include the Plesk configuration
include_once 'sabooks_plesk.php';

// Get the user key from the session
$userkey = $_SESSION['ADMIN_USERKEY'];

// Prepare the SQL statement to retrieve Plesk account data
$sqp = "SELECT * FROM plesk_accounts WHERE USERKEY = ?";

// Prepare and bind the parameter for the query
$stmt = $mysqli->prepare($sqp);
$stmt->bind_param("s", $userkey);

// Initialize a variable to track website data availability
$websitedata = false;

// Execute the query and fetch the result
if ($stmt->execute()) {
    $result_sqp = $stmt->get_result();
    
    // Check if any rows were returned
    if(mysqli_num_rows($result_sqp) > 0){
        $row = mysqli_fetch_assoc($result_sqp);
        $customerPassword = $row['PASSWORD'];

        // Extract necessary data from the row
        $userid = $_SESSION['ADMIN_ID'];
        $customerUsername = strtolower(str_replace(' ','_',$_SESSION['ADMIN_NAME'])).'_'.$userid;

        // Create the database connection
        $servername_user = "localhost";
        $username_user = $customerUsername;
        $password_user = $customerPassword;
        $dbh_user = $customerUsername;

        // Create connection
        $con = new mysqli($servername_user, $username_user, $password_user, $dbh_user);

        // Check the connection
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        // Indicate that website data is available
        $websitedata = true;
    }
}

// Close the prepared statement
$stmt->close();

?>
