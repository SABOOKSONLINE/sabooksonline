<?php

        //The registartion code begins

        //DATABASE CONNECTIONS SCRIPT
        include '../includes/database_connections/sabooks.php';

        $reg_name = $user->getName() ;
        $reg_email = $user->getEmail();
        $reg_number = 'None';
        $reg_password = '01234nola';
        $reg_confirm_password = '01234nola';
        $reg_type = 'Free';
		$profileimage = $user->getPicture();

        //IMAGE UPLOAD CODE END

        if(!preg_match('/^[a-zA-Z0-9 ]*$/', $reg_name)){

          echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>You used some invalid characters in your form! Check your <b>Name</b> or <b>Description</b> inputs</div>";

        } else {
        
        //IMAGE UPLOAD CODE END

        $userkey = substr(uniqid(),'0', '9').time();
      

        $combined = $userkey.time();
        $time = substr(uniqid(),'0', '9');
        $userkey = $userkey.$time;

		$reg_type = strtolower($reg_type);

        //print_r($reg_services);

       

        $reg_services = 'None';

        //TIME VARIABLE
        $current_time = date('l jS \of F Y');

        //VERIFICATION LINK FOR USER

        $veri_link = "https://11-july-2023.sabooksonline.co.za/verify?verifyid=$combined";

        //INSERT REGISTRATION DATA INTO DATABASE
          //$reg_password = password_hash($reg_password, PASSWORD_DEFAULT);

                    
                if(!filter_var($reg_email, FILTER_VALIDATE_EMAIL)){
                  echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your email address is invalid!</div>";
                }else{

                  if ($reg_password != $reg_confirm_password){
                    echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your passwords don't match!</div>";
                  } else {

                  $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$reg_email';";

                  if(mysqli_num_rows(mysqli_query($conn, $sql))){
                    echo "<p class='alert alert-warning mt-4'>The email '$reg_email' already exists!</p>";
					  
					  include 'google-login.php';
					  
                  }else {

                    $reg_password = password_hash($reg_password, PASSWORD_DEFAULT);
                    
                    $sql = "INSERT INTO users (ADMIN_NAME, ADMIN_EMAIL, ADMIN_NUMBER, ADMIN_WEBSITE, ADMIN_BIO, ADMIN_TYPE, ADMIN_FACEBOOK, ADMIN_TWITTER, ADMIN_LINKEDIN, ADMIN_GOOGLE, ADMIN_INSTAGRAM, ADMIN_PINTEREST, ADMIN_PASSWORD, ADMIN_DATE, ADMIN_VERIFICATION_LINK, ADMIN_PROFILE_IMAGE, ADMIN_USERKEY, ADMIN_USER_STATUS,RESETLINK, USER_STATUS, ADMIN_SUBSCRIPTION, ADMIN_LATITUDE, ADMIN_LONGITUDE, ADMIN_SERVICES, ADMIN_IMAGES, ADMIN_PROVINCE) VALUES ('$reg_name','$reg_email','','','','$reg_type','','','','','','','$reg_password','$current_time','$combined','$profileimage','$userkey','pending','$combined','Verified','Free','','','','', '');";

                    if(mysqli_query($conn, $sql)){


                          $message = "A new account has just been created, Review the details below.";
                          $message .= "<br><br><b>Name:</b> ".$reg_name;
                          $message .= "<br><br><b>Type:</b> ".$type;
                          $message .= "<br><br><b>Email:</b> ".$reg_email;
                          $message .= "<br><br><b>Number:</b> ".$reg_number;
                          $message .= "<br><br><b>Date:</b> ".$current_time;

                          $button_link = "https://sabooksonline.co.za/admin_section";
                          $link_text = "User Dashboard";
              
                          include '../templates/email.php';
              
                          $subject = 'New Account Creation For '.ucwords($reg_name).' ';

                          mail($reg_email,$subject,$message2,$headers);
                  
                          //echo '<script>window.location.href="https://sabooksonline.co.za/sabo/page-dashboard-plan";</script>';
						
							include 'google-login.php';

                    }else {
                          echo "<p class='alert alert-warning p-2 mb-3'>Your account could not be created!</p>";
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