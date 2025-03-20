
<?php
session_start();

//DATABASE CONNECTIONS SCRIPT
include '../database_connections/sabooks.php';

if(!isset($_SESSION['ADMIN_USERKEY'])){
    header("Location: ../../dashboard-2?status=failed");
    
} else {

    $userkey = $_SESSION['ADMIN_USERKEY'];

    $contentid = mysqli_real_escape_string($conn, $_GET['contentid']);
    $contentlocate = mysqli_real_escape_string($conn, $_GET['locate']);

    $sql = "DELETE FROM provider_images WHERE PROVIDER_IMAGE ='$contentlocate' AND PROVIDER_KEY = '$userkey';";

    if(mysqli_query($conn, $sql)){
        unlink('../../cms-data/user-images/'.$contentlocate); 

        header("Location: ../../dashboard-2?status=success");
    }

}


?>
