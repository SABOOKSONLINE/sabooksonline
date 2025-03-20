<?php

        session_start();
        //The registartion code begins

       /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/

        if(!isset($_SESSION['ADMIN_USERKEY'])){
          echo 'You are not logged in!';
        } else {

        //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $event_title = mysqli_real_escape_string($conn, $_POST['event_title']);
        $event_email = mysqli_real_escape_string($conn, $_POST['event_email']);
        $event_number = mysqli_real_escape_string($conn, $_POST['event_number']);
        $event_desc = mysqli_real_escape_string($conn, $_POST['event_desc']);
        $event_address = mysqli_real_escape_string($conn, $_POST['event_address']);
        $event_province = mysqli_real_escape_string($conn, $_POST['event_province']);
        $event_start_time = mysqli_real_escape_string($conn, $_POST['event_start_time']);
        $event_date_start = mysqli_real_escape_string($conn, $_POST['dates_start']);
        $event_date_end = mysqli_real_escape_string($conn, $_POST['dates_end']);
        $event_days = mysqli_real_escape_string($conn, $_POST['child']);
        $event_end_time = mysqli_real_escape_string($conn, $_POST['event_end_time']);
        $reg_service_other = mysqli_real_escape_string($conn, $_POST['reg_service_other']);

        $userkey = $_SESSION['ADMIN_USERKEY'];
        $reg_email = $_SESSION['ADMIN_EMAIL'];
        $reg_name = $_SESSION['ADMIN_NAME'];
        $userid = $_SESSION['ADMIN_ID'];

        $title = $event_title;

        // Initialize arrays for multi-select fields
        $reg_services = isset($_POST['services']) ? $_POST['services'] : array();
        $event_type = isset($_POST['event_type']) ? $_POST['event_type'] : array();

        // Convert arrays to comma-separated strings
        $reg_services_str = implode('|', $reg_services);
        $event_type_str = implode('|', $event_type);

        $contentid = time().uniqid();
        $contentid = str_shuffle($contentid);
        $contentid = substr($contentid, '4', '6');

        //$userkey = substr(uniqid(),'0', '6').time();


        $sourcePath = $_FILES['event_cover']['tmp_name'];

        $extension = pathinfo($_FILES["event_cover"]["name"], PATHINFO_EXTENSION);

        $targetPath = "../../cms-data/event-covers/".str_replace(' ','-', $event_title).'-'.$userkey.".".$extension;

        $targetPath1 = str_replace(' ','-', $event_title).'-'.$userkey.".".$extension;

        //IMAGE UPLOAD CODE END 

        if($event_days > 91){
            echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Maximum number of days to publish your event is <b>30 days</b>, you selected <b>$event_days</b> instead.</div>";
        } else {

            if($event_days < 1){
                echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Minimum number of days to publish your event is <b>7 days</b>, you selected <b>$event_days</b> instead.</div>";
            } else {

                if(empty($_POST['reg_service_other'])){
                    $status = 'active';
                  } else {
                    $status = 'pending';
                  }
                  
                
          
        //$reg_services = $reg_services.' '.$reg_service_other;  

        if(!preg_match('/^[a-zA-Z0-9- ]*$/', $event_title)){

          echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>You used some invalid characters in your form! Check your <b>Name</b> or <b>Description</b> inputs</div>";

        } else {
        
        //IMAGE UPLOAD CODE END

        if(move_uploaded_file($sourcePath,$targetPath)){

        $combined = $userkey.time();
        $time = substr(uniqid(),'0', '9');

        //TIME VARIABLE
        $current_time = date('l jS \of F Y');

         // convert the address to coordinates

         $reg_address = $event_address;

        include 'google/coordinates.php';

        //VERIFICATION LINK FOR USER

        $veri_link = "https://sabooksonline.co.za/verify?verifyid=$combined";

        //INSERT REGISTRATION DATA INTO DATABASE
          //$reg_password = password_hash($reg_password, PASSWORD_DEFAULT);

          if(empty($latitude) || empty($longitude)){
            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'We could not find the address you typed in on <b>Google</b>, Please select the suggested address from the input,showConfirmButton: false,timer: 6000});</script>";
          } else {

                    
                if(!filter_var($event_email, FILTER_VALIDATE_EMAIL)){
                    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your Email is Invalid! Please check your email.',showConfirmButton: false,timer: 6000});</script>";
                }else{

                    if(empty($reg_service_other) && empty($reg_services)){

                        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Please select service type, it seems to be empty!',showConfirmButton: false,timer: 6000});</script>";
                        
                    } else {

                        include 'scripts/functions/event_permissions.php';

                    }
                }

              }
              } else {
                echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'We could not upload your image!',showConfirmButton: false,timer: 6000});</script>";
              }
            }
        }
      }
    }
          
            
            
          /*if(!mail($reg_email,$subject,$message,$headers)){

            echo "<script>showSwal('warning-email-unsent');</script>";

          }else{
             echo "<script>showSwal('warning-email-sent');</script>";
          }*/

      

?>