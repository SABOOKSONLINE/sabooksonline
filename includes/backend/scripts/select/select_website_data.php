<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include '../../database_connections/sabooks.php';

// SQL to select data
$sql = "SELECT * FROM plesk_accounts WHERE USERKEY = '$userkey'";

$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $subscription_status = true;
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $sub_id = $row["DOMAIN"];
        $database_id = $row["DATABASE_ID"];
        $customer_id = $row["PLESK_ID"];
        $customer_password = $row["PASSWORD"];
        $customer_username = $row["USERNAME"];
        
        if(strpos($sub_id, 'sabooksonline.co.za') !== false){
            $registerDomain = true;
        } else {
            $registerDomain = false;
        }
    }
} else {
    $subscription_status = false;
}

?>
