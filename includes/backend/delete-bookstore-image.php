
<?php
session_start();

//DATABASE CONNECTIONS SCRIPT
include '../database_connections/sabooks.php';

if(!isset($_SESSION['ADMIN_USERKEY'])){
    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You are not logged in!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-edit-bookstore-images?contentid=".$bookstoreid."');},3000);</script>";
    
} else {

    $userkey = $_SESSION['ADMIN_USERKEY']; 

    $contentid = mysqli_real_escape_string($conn, $_POST['contentid']);
    $bookstoreid = mysqli_real_escape_string($conn, $_POST['bookstoreid']);

    $sql = "DELETE FROM bookstores_images WHERE ID ='$contentid';";

    if(mysqli_query($conn, $sql)){

        echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Image has been Deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('edit-bookstore-images?contentid=".$bookstoreid."');},3000);</script>";
    }

} 


?>
