<?php
// Start the session
    session_start();

  //DATABASE CONNECTIONS SCRIPT
  include '../database_connections/sabooks.php';

  $log_email = mysqli_real_escape_string($conn, $_POST['log_email']);
  $log_pwd2 = mysqli_real_escape_string($conn, $_POST['log_pwd2']);

      if(!filter_var($log_email, FILTER_VALIDATE_EMAIL)){
        echo "<div class='alert alert-warning'>Your email is invalid!</div>";
      }else{
 
        $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$log_email';";

        if(!mysqli_num_rows(mysqli_query($conn, $sql))){
          echo "<center class='alert alert-warning'>Email Not Found!</center>";
        }else {
          $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
 
          $dehash = $row['ADMIN_PASSWORD'];


          $status = $row['USER_STATUS'];

          if($status != "Verified"){
            echo "<center class='alert alert-warning'>Your account needs to be confirmed before you can login. Please check your emails for a confirmation email with a verification link.</center>";
          } else {
            if(!password_verify($log_pwd2, $dehash)){
              echo "<center class='alert alert-warning'>Password Incorrect!</center>";
            }else {
              /*start the sessions*/
             
             
                  $_SESSION['ADMIN_ID'] = $row['ADMIN_ID'];
                  $_SESSION['ADMIN_SUBSCRIPTION'] = $row['ADMIN_SUBSCRIPTION'];
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
                  $_SESSION['ADMIN_CUSTOMER_PLESK'] = $row['ADMIN_PINTEREST'];
                  $_SESSION['ADMIN_PASSWORD'] = $row['ADMIN_PASSWORD'];
                  $_SESSION['ADMIN_DATE'] = $row['ADMIN_DATE'];
                  $_SESSION['ADMIN_VERIFICATION_LINK'] = $row['ADMIN_VERIFICATION_LINK'];
                  $_SESSION['ADMIN_PROFILE_IMAGE'] = $row['ADMIN_PROFILE_IMAGE'];
                  $_SESSION['ADMIN_USERKEY'] = $row['ADMIN_USERKEY'];
                  $_SESSION['ADMIN_USER_STATUS'] = $row['ADMIN_USER_STATUS'];
                  $_SESSION['ADMIN_SERVICES'] = $row['ADMIN_SERVICES'];
                  $_SESSION['ADMIN_IMAGE01'] = $row['ADMIN_IMAGE01'];
                  $_SESSION['ADMIN_IMAGE02'] = $row['ADMIN_IMAGE02'];
                  $_SESSION['ADMIN_IMAGE03'] = $row['ADMIN_IMAGE03'];
                  $_SESSION['ADMIN_IMAGE04'] = $row['ADMIN_IMAGE04'];
                  
  
                  //$name = $_SESSION['ADMIN_NAME'];
  
  
                  echo '<script>window.location.href="dashboard/";</script>';
  
              /*endd the sessions*/
  
          
  
            }
          }

          

        }

      }