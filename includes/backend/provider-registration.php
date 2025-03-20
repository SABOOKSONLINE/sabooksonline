<?php

        //The registartion code begins

        //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $reg_name = mysqli_real_escape_string($conn, $_POST['reg_name']);
        $reg_email = mysqli_real_escape_string($conn, $_POST['reg_email']);
        $reg_address = mysqli_real_escape_string($conn, $_POST['reg_address']);
        $reg_password = mysqli_real_escape_string($conn, $_POST['reg_password']);
        $reg_confirm_password = mysqli_real_escape_string($conn, $_POST['reg_confirm_password']);
        $reg_number = mysqli_real_escape_string($conn, $_POST['reg_number']);
        $reg_services = mysqli_real_escape_string($conn, $_POST['reg_services']);

        $userkey = substr(uniqid(),'0', '9').time();
      

        $combined = $userkey.time();
        $time = substr(uniqid(),'0', '9');
        $userkey = $userkey.$time;

		    $reg_type = strtolower($reg_type);

        //TIME VARIABLE
        $current_time = date('l jS \of F Y');

        //VERIFICATION LINK FOR USER

        $veri_link = "https://sabooksonline.co.za/verify?verifyid=$combined";


        // convert the address to coordinates

        include 'google/coordinates.php';

        //INSERT REGISTRATION DATA INTO DATABASE
          //$reg_password = password_hash($reg_password, PASSWORD_DEFAULT);

          
      if(!filter_var($reg_email, FILTER_VALIDATE_EMAIL)){
        echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your email address is invalid!</div>";
      }else{

        if ($reg_password != $reg_confirm_password){
          echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your passwords don't match!</div>";
        } else {

        $sql = "SELECT * FROM providers WHERE PROVIDER_EMAIL = '$reg_email';";

        if(mysqli_num_rows(mysqli_query($conn, $sql))){
          echo "<p class='text-warning p-2 mb-3 mt-4'>The email '$reg_email' already exists!</p>";
        }else {

          //$reg_password = password_hash($reg_password, PASSWORD_DEFAULT);
          
          $sql = "INSERT INTO providers (PROVIDER_NAME, PROVIDER_EMAIL, PROVIDER_ADDRESS, PROVIDER_LATITUDE, PROVIDER_LONGITUDE, PROVIDER_RATING, PROVIDER_SERVICES, PROVIDER_PROFILE, PROVIDER_STATUS) VALUES ('$reg_name', '$reg_email', '$reg_address', '$latitude', '$longitude', '', '$reg_services', '', 'active')";

          if(mysqli_query($conn, $sql)){

            
          /*$createTable = "CREATE TABLE userkey (
            ID int(225) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            PRODUCTIMAGE text NOT NULL,
            TITLE text NOT NULL,
            PRICE text NOT NULL,
            COLOR text NOT NULL,
            QUANTITY text NOT NULL,
            CURRENT text NOT NULL,
            PRODUCTID text NOT NULL,
            SIZE text NOT NULL
          );";

          mysqli_query($conn, $createTable);*/

          
         /* $message = "Thank you for applying for membership on our website. You need to verify your email by clicking the link below.";

          $button_link = $veri_link;
          $link_text = "Confirm Your Email";

          include '../templates/email.php';

          $subject = 'Confirmation Of Registration For '.ucwords($reg_name).' ';

		      $sqlss = "UPDATE users SET RESETLINK = '$combined' WHERE ADMIN_USERKEY = '$userkey'";
											
		      mysqli_query($conn, $sqlss);

           if(mail($reg_email,$subject,$message2,$headers)){
                
                $message = "A new account has just been created for membership. Your subscription is ".$subscription." with a yearly subscription of R".$amount."/year.";
                $message .= "<br><br><b>Name:</b> ".$reg_name;
                $message .= "<br><br><b>Type:</b> ".$type;
                $message .= "<br><br><b>Email:</b> ".$reg_email;
                $message .= "<br><br><b>Number:</b> ".$reg_number;
                $message .= "<br><br><b>Date:</b> ".$current_time;
                $message .= "<br><br><b>Subscription:</b> ".$subscription;

                $button_link = "https://sabooksonline.co.za/admin_section";
                $link_text = "Admin Dashboard";
    
                include '../templates/email.php';
    
                $subject = 'New Account Creation For '.ucwords($reg_name).' ';

                mail('accounts@sabooksonline.co.za',$subject,$message2,$headers);
				
                echo '<script>window.location.href="confirm.php?email='.$reg_email.'";</script>';

            }*/

            echo '<script>window.location.href="confirm.php?email='.$reg_email.'";</script>';


            
          }else {
                echo "<p class='alert alert-warning p-2 mb-3'>Your account could not be created!</p>";
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