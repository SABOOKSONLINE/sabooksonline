<?php

        session_start();
        //The registartion code begins

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

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
        $event_date = mysqli_real_escape_string($conn, $_POST['dates_start']);
        $event_date_end = mysqli_real_escape_string($conn, $_POST['dates_end']);
        $event_days = mysqli_real_escape_string($conn, $_POST['child']);
        $event_end_time = mysqli_real_escape_string($conn, $_POST['event_end_time']);
        $event_id = mysqli_real_escape_string($conn, $_POST['event_id']);

        $userkey = $_SESSION['ADMIN_USERKEY'];
        $reg_email = $_SESSION['ADMIN_EMAIL'];
        $reg_name = $_SESSION['ADMIN_NAME'];

        //$userkey = substr(uniqid(),'0', '6').time();


        // Initialize arrays for multi-select fields
        $reg_services = isset($_POST['services']) ? $_POST['services'] : array();
        $event_type = isset($_POST['event_type']) ? $_POST['event_type'] : array();

        // Convert arrays to comma-separated strings
        $reg_services_str = implode('|', $reg_services);
        $event_type_str = implode('|', $event_type);

       
       

        //IMAGE UPLOAD CODE END 

        if(!preg_match('/^[a-zA-Z0-9- ]*$/', $event_title)){

          echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>You used some invalid characters in your form! Check your <b>Name</b> or <b>Description</b> inputs</div>";

        } else {
        
        //IMAGE UPLOAD CODE END


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
            echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>We could not find the address you typed in on <b>Google</b>, Please select the suggested address from the input.</div>";
          } else {

                if(!filter_var($event_email, FILTER_VALIDATE_EMAIL)){
                  echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your email address is invalid!</div>";
                }else{

                    $sourcePath = $_FILES['event_cover']['tmp_name'];

                    $extension = pathinfo($_FILES["event_cover"]["name"], PATHINFO_EXTENSION);

                    $targetPath = "../../cms-data/event-covers/".$event_id.".".$extension;

                    $targetPath1 = $event_id.".".$extension;

                  $sourcePath = $_FILES['event_cover']['tmp_name'];


                    if(move_uploaded_file($sourcePath,$targetPath)){
                        $profile = $targetPath;
            
                        $sql = "UPDATE events SET COVER='$targetPath1' WHERE CONTENTID = '$event_id'";
            
                        mysqli_query($conn, $sql);
            
                    } 
                    
            
                    $sql = "UPDATE events SET 
                            TITLE = '$event_title', 
                            DESCRIPTION = '$event_desc',
                            EVENTDATE = '$event_date',
                            EVENTTIME = '$event_start_time',
                            VENUE = '$event_address',
                            KEYWORDS = '$reg_services_str',
                            EVENTTYPE = '$event_type_str',
                            EMAIL = '$event_email',
                            NUMBER = '$event_number',
                            LINK = '$event_end_time',
                            DURATION = '$event_days',
                            PROVINCE = '$event_province',
                            LATITUDE = '$latitude',
                            LONGITUDE = '$longitude',
                            EVENTEND = '$event_date_end',
                            TIMEEND = '$event_end_time'
                        WHERE CONTENTID = '$event_id'";


                    
                    if(mysqli_query($conn, $sql)){
                        
                        echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Event successfully updated!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('events');},3000);</script>";

                    }else {
                        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Could not update the event!',showConfirmButton: false,timer: 6000});</script>";
                    }
                }

              }
              
            }
          }
 

?>
