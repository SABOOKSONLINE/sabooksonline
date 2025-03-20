<?php

$fileContent = file_get_contents('api_key.txt');

ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

    session_start();

  //DATABASE CONNECTIONS SCRIPT
  include 'db.php';

  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);

      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "<div class='alert alert-warning'>Your email is invalid!</div>";
      }else{

        $sql = "SELECT * FROM user_info WHERE email = '$email';";

        if(!mysqli_num_rows(mysqli_query($con, $sql))){
          echo "<center class='alert alert-warning'>Email Not Found!</center>";
        }else {
          $row = mysqli_fetch_assoc(mysqli_query($con, $sql));

          $dehash = $row['password'];

         
            if(!password_verify($password, $dehash)){
              echo "<center class='alert alert-warning'>Password Incorrect!</center>";
            }else {
              /*start the sessions*/

                  $_SESSION['user_id'] = $row["user_id"];
                  $_SESSION['first_name'] = $row["first_name"];
  
                  //$name = $_SESSION['ADMIN_NAME'];
  
                  echo '<script>window.location.href="my-orders?login=success&'.$_SESSION['first_name'].'";</script>';
  
              /*endd the sessions*/
  
          
  
            }

        }

      }