<?php

    ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

    session_start(); 

    $current_user = $_SESSION['user_id'];

    include_once '../db.php';
        
    $contentkey = $_GET['contentid'];

    $sql = "DELETE FROM product_order WHERE user_id = '$current_user' AND product_id = '$contentkey';";
        
    if(!mysqli_query($con, $sql)){
        echo 'Not removed';
    }else{
            echo '<p class="text-danger">Removed</p>';
    }


 ?>