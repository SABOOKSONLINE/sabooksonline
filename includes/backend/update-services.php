<?php

        session_start();

        //The registartion code begins

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        

        //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $reg_name = mysqli_real_escape_string($conn, $_POST['reg_name']);
        $reg_email = mysqli_real_escape_string($conn, $_POST['reg_email']);
        $reg_number = mysqli_real_escape_string($conn, $_POST['reg_number']);
        $reg_type = mysqli_real_escape_string($conn, $_POST['reg_type']);
        $reg_bio = mysqli_real_escape_string($conn, $_POST['reg_bio']);
        $reg_address = mysqli_real_escape_string($conn, $_POST['reg_address']);
        $reg_province = mysqli_real_escape_string($conn, $_POST['reg_province']);
        $reg_service_other = mysqli_real_escape_string($conn, $_POST['reg_service_other']);

        $reg_bio = str_replace("'", "'", $reg_bio);


        $userkey = $_SESSION['ADMIN_USERKEY'];
        $type = $_SESSION['ADMIN_TYPE'].','.$reg_type;


        if(!preg_match('/^[a-zA-Z0-9- ]*$/', $reg_name)){

          echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You used some invalid characters in your form! Check your Name or Description inputs',showConfirmButton: false,timer: 6000});</script>";

        } else {
        
        //IMAGE UPLOAD CODE END

        
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
       // $userkey = $userkey.$time;

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

            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'We could not find the address you typed in on Google, Please select the suggested address from the input.',showConfirmButton: false,timer: 6000});</script>";
            
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

                    $sql = "UPDATE users SET ADMIN_NAME='$reg_name',ADMIN_EMAIL='$reg_email',ADMIN_NUMBER='$reg_number',ADMIN_BIO='$reg_bio' ,ADMIN_GOOGLE='$reg_address',ADMIN_PINTEREST='$reg_address',ADMIN_LATITUDE='$latitude' ,ADMIN_LONGITUDE='$longitude' ,ADMIN_SERVICES='$reg_services' WHERE ADMIN_USERKEY='$userkey'";

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

                    
                    $message = "Thank you for applying for membership on our website. You need to verify your email by clicking the link below.";

                    $button_link = $veri_link;
                    $link_text = "Confirm Your Email";

                    include '../templates/email.php';

                    $subject = 'Confirmation Of Registration For '.ucwords($reg_name).' ';

                    if(mail($reg_email,$subject,$message2,$headers)){

                            $invoice_number = substr(rand(), 0, 5);

                            $contentid = $invoice_number;

							$current_time = date('l jS \of F Y');

							$payment_status = "Promo";

							$payment_method = "Online";

                            $title = $reg_name;

                            $type = $reg_type;

                            $amount = $price;


                            include 'functions/insert-invoice.php';

                            $style = 'style="color:#990000;"';

                            $subscription = "Service Listing";

                            $message = "A new account has just been created for membership. Your subscription is ".$subscription." with a yearly subscription of R".$price." ".$cycle.".";
                            $message .= " An invoice has been generated for your new ".str_replace('-', ' ', ucfirst($reg_type))." Account.".ucfirst($title);
                            $message .= "<br><br><b $style>THIS PROMOTIONAL VOUCHER IS VALID TILL 30 SEPTEMBER 2023.</b>";
                            $message .= "<br><br><b>Name:</b> ".$reg_name;
                            $message .= "<br><br><b>Type:</b> ".str_replace('-', ' ', ucfirst($reg_type));
                            $message .= "<br><br><b>Email:</b> ".$reg_email;
                            $message .= "<br><br><b>Number:</b> ".$reg_number;
                            $message .= "<br><br><b>Date:</b> ".$current_time;
							$message .= "<br><br><b>Invoice No:</b> #".$invoice_number;
							$message .= "<br><b>Payment Method:</b> Online";
							$message .= "<br><b>Payment Status:</b> Unpaid";
							$message .= "<br><b>Date Issued:</b> ".$current_time;
							$message .= "<br><b>Amount Due:</b> R".$price;
                          

                          $button_link = "https://sabooksonline.co.za/dashboard-invoices";
                          $link_text = "See My Invoices";
              
                          include '../templates/email.php';
              
                          $subject = 'New Account Creation For '.ucwords($reg_name).' ';

                          if(mail($reg_email,$subject,$message2,$headers)){
                            echo '<script>window.location.href="../confirm.php?email='.$reg_email.'&message=Membership Account Created Successfully";</script>';
                          } else {
                            echo 'Cant';
                          }
                  
                      }


                      
                    }else {
                          echo "<p class='alert alert-warning p-2 mb-3 mt-3'>Your account could not be created!</p>";
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