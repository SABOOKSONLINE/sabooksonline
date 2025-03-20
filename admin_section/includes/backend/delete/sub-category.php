<?php
        if(!isset($_POST['submit'])){

               //DATABASE CONNECTIONS SCRIPT
                include '../../dbh.inc.php';

                $sub = mysqli_real_escape_string($conn, $_POST['sub']);
                $category = mysqli_real_escape_string($conn, $_POST['category']);

                $sql = "SELECT * FROM $category WHERE SUBCATEGORY = '$sub';";

                $category = str_replace(' ', '_', $category);

                
        if(mysqli_num_rows(mysqli_query($conn, $sql))){
            $sqls = "DELETE FROM $category WHERE SUBCATEGORY = '$sub';";
                
            if(mysqli_query($conn, $sqls)){
                echo "<p class='text-success'>The <b>'$sub'</b> has been deleted!</p>";
            }else{
                echo "<p class='text-warning'>We could not delete <b>'$sub'</b></p>";
            }

          }
           
        }else{
            header("Location: https://my.sabooksonline.co.za/");
        }
  

     
?>