<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../database_connections/sabooks.php';

if (!isset($_SESSION['ADMIN_USERKEY'])) {
    session_start();
}

$userkey = $_SESSION['ADMIN_USERKEY'];


        // Update the value to 'Free'
        $updateSql = "UPDATE users SET ADMIN_SUBSCRIPTION = 'Free' WHERE ADMIN_SUBSCRIPTION = 'Starter'";
        if ($conn->query($updateSql) === TRUE) {
            echo "ADMIN_SUBSCRIPTION updated successfully.";
        } else {
            echo "Error updating ADMIN_SUBSCRIPTION: " . $conn->error;
        }


// Close the database connection
$conn->close();
?>
