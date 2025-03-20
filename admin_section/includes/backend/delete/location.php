<?php
        if(!isset($_POST['submit'])){

               //DATABASE CONNECTIONS SCRIPT
                include '../../dbh.inc.php';

                $city = mysqli_real_escape_string($conn, $_POST['city']);

                $sql = "SELECT * FROM brand WHERE BRAND = '$city';";

                
        if(mysqli_num_rows(mysqli_query($conn, $sql))){
            $sqls = "DELETE FROM brand WHERE BRAND = '$city';";
                
            if(mysqli_query($conn, $sqls)){
                echo "<p class='text-success'>The Brand <b>'$city'</b> has been deleted!</p>";
            }else{
                echo "<p class='text-warning'>We could not delete <b>'$city'</b></p>";
            }

            

          }
           
        }else{
            header("Location: https://my.sabooksonline.co.za/");
        }
  

     
?>