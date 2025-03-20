<?php
// Database credentials
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();

include '../../database_connections/sabooks.php';
include '../../database_connections/sabooks_plesk.php';


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if($_SESSION['payment'] == 'false'){

    header("Location: https://sabooksonline.co.za/dashboard/service-plan?payment_invalid_type");
    
} else if ($_SESSION['payment'] == 'true'){

    $_SESSION['payment'] = 'false';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Ensure the user is logged in and the ADMIN_USERKEY is set in the session
        if (!isset($_SESSION['ADMIN_USERKEY'])) {
            echo "User is not logged in.";
            exit;
        }
    
        $userkey = $_SESSION['ADMIN_USERKEY'];
        $subscriptionType = $_GET['subscription_type'];
    
        $plan = $_SESSION['ADMIN_SUBSCRIPTION'];
    
        // Check the current service plan
        include 'select/select_subscriptions.php';
        // ...
    
        $downgrade = true; // Default to true for downgrading
    
        // Subscription downgrade conditions (you can optimize this with arrays)
        if ($plan == 'Free') {
            if ($subscriptionType == 'Standard' || $subscriptionType == 'Premium' || $subscriptionType == 'Deluxe') {
                $downgrade = false; // Set to true for downgrading
            }
        } elseif ($plan == 'Standard') {
            if ($subscriptionType == 'Premium' || $subscriptionType == 'Deluxe') {
                $downgrade = false; // Set to true for downgrading
            }
        } elseif ($plan == 'Premium') {
            if ($subscriptionType == 'Deluxe') {
                $downgrade = false; // Set to true for downgrading
            }
        }
    
    
        if ($subscription_status) { // Assuming $subscription_status is set properly
            $subscription_update = "UPDATE users SET ADMIN_SUBSCRIPTION = ? WHERE ADMIN_USERKEY = ?";
            $stmt = $conn->prepare($subscription_update);
            $stmt->bind_param("ss", $subscriptionType, $userkey);
    
            if ($stmt->execute()) {
                $user_books = 1;
                if ($subscriptionType == 'Standard') {
                    $user_books = 10;
                    // Do removal of items where applicable
                    include 'functions/downgrade_subscription.php';
                    // Update the database as per use
                    include 'functions/downgrade_website.php';
                    
                    // ...
                } elseif ($subscriptionType == 'Premium') {
                    $user_books = 50;
                    // Do removal of items where applicable
                    include 'functions/downgrade_subscription.php';
                    // Update the database as per use
                   include 'functions/downgrade_website.php';
                    // ...
                } elseif ($subscriptionType == 'Deluxe') {
                    $user_books = 1000000;
                    // Do removal of items where applicable
                    include 'functions/downgrade_subscription.php';
                    // Update the database as per use
                    include 'functions/downgrade_website.php';
                    // ...
                } elseif ($subscriptionType == 'Free') {
                    $user_books = 1;
                    // Do removal of items where applicable
                    include 'functions/downgrade_subscription.php';
                    // Update the database as per use
                    include 'functions/downgrade_website.php';
                    // ...
                } elseif ($subscriptionType == '')  {
                    // Do removal of items where applicable
                    include 'functions/downgrade_subscription.php';
                    include 'functions/downgrade_website.php';
                    // ...
                }
            }
    
            $stmt->close();
        } else {
            echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'Subscription Plan not found! Please report this to support@sabooksonline.co.za'});</script>";

            echo '1';
        }
    } else {
        echo "<script>Swal.fire({icon: 'error', title: 'Oops...', text: 'Could not insert into database! Please report this to support@sabooksonline.co.za'});</script>";

        echo '1';
        exit;
    }
    
}


// Close the database connection
$conn->close();
?>
