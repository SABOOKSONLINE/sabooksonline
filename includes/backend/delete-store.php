
<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//DATABASE CONNECTIONS SCRIPT
include '../database_connections/sabooks.php';

if(!isset($_SESSION['ADMIN_USERKEY'])){
    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You are not logged in!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('listings');},3000);</script>";
    
} else {

    $userkey = $_SESSION['ADMIN_USERKEY'];

   // $id = mysqli_real_escape_string($conn, $_POST['id']);
    $contentid = mysqli_real_escape_string($conn, $_POST['contentid']);
    //$contentlocate = mysqli_real_escape_string($conn, $_POST['locate']);

    $sql = "DELETE FROM book_stores WHERE ID ='$contentid' AND USERID = '$userkey';";

    if(mysqli_query($conn, $sql)){

        //unlink('../../cms-data/book-covers/'.$contentlocate); 

        $sql = "DELETE FROM book_stores WHERE ID ='$contentid' AND USERID = '$userkey';";        

            echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Book-Store has been Deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('bookstore');},3000);</script>";
       
    }

}


?>
