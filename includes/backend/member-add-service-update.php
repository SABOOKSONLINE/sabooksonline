<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 //DATABASE CONNECTIONS SCRIPT
 include '../database_connections/sabooks.php';

// Assuming you have a database connection established
$user_id = $_SESSION['ADMIN_USERKEY']; // Replace with the actual user ID
$user_subscription = $_SESSION['ADMIN_SUBSCRIPTION']; // Replace with the actual user subscription

// Details of the service to update
$service_id = $_POST['service_id']; // Assuming you have a form field with the service ID
$new_service_type = $_POST['service_type']; // Assuming you have a form field for the new service type
$new_service_price_min = $_POST['service_price_min']; // Assuming you have a form field for the new price min
$new_service_price_max = $_POST['service_price_max']; // Assuming you have a form field for the new price max

// Fetch user's subscription plan and allowed service count
$sql_subscription = "SELECT SERVICES FROM subscriptions WHERE PLAN = ?";
$stmt_subscription = $conn->prepare($sql_subscription);
$stmt_subscription->bind_param("s", $user_subscription);
$stmt_subscription->execute();
$result_subscription = $stmt_subscription->get_result();

if ($result_subscription->num_rows > 0) {
    $row_subscription = $result_subscription->fetch_assoc();
    $services_allowed = $row_subscription["SERVICES"];

    // Check if the user already has the requested service
    $sql_check_service = "SELECT * FROM services WHERE ID = ? AND USERID = ?";
    $stmt_check_service = $conn->prepare($sql_check_service);
    $stmt_check_service->bind_param("ss", $service_id, $user_id);
    $stmt_check_service->execute();
    $result_check_service = $stmt_check_service->get_result();

    if ($result_check_service->num_rows > 0) {
        // Update the service details
        $sql_update_service = "UPDATE services SET SERVICE = ?, MINIMUM = ?, MAXIMUM = ?, MODIFIED = ? WHERE ID = ?";
        $stmt_update_service = $conn->prepare($sql_update_service);
        $stmt_update_service->bind_param("sssss", $new_service_type, $new_service_price_min, $new_service_price_max, $current_time, $service_id);
        if ($stmt_update_service->execute()) {
            echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Service updated successfully!',showConfirmButton: false,timer: 6000});</script><script>setInterval(function(){window.location.replace('page-dashboard-services');},2000);</script>";
        } else {
            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'warninged to update service.',showConfirmButton: false,timer: 6000});</script>";
        }
    } else {
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Service not found for this user.',showConfirmButton: false,timer: 6000});</script>";
    }

    $stmt_check_service->close();
} else {
    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Subscription plan not found',showConfirmButton: false,timer: 6000});</script>";
}

$stmt_subscription->close();

?>
