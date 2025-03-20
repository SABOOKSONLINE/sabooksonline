<?php

session_start();

$type = $_SESSION['upgrade'];

$servername = "localhost";
$username = "sabooks_library";
$password = "1m0g7mR3$";
$dbh = "Sibusisomanqa_update_3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}


$invoice_id = $_SESSION['ADMIN_USERKEY']; // Replace with the actual invoice ID
$today = date("Y-m-d");

$sql = "SELECT * FROM payments WHERE invoice_id = ? AND payment_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $invoice_id, $today);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['payment'] = 'true';
    // You can perform additional actions here if needed
    header("Location: ../backend/scripts/subscription-activate.php?subscription_type=".$type);

} else {
    $_SESSION['payment'] = 'false';
    header("Location: https://sabooksonline.co.za/dashboard/service-plan?payment_invalid");
}

$conn->close();

?>
