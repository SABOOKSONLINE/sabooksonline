<?php
// Start the session
    session_start();

  //DATABASE CONNECTIONS SCRIPT
  include '../database_connections/sabooks.php';

  $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
  $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
  $code = mysqli_real_escape_string($conn, $_POST['code']);

        if($confirm_password != $new_password){
            echo "<p class='alert alert-warning p-2 mb-3'>Your passwords don't match!</p>";
        }else{

            $sql = "SELECT * FROM users WHERE RESETLINK = '$code';";

            if(!mysqli_num_rows(mysqli_query($conn, $sql))){
              echo "<p class='alert alert-warning p-2 mb-3'>Your security token has expired. Try re-sending the password recovery link!</p>";
            }else {
                
                $result = mysqli_query($conn, $sql);
                
                 while ($row = mysqli_fetch_assoc($result)) {
                
                $reg_name = $row['ADMIN_NAME'];
                $reg_email = $row['ADMIN_EMAIL'];

                }

                $reg_password = password_hash($new_password, PASSWORD_DEFAULT);
              
                $sql = "UPDATE users SET ADMIN_PASSWORD = '$reg_password' WHERE RESETLINK ='$code'";
                if(mysqli_query($conn, $sql)){
        
                    $sql = "UPDATE users SET RESETLINK = ' ' WHERE RESETLINK ='$code'";
    
                    if(mysqli_query($conn, $sql)){

                    $message = "Your password has been updated. If you didn't authorise this please be in contact with us.";
                    $button_link = "https://my.sabooksonline.co.za/login";
                    $link_text = "Login To Account";
                  		  
				        include '../templates/email.php';

                $subject = 'Password Change Confirmation For '.ucwords($reg_name).' ';

                mail($reg_email,$subject,$message2,$headers);

                        $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$reg_email';";

                        $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
            
                        $_SESSION['ADMIN_ID'] = $row['ADMIN_ID'];
                        $_SESSION['ADMIN_NAME'] = $row['ADMIN_NAME'];
                        $_SESSION['ADMIN_EMAIL'] = $row['ADMIN_EMAIL'];
                        $_SESSION['ADMIN_NUMBER'] = $row['ADMIN_NUMBER'];
                        $_SESSION['ADMIN_WEBSITE'] = $row['ADMIN_WEBSITE'];
                        $_SESSION['ADMIN_BIO'] = $row['ADMIN_BIO'];
                        $_SESSION['ADMIN_TYPE'] = $row['ADMIN_TYPE'];
                        $_SESSION['ADMIN_FACEBOOK'] = $row['ADMIN_FACEBOOK'];
                        $_SESSION['ADMIN_TWITTER'] = $row['ADMIN_TWITTER'];
                        $_SESSION['ADMIN_LINKEDIN'] = $row['ADMIN_LINKEDIN'];
                        $_SESSION['ADMIN_GOOGLE'] = $row['ADMIN_GOOGLE'];
                        $_SESSION['ADMIN_INSTAGRAM'] = $row['ADMIN_INSTAGRAM'];
                        $_SESSION['ADMIN_PINTEREST'] = $row['ADMIN_PINTEREST'];
                        $_SESSION['ADMIN_PASSWORD'] = $row['ADMIN_PASSWORD'];
                        $_SESSION['ADMIN_DATE'] = $row['ADMIN_DATE'];
                        $_SESSION['ADMIN_VERIFICATION_LINK'] = $row['ADMIN_VERIFICATION_LINK'];
                        $_SESSION['ADMIN_PROFILE_IMAGE'] = $row['ADMIN_PROFILE_IMAGE'];
                        $_SESSION['ADMIN_USERKEY'] = $row['ADMIN_USERKEY'];
                        $_SESSION['ADMIN_USER_STATUS'] = $row['ADMIN_USER_STATUS'];
                        

                        echo '<script>window.location.href="dashboard-2";</script>';
                      echo "<p class='alert alert-success p-2 mb-3' style='border: none !important'>Your password has been changed! Please check your email for confirmation. </p>";
                                   
                    }else {
                          echo "<p class='alert alert-warning p-2 mb-3'>Your request could not be processed!</p>";
                    }
                                 
                  }else {
                        echo "<p class='alert alert-warning p-2 mb-3'>Your request could not be processed!</p>";
                  }

               
    
            }
         }