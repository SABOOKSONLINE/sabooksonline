<?php

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/

    error_reporting(0);
    ini_set('display_errors', 0);

            
    session_start();

    include '../../database_connections/sabooks.php';
    include '../../database_connections/sabooks_plesk.php';

    $merchant_id = mysqli_real_escape_string($mysqli, $_POST['merchant_id']);
    $merchant_key = mysqli_real_escape_string($mysqli, $_POST['merchant_key']);
    $number = mysqli_real_escape_string($mysqli, $_POST['number']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $address = mysqli_real_escape_string($mysqli, $_POST['address']);
    $desc = mysqli_real_escape_string($mysqli, $_POST['desc']);
    $profile = mysqli_real_escape_string($mysqli, $_POST['profile']);
    $color = 'color';
    $desc = mysqli_real_escape_string($mysqli, $_POST['desc']);
    $books =  mysqli_real_escape_string($mysqli, $_POST['shipping']);
    $passphrase =  mysqli_real_escape_string($mysqli, $_POST['passphrase']);

    $date = $current_time = date('l jS \of F Y');

    if(!isset($_POST['merchant_id']) && !isset($_POST['merchant_key'])){
        $merchant_id = 'Service-locked';
        $merchant_key = 'Service-locked';
    }

    // Customer details
    $userkey = $_SESSION['ADMIN_USERKEY'];

    $sql = "UPDATE plesk_accounts SET SITE_MERCHANT_KEY = '$merchant_key', SITE_MERCHANT_ID = '$merchant_id', SITE_NUMBER = '$number',  SITE_EMAIL = '$email', SITE_ADDRESS = '$address', DESCRIPTION = '$desc', SITE_BOOKS = '$books', SITE_LONGITUDE = '$passphrase' WHERE USERKEY = '$userkey'";

                                        // Execute the query
    if ($mysqli->query($sql) === TRUE) {

        //mail($customerEmail, $subject, $logMessage, $headers);

        echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your website details have been updated!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('websites');},3000);</script>";

        } else {
            //echo "Error updating record: " . $conn->error;

            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Could not update your site! Contact support.',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('websites');},3000);</script>";
        }

                        // Close the statement and the connection
        $mysqli->close();

    
?>