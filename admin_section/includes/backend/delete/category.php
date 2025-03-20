<?php
        if(!isset($_POST['submit'])){

               //DATABASE CONNECTIONS SCRIPT
            include '../../../includes/database_connections/sabooks.php';

                $category = mysqli_real_escape_string($conn, $_POST['category']);

                $sql = "SELECT * FROM category WHERE CATEGORY = '$category';";

                
        if(mysqli_num_rows(mysqli_query($conn, $sql))){
            $sqls = "DELETE FROM category WHERE CATEGORY = '$category';";
                
            if(mysqli_query($conn, $sqls)){
                echo "<p class='text-success'>The <b>'$category'</b> has been deleted!</p>";
            }else{
                echo "<p class='text-warning'>We could not delete <b>'$category'</b></p>";
            }

            

          }
           
        }else{
            header("Location: https://my.sabooksonline.co.za/");
        }
  

     
?>