<?php
        if(!isset($_POST['submit'])){

               //DATABASE CONNECTIONS SCRIPT
                include '../../dbh.inc.php';

                $city = mysqli_real_escape_string($conn, $_POST['city']);

                $sql = "SELECT * FROM brand WHERE BRAND = '$city';";

                
        if(mysqli_num_rows(mysqli_query($conn, $sql))){
            echo "<div class='text-warning'>The Brand <b>'$city'</b> already exists!</div>";

          }else {
            
            $sql = "INSERT INTO brand ( BRAND ) VALUES ('$city');";
                
            if(mysqli_query($conn, $sql)){
                echo "<p class='text-success'>The Brand <b>'$city </b>  Has Been Added!</p>";
            }else{
                echo "<p class='text-warning'>Brand Has Failed To Be published!</p>";
            }

            }
        }else{
            header("Location: https://my.sabooksonline.co.za/");
        }
  

     
?>