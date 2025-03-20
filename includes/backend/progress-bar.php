<?php

  //DATABASE CONNECTIONS SCRIPT
  include 'includes/database_connections/sabooks.php';

  $log_email = $_SESSION['ADMIN_USERKEY'];

     
        $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$log_email';";

        if(!mysqli_num_rows(mysqli_query($conn, $sql))){
          echo "<center class='alert alert-info'>Progress Data Not Found</center>";
        }else {
          $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

          $name = $row['ADMIN_NAME'];
          $email = $row['ADMIN_EMAIL'];
          $number = $row['ADMIN_NUMBER'];
          $website = $row['ADMIN_WEBSITE'];
          $profile = $row['ADMIN_PROFILE_IMAGE'];
          $description = $row['ADMIN_BIO'];

          $facebook = $row['ADMIN_FACEBOOK'];
          $instagram = $row['ADMIN_INSTAGRAM'];
          $twitter = $row['ADMIN_TWITTER'];
          $linkedin = $row['ADMIN_LINKEDIN'];
          $province = $row['ADMIN_GOOGLE'];


          $progressdata = 10;


          if(!empty($name) && !empty($email) && !empty($number)){
                $progressdata = 20;
            }

          
            if(!empty($website)){
                $progressdata = $progressdata + 10;
            }

            if(!empty($profile)){
                $progressdata = $progressdata + 10;
            }

            if(!empty($description)){
                $progressdata = $progressdata + 10;
            }

            if(!empty($facebook)){
                $progressdata = $progressdata + 10;
            }

            if(!empty($instagram)){
                $progressdata = $progressdata + 10;
            }

            if(!empty($twitter)){
                $progressdata = $progressdata + 10;
            }

            if(!empty($linkedin)){
                $progressdata = $progressdata + 10;
            }

            if(!empty($province)){
                $progressdata = $progressdata + 10;
            }




            if($progressdata <= 60){
                echo '<div class="progress p-0" style="height:40px">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: '.$progressdata.'%;height: 40px;" aria-valuenow="'.$progressdata.'" aria-valuemin="0" aria-valuemax="100">'.$progressdata.'% Complete - Profile Hidden</div>
                    </div>';
            }else{
                echo '<div class="progress p-0" style="height:40px">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: '.$progressdata.'%;height: 40px;" aria-valuenow="'.$progressdata.'" aria-valuemin="0" aria-valuemax="100">'.$progressdata.'% Complete - Profile Visible</div>
                    </div>';
            }


           

          }
