<?php

        //The registartion code begins

        //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $reg_name = mysqli_real_escape_string($conn, $_POST['reg_name']);
        $reg_email = mysqli_real_escape_string($conn, $_POST['reg_email']);
        $reg_number = mysqli_real_escape_string($conn, $_POST['reg_number']);
        $reg_password = mysqli_real_escape_string($conn, $_POST['reg_password']);
        $reg_confirm_password = mysqli_real_escape_string($conn, $_POST['reg_confirm_password']);
        $reg_type = mysqli_real_escape_string($conn, $_POST['reg_type']);
        $reg_bio = mysqli_real_escape_string($conn, $_POST['reg_bio']);
        $reg_address = mysqli_real_escape_string($conn, $_POST['reg_address']);
        $reg_province = mysqli_real_escape_string($conn, $_POST['reg_province']);
        $reg_service_other = mysqli_real_escape_string($conn, $_POST['reg_service_other']);


        $userkey = substr(uniqid(),'0', '6').time();


        $sourcePath = $_FILES['reg_profile']['tmp_name'];

        $extension = pathinfo($_FILES["reg_profile"]["name"], PATHINFO_EXTENSION);

        $targetPath = "../../cms-data/profile-images/".str_replace(' ','-', $reg_name).'-'.$userkey.".".$extension;

        $targetPath1 = str_replace(' ','-', $reg_name).'-'.$userkey.".".$extension;

        //IMAGE UPLOAD CODE END 

        if(!preg_match('/^[a-zA-Z0-9- ]*$/', $reg_name)){

          echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>You used some invalid characters in your form! Check your <b>Name</b> or <b>Description</b> inputs</div>";

        } else {
        
        //IMAGE UPLOAD CODE END

        move_uploaded_file($sourcePath,$targetPath);
        
        if(!isset($_POST['services'])){
          $reg_services = '';
        } else {
          $reg_services = $_POST['services'];

          $chk1 = '';

          foreach($reg_services as $chk1)  
          {  
              $reg_services .= $chk1.",";  
          } 
        }
      

        $reg_services = $reg_services.' '.$reg_service_other;  

        $combined = $userkey.time();
        $time = substr(uniqid(),'0', '9');
        $userkey = $userkey.$time;

		    $reg_type = strtolower($reg_type);

        //print_r($reg_services);

        $reg_services = str_replace('Array','', $reg_services);

        //TIME VARIABLE
        $current_time = date('l jS \of F Y');

         // convert the address to coordinates

        include 'google/coordinates.php';

        //VERIFICATION LINK FOR USER

        $veri_link = "https://sabooksonline.co.za/verify?verifyid=$combined";

        //INSERT REGISTRATION DATA INTO DATABASE
          //$reg_password = password_hash($reg_password, PASSWORD_DEFAULT);

          if(empty($latitude) || empty($latitude)){
            echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>We could not find the address you typed in on <b>Google</b>, Please select the suggested address from the input.</div>";
          } else {


            $price = 0;
            $cycle = "";


            if($reg_type === 'book-store'){
                $price =  500;
                $cycle = "/month";
            } else if ($reg_type === 'author' || $reg_type === 'publisher'){
                $price =  10 * 12;
                $cycle = "/year";
            } else if($reg_type = 'service-provider'){

                if(strstr($reg_services, 'Printer')){

                    $price =  $price + 50; 
    
                } 
    
                if(strstr($reg_services, 'Distributor')){
    
                    $price =  $price + 50; 
    
                } 
    
                if(strstr($reg_services, 'Editor')){
    
                    $price =  $price + 10; 
    
                } 
    
                if(strstr($reg_services, 'Writer')){
    
                    $price =  $price + 10; 
    
                } 
    
                if(strstr($reg_services, 'Reviewer')){
    
                    $price =  $price + 10; 
    
                } 

                if(strstr($reg_services, 'Illustrator')){
    
                    $price =  $price + 10; 
    
                } 

                if(!empty($reg_service_other)){
    
                    $price =  $price + 10; 
    
                } 

                $cycle = "/month";
            } 

           


                if(!filter_var($reg_email, FILTER_VALIDATE_EMAIL)){
                  echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your email address is invalid!</div>";
                }else{

                  if ($reg_password != $reg_confirm_password){
                    echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your passwords don't match!</div>";
                  } else {

                  $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$reg_email';";

                  if(mysqli_num_rows(mysqli_query($conn, $sql))){
                    echo "<p class='alert alert-warning mt-4'>The email '$reg_email' already exists!</p>";
                  }else {

                    $reg_password = password_hash($reg_password, PASSWORD_DEFAULT);
                    
                    $sql = "INSERT INTO users (ADMIN_NAME, ADMIN_EMAIL, ADMIN_NUMBER, ADMIN_WEBSITE, ADMIN_BIO, ADMIN_TYPE, ADMIN_FACEBOOK, ADMIN_TWITTER, ADMIN_LINKEDIN, ADMIN_GOOGLE, ADMIN_INSTAGRAM, ADMIN_PINTEREST, ADMIN_PASSWORD, ADMIN_DATE, ADMIN_VERIFICATION_LINK, ADMIN_PROFILE_IMAGE, ADMIN_USERKEY, ADMIN_USER_STATUS,RESETLINK, USER_STATUS, ADMIN_SUBSCRIPTION, ADMIN_LATITUDE, ADMIN_LONGITUDE, ADMIN_SERVICES, ADMIN_IMAGES, ADMIN_PROVINCE, ADMIN_SHIPPING) VALUES ('$reg_name','$reg_email','$reg_number','','$reg_bio','$reg_type','','','','$reg_address','','','$reg_password','$current_time','$combined','$targetPath1','$userkey','pending','$combined','','Free','$latitude','$longitude','$reg_services','', '$reg_province', '');";

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

                    
                    $message = "Thank you for applying for membership on our website. You need to verify your email by clicking the link below. Here is your confirmation link ".$veri_link;     

                    $button_link = $veri_link;
                    $link_text = "Confirm Your Email";

                    include '../templates/email.php';

                    $subject = 'Confirmation Of Registration For '.ucwords($reg_name).' ';

                    $sqlss = "UPDATE users SET RESETLINK = '$combined' WHERE ADMIN_USERKEY = '$userkey'";
                                    
                    mysqli_query($conn, $sqlss);

                    //$header = "From: Admin SABO <accounts@my.sabooksonline.co.za>";

                    if(mail($reg_email,$subject,$message2,$headers)){ 

                            $invoice_number = substr(rand(), 0, 5);

                            $contentid = $invoice_number;

                            $current_time = date('l jS \of F Y');  

                            $payment_status = "Promo";
  
                            $payment_method = "Online";

                            $title = $reg_name;

                            $type = $reg_type;

                            $amount = $price;


                           // include 'functions/insert-invoice.php';

                            $style = 'style="color:#990000;"';

                            $message = "A new account has just been created for membership. ";
                            
                            //$message .= "<br><br><b $style>YOU ARE CURRENTLY ON THE FREE PLAN, CLICK THE BUTTON BELOW TO UPGRADE YOUR PLAN.</b>";
                            $message .= "<br><br><b>Name:</b> ".$reg_name;
                            $message .= "<br><br><b>Type:</b> ".str_replace('-', ' ', ucfirst($reg_type));
                            $message .= "<br><br><b>Email:</b> ".$reg_email;
                            $message .= "<br><br><b>Number:</b> ".$reg_number;
                            $message .= "<br><br><b>Date:</b> ".$current_time;
                          

                          $button_link = "https://my.sabooksonline.co.za/sabo/page-dashboard-plans";
                          $link_text = "Upgrade Your Plan";
              
                         // include '../templates/email.php';
              
                          $subject = 'New Account Creation For '.ucwords($reg_name).' ';

                          echo '<script>window.location.href="https://sabooksonline.co.za/confirm.php?email='.$reg_email.'&message=Membership Account Created Successfully";</script>';

                        mail('admin@sabooksonline.co.za',$subject,$message,$headers);
                         
                  
                      }


                      
                    }else {
                          echo "<p class='alert alert-warning p-2 mb-3 mt-3'>Your account could not be created!</p>";
                    }

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