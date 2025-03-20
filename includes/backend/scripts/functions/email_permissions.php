<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../../database_connections/sabooks_plesk.php';
include '../../../database_connections/sabooks.php';

session_start();

// Assuming you have a database connection established
$user_id = $_SESSION['ADMIN_USERKEY']; // Replace with the actual user ID
$user_subscription = $_SESSION['ADMIN_SUBSCRIPTION']; // Replace with the actual user subscription
$retailprice = 'Active';

// Fetch user's subscription plan and allowed book count
$sql_subscription = "SELECT EMAILS FROM subscriptions WHERE PLAN = ?";
$stmt_subscription = $conn->prepare($sql_subscription);
$stmt_subscription->bind_param("s", $user_subscription);
$stmt_subscription->execute();
$result_subscription = $stmt_subscription->get_result();

if ($result_subscription->num_rows > 0) {
    $row_subscription = $result_subscription->fetch_assoc();
    $books_allowed = $row_subscription["EMAILS"];

    // Count the amount of books the user has uploaded
    $sql_count_books = "SELECT * FROM plesk_emails WHERE EMAIL_USERID = ?";
    $stmt_count_books = $mysqli->prepare($sql_count_books);
    $stmt_count_books->bind_param("s", $user_id);
    $stmt_count_books->execute();
    $result_count_books = $stmt_count_books->get_result();
    
    $uploaded_books = $result_count_books->num_rows;

    if ($uploaded_books < $books_allowed) {
        $remainingUploads = $books_allowed - $uploaded_books;

        include '../email.php';
          
       // echo "You are allowed to upload $remainingUploads more book(s).";
        
    } else {
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You have reached your email creation limit!',showConfirmButton: false,timer: 3000});";
    }
} else {
    echo "Subscription plan not found.";
}

$stmt_subscription->close();
$stmt_count_books->close();

?>