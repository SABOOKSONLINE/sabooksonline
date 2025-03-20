<?php
// Database credentials
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include '../../database_connections/sabooks.php';
include '../../database_connections/sabooks_plesk.php';


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userkey = $_SESSION['ADMIN_USERKEY'];
        $subscriptionType = $_GET['subscription_type'];
    
        // Insert the subscription into the database
        $insertQuery = "UPDATE users SET ADMIN_SUBSCRIPTION = ? WHERE ADMIN_USERKEY = ?";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("si", $userkey, $subscriptionType);

        if ($stmt->execute()) {

             // Update the database as per use
            include 'functions/downgrade_website.php';

            echo "<script>Swal.fire({position: 'top-end',icon: 'success',title: 'Your Subscription has been updated!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('service-plan');},3000);</script>";
        } else {
            echo "<script>Swal.fire({icon: 'error',title: 'Oops...',text: 'Could not insert into database! Please report this to support@sabooksonline.co.za'});</script>";
        }
        
        $stmt->close();
    
} else {

    

    echo "<script>Swal.fire({icon: 'error',title: 'Oops...',text: 'Could not insert into database! Please report this to support@sabooksonline.co.za'});</script>";
    exit;
}

// Close the database connection
$conn->close();
?>
