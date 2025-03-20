<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$userkey = $_SESSION['ADMIN_USERKEY'];

include '../../../includes/database_connections/sabooks.php';

// SQL to select data
$sql = "SELECT * FROM subscriptions_p WHERE subscription_id = '$userkey'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {


        $start_date = $row["start_date"];
        $next_invoice_date = $row["next_invoice_date"];
        $status = $row["status"];
        $frequency = $row["frequency"];
        $amount = $row["amount"];
        $plan = $row["plan"];
        $subscription_id = $row["subscription_id"];  


    }
} else {
    echo "0 results";
}

?>
