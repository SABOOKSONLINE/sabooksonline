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

// Service ID to delete (Assuming you have a form field with the service ID)
$service_id_to_delete = $_GET['contentid'];

// Fetch user's subscription plan and allowed service count
$sql_subscription = "SELECT SERVICES FROM subscriptions WHERE PLAN = ?";
$stmt_subscription = $conn->prepare($sql_subscription);
$stmt_subscription->bind_param("s", $user_subscription);
$stmt_subscription->execute();
$result_subscription = $stmt_subscription->get_result();

if ($result_subscription->num_rows > 0) {
    $row_subscription = $result_subscription->fetch_assoc();
    $services_allowed = $row_subscription["SERVICES"];

    // Check if the user has the requested service
    $sql_check_service = "SELECT * FROM services WHERE ID = ? AND USERID = ?";
    $stmt_check_service = $conn->prepare($sql_check_service);
    $stmt_check_service->bind_param("ss", $service_id_to_delete, $user_id);
    $stmt_check_service->execute();
    $result_check_service = $stmt_check_service->get_result();

    if ($result_check_service->num_rows > 0) {
        // Delete the service
        $sql_delete_service = "DELETE FROM services WHERE ID = ?";
        $stmt_delete_service = $conn->prepare($sql_delete_service);
        $stmt_delete_service->bind_param("s", $service_id_to_delete);
        if ($stmt_delete_service->execute()) {
            echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Service deleted successfully!',showConfirmButton: false,timer: 6000});</script>";
        } else {
            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Failed to delete service',showConfirmButton: false,timer: 6000});</script>";
        }
    } else {
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Service not found for this user',showConfirmButton: false,timer: 6000});</script>";
    }

    $stmt_check_service->close();
} else {
    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Subscription plan not found',showConfirmButton: false,timer: 6000});</script>";
}

$stmt_subscription->close();

?>
