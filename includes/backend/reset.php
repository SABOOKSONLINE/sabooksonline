<?php
// Start the session
    session_start();

  //DATABASE CONNECTIONS SCRIPT
  include '../database_connections/sabooks.php';

  $log_email = mysqli_real_escape_string($conn, $_POST['log_email']);

      if(!filter_var($log_email, FILTER_VALIDATE_EMAIL)){
        echo "<div class='alert alert-warning p-2 mb-3'>Your email is invalid!</div>";
      }else{

        $link = substr(uniqid(), '0', '15');
        $link = strtolower($link).time();

        $link_email = "password-reset?code=".$link;

        $sql = "UPDATE users SET RESETLINK = '$link' WHERE ADMIN_EMAIL ='$log_email'";

        if(mysqli_query($conn, $sql)){

          $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$log_email';";

          if(!mysqli_num_rows(mysqli_query($conn, $sql))){
            echo "<center class='alert alert-warning'>Email Not Found!</center>";
          }else {
            $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

            $reg_name = $row['ADMIN_NAME'];
            $reg_email = $row['ADMIN_EMAIL'];
          }

          $message = "You request a password reset for your SABO Account, Please click the link below to reset your password. If you didn't authorise this please be in contact with us.";
          $button_link = "https://sabooksonline.co.za/".$link_email;
          $link_text = "Reset Your Password";
              
          include '../templates/email.php';

          $subject = 'Password Change Request For Account ';

          mail($reg_email,$subject,$message2,$headers);
              

          echo "<p class='alert alert-success p-2 mb-3'>Your request has been processed. Please check your email for confirmation.</p>";
                       
        }else {
              echo "<p class='alert alert-warning p-2 mb-3'>Your request could not be processed!</p>";
        }

      }