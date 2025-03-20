<?php

        session_start();

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if(!isset($_SESSION['ADMIN_USERKEY'])){
            echo "<script>Swal.fire({position: 'top-end',icon: 'failed',title: 'Your website with domain ".$domainName." has been created!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-websites');},3000);</script>";
        } else {

        //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $service_price_min = mysqli_real_escape_string($conn, $_POST['service_price_min']);
        $service_price_max = mysqli_real_escape_string($conn, $_POST['service_price_max']);
        $service_type = mysqli_real_escape_string($conn, $_POST['service_type']);

        $userkey = $_SESSION['ADMIN_USERKEY'];
        $reg_email = $_SESSION['ADMIN_EMAIL'];
        $reg_name = $_SESSION['ADMIN_NAME'];
        $userid = $_SESSION['ADMIN_ID'];

        $contentid = time().uniqid();
        $contentid = str_shuffle($contentid);
        $contentid = substr($contentid, '4', '6');

        $combined = $userkey.time();
        $time = substr(uniqid(),'0', '9');

        //TIME VARIABLE
        $current_time = date('l jS \of F Y');

        //VERIFICATION LINK FOR USER

        $veri_link = "https://sabooksonline.co.za/verify?verifyid=$combined";

        if(empty($service_price_min) && empty($service_price_max)){

          echo "<script>Swal.fire({position: 'center',icon: 'fail',title: 'Please fill in the price range.',showConfirmButton: false,timer: 6000});</script>";
                        
        } else {

               include 'scripts/functions/service_permissions.php';

              }
        }
          
?>