<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Assuming you have a database connection established
$user_id = $_SESSION['ADMIN_USERKEY']; // Replace with the actual user ID
$user_subscription = $_SESSION['ADMIN_SUBSCRIPTION']; // Replace with the actual user subscription
$retailprice = 'Active';

// Fetch user's subscription plan and allowed book count
$sql_subscription = "SELECT SERVICES FROM subscriptions WHERE PLAN = ?";
$stmt_subscription = $conn->prepare($sql_subscription);
$stmt_subscription->bind_param("s", $user_subscription);
$stmt_subscription->execute();
$result_subscription = $stmt_subscription->get_result();

if ($result_subscription->num_rows > 0) {
    $row_subscription = $result_subscription->fetch_assoc();
    $books_allowed = $row_subscription["SERVICES"];

    // Count the amount of services the user has
    $sql_count_services = "SELECT * FROM services WHERE USERID = ?";
    $stmt_count_services = $conn->prepare($sql_count_services);
    $stmt_count_services->bind_param("s", $user_id);
    $stmt_count_services->execute();
    $result_count_services = $stmt_count_services->get_result();
    
    $current_services_count = $result_count_services->num_rows;

    if ($current_services_count < $books_allowed) {
        $remainingServices = $books_allowed - $current_services_count;

        // Check if the user already has the requested service
        $sql_check_service = "SELECT * FROM services WHERE USERID = ? AND SERVICE = ?";
        $stmt_check_service = $conn->prepare($sql_check_service);
        $stmt_check_service->bind_param("ss", $user_id, $service_type);
        $stmt_check_service->execute();
        $result_check_service = $stmt_check_service->get_result();

        if ($result_check_service->num_rows > 0) {
            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You already have this service under your profile.',showConfirmButton: false,timer: 3000});";
        } else {
            // Insert the new service into the database
            $sql = "INSERT INTO services (SERVICE, USERID, STATUS, CREATED, MODIFIED, MINIMUM, MAXIMUM) VALUES ('$service_type','$userkey', 'Active', '$current_time','$current_time','$service_price_min','$service_price_max');";

            if (mysqli_query($conn, $sql)) {
                echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Service with title <b>".$service_type."</b> has been uploaded!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('services');},3000);</script>";
            } else {
                echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your service could not be added.',showConfirmButton: false,timer: 3000});";
            }
        }

        $stmt_check_service->close();
    } else {
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You have reached your limit in listing services.',showConfirmButton: false,timer: 3000});";
    }
} else {
    echo "<script>Swal.fire({position: 'center',icon: 'danger',title: 'Subscription plan not found.',showConfirmButton: false,timer: 3000});";
}

$stmt_subscription->close();
$stmt_count_services->close();

?>
