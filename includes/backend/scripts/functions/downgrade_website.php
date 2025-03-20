<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

error_reporting(0);
ini_set('display_errors', 0);


include_once '/var/www/vhosts/sabooksonline.co.za/httpdocs/includes/database_connections/sabooks.php';//normally its hidden
include_once '/var/www/vhosts/sabooksonline.co.za/httpdocs/includes/database_connections/sabooks_plesk.php';//normally its hidden
include 'select/select_website_data.php';


// SQL query to update the table
$userKey = $_SESSION['ADMIN_USERKEY']; // Replace with the actual user key
$susbcriptionID = $_SESSION['ADMIN_CUSTOMER_PLESK']; // Replace with the actual user key

$plan = $_SESSION['ADMIN_SUBSCRIPTION'];


if($plan == 'Standard' || $plan == 'Premium' || $plan == 'Deluxe'){
    $sql = "UPDATE plesk_accounts SET STATUS ='Active' WHERE USERKEY = '$userKey'";
    

    // Execute the query
    if ($mysqli->query($sql) === TRUE) {

        if($registerDomain == true){

             // REMOVER SUB DOMAIN
             include 'website/remove_subdomain.php';
             include 'website/remove_ftp.php';

             

        } else if($registerDomain == false){

            // SUSPEND THE CUSTOMER
            include 'website/unsuspend_customer.php';

        } 

    } else {
        //echo "Error updating record: " . $mysqli->error;
        
    }

    // Close the connection
    $mysqli->close();

} else if($plan == 'Free'){
    $sql = "UPDATE plesk_accounts SET STATUS ='Service Locked' WHERE USERKEY = '$userKey'";

    // Execute the query
    if ($mysqli->query($sql) === TRUE) {

        echo 'Tranbs';

        // SUSPEND THE CUSTOMER
        include 'website/suspend_customer.php';

        $customer_suspended = true;

    } else {
        //echo "Error updating record: " . $mysqli->error;
    }

    // Close the connection
    $mysqli->close();

}

?>
