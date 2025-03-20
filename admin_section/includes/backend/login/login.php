<?php
// Start the session
    session_start();

  //DATABASE CONNECTIONS SCRIPT
  include '../../database_connections/sabooks.php';

  $log_email = "email@oner.co.za";
  $log_pwd2 = "12345";
  $contentid = $_GET['contentid'];

      if(!filter_var($log_email, FILTER_VALIDATE_EMAIL)){
        echo "<div class='alert alert-warning'>Your email is invalid!</div>";
      }else{

        $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$contentid';";

        if(!mysqli_num_rows(mysqli_query($conn, $sql))){
          echo "<center class='alert alert-warning'>Email Not Found!</center>";
        }else {
          $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

          $dehash = $row['ADMIN_PASSWORD'];

           
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
                  $_SESSION['ADMIN_SERVICES'] = $row['ADMIN_SERVICES'];
                  $_SESSION['ADMIN_IMAGE01'] = $row['ADMIN_IMAGE01'];
                  $_SESSION['ADMIN_IMAGE02'] = $row['ADMIN_IMAGE02'];
                  $_SESSION['ADMIN_IMAGE03'] = $row['ADMIN_IMAGE03'];
                  $_SESSION['ADMIN_IMAGE04'] = $row['ADMIN_IMAGE04'];
                

                header("Location: ../../../../dashboard-2?adminlogin");

            /*endd the sessions*/


        }

      }