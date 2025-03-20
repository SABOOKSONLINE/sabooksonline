<?php

        //The registartion code begins
        
        //DATABASE CONNECTIONS SCRIPT
        include '../../database_connections/sabooks.php';

        $contentid = mysqli_real_escape_string($conn, $_GET['contentid']);

        $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$contentid'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $status = $row['USER_STATUS'];
        $name = $row['ADMIN_NAME'];
        $email = $row['ADMIN_EMAIL'];
		$subscription = $row['ADMIN_SUBSCRIPTION'];

        $type = strtolower($row['ADMIN_TYPE']);
        $reg_email = $email;
        $reg_name = $name;

        if($status != 'Verified'){
            header("Location: ../../../pending-users.php?verification=failed");
        } else {

            
        $combined = $contentid.time();
        $time = substr(uniqid(),'0', '9');

        //TIME VARIABLE
        $d=strtotime("10:30pm April 15 2021");
        $current_time = date('l jS \of F Y');

        $page_overview = "";
        $page_profile_picture = "../../default.jpg";

        //Template page
        include '../templates/pages/user-page.php';

        $reg_password = substr(uniqid(), '0', '7');
        
        //VERIFICATION LINK FOR USER

        $veri_link = $combined;

        //INSERT REGISTRATION DATA INTO DATABASE
          $reg_password_hashed = password_hash($reg_password, PASSWORD_DEFAULT);

          $sql = "UPDATE users SET ADMIN_PASSWORD='$reg_password_hashed', ADMIN_USER_STATUS = 'approved' WHERE ADMIN_USERKEY = '$contentid'";
          
          if(mysqli_query($conn, $sql)){

            $sql = "UPDATE users SET RESETLINK = '$veri_link' WHERE ADMIN_USERKEY = '$contentid'";

            if(!mysqli_query($conn, $sql)){

                header("Location: ../../../pending-users.php?reset=failed");

            } else {
                 //create user profiles

                 if(mkdir("../../../../".$type."/".$contentid)){

                    mkdir("../../../../".$type."/".$contentid."/includes");

                    $file = "../../../../".$type."/".$contentid."/index.php";
                    $myfile = fopen($file , "w");
                    fwrite($myfile, $page);

                    if(fclose($myfile)){
                      $page_overview = "../../../../".$type."/".$contentid."/includes/overview.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, "Hey! My name is ".$name.".");

                      $page_overview = "../../../../".$type."/".$contentid."/includes/title.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, $name);

                      $page_overview = "../../../../".$type."/".$contentid."/includes/number.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, '+27');

                      $page_overview = "../../../../".$type."/".$contentid."/includes/facebook.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, 'https://facebook.com/');

                      $page_overview = "../../../../".$type."/".$contentid."/includes/twitter.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, 'https://twitter.com/');

                      $page_overview = "../../../../".$type."/".$contentid."/includes/instagram.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, 'https://instagram.com/');

                      $page_overview = "../../../../".$type."/".$contentid."/includes/address.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, 'Your Address Here');

                      $page_overview = "../../../../".$type."/".$contentid."/includes/website.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, 'Your website Here');

                      $page_overview = "../../../../".$type."/".$contentid."/includes/keywords.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, 'keyword, keyword, keyword');

                      $page_overview = "../../../../".$type."/".$contentid."/includes/email.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, $email);
                        
                     $page_overview = "../../../../".$type."/".$contentid."/includes/type.php";
                      $myfile = fopen($page_overview , "w");
                      fwrite($myfile, $type);

                      

                      if(fclose($myfile)){
                        $page_profile = "../../../../".$type."/".$contentid."/includes/cover.php";
                        $myfile = fopen($page_profile , "w");
                        fwrite($myfile, 'https://my.sabooksonline.co.za/img/logo.png');
                      }
                      $link = "https://my.sabooksonline.co.za/".strtolower($type)."/".$contentid;
						
						
					if($subscription == 'Starter'){
							$amount = '120';
					} else if ($subscription == 'Standard'){
							$amount = '100';
					} else if ($subscription == 'Premium'){
							$amount = '80';
					}
						
					$sub_type = $subscription;
                      
                        
                    $message1 = "Thank you for your membership! Here is your password ".$reg_password." you can now login to your dashboard."."\n\nSA Books Online. "."\n\nKind Regards \nSA Books Team" ;

                    $message = "Thank you for taking the time to apply for membership. Your account has been now approved and you can start publishing your books. To login to your account you need to set up your password, please click the link below to start setting up your password.";

                    $button_link = "https://my.sabooksonline.co.za/set-password.php?code=".$veri_link;
                    $link_text = "Create Your Password";

                    $subject = "Password Creation For Your Account";
					//Email template multiuse
                    include '../templates/emails/multiuse.php';

                    //echo $subscription;

					if(mail($email,$subject,$message2,$headers)){

							header("Location: ../../../pending-users.php?status1=success");

						} else {
							header("Location: ../../../pending-users.php?reset=failed");
						}

                    } else {
						header("Location: ../../../pending-users.php?reset=failed");
					}

                }
            }

                           
                
          }else {
                echo "Bad";
          }


        }
        



         /* $createTable = "CREATE TABLE $userkey (
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
      
            
          /*if(!mail($reg_email,$subject,$message,$headers)){

            echo "<script>showSwal('warning-email-unsent');</script>";

          }else{
             echo "<script>showSwal('warning-email-sent');</script>";
          }*/


?>