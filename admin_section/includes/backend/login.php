<?php
// Start the session
    session_start();

  //DATABASE CONNECTIONS SCRIPT
  include '../database_connections/sabooks.php';

  $log_email = mysqli_real_escape_string($conn, $_POST['log_email']);
  $log_pwd2 = mysqli_real_escape_string($conn, $_POST['log_password']);

      if(!filter_var($log_email, FILTER_VALIDATE_EMAIL)){
        echo "<div class='alert alert-warning'>Your email is invalid!</div>";
      }else{

        $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$log_email';";

        if(!mysqli_num_rows(mysqli_query($conn, $sql))){
          echo "<center>Email Not Found!</center>";
        }else {
          $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

          $dehash = $row['ADMIN_PASSWORD'];

          if(!password_verify($log_pwd2, $dehash)){
            echo "<center>Password Incorrect!</center>";
          }else {
            /*start the sessions*/
           
           
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
                

                $name = $_SESSION['ADMIN_NAME'];
                echo "<center>You are logged in '$name'</center>";
                header("Location: http://localhost/Projects/SAbooks/dashboard.php");

            /*endd the sessions*/

        

          }

        }

      }