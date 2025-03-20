<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DATABASE CONNECTIONS SCRIPT
include '../database_connections/sabooks.php';

if (!isset($_SESSION['ADMIN_USERKEY'])) {
    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You are not logged in!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('events');},3000);</script>";
    exit;
}

$userkey = $_SESSION['ADMIN_USERKEY'];

if (isset($_POST['contentid'])) {
    $contentid = mysqli_real_escape_string($conn, $_POST['contentid']);

    $sql = "DELETE FROM services WHERE ID = '$contentid' AND USERID = '$userkey'";

    if (mysqli_query($conn, $sql)) {
        //unlink('../../cms-data/event-covers/' . $contentlocate);
        echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Service has been Deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('services');},3000);</script>";
    } else {
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Something went wrong! Please contact support.',showConfirmButton: false,timer: 6000});</script>";
    }
} else {
    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Not Authorized! Please contact support.',showConfirmButton: false,timer: 6000});</script>";
}

?>
