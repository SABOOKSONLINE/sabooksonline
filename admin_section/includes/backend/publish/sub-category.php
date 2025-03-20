<?php
        if(!isset($_POST['submit'])){

               //DATABASE CONNECTIONS SCRIPT
                include '../../dbh.inc.php';

                $sub = mysqli_real_escape_string($conn, $_POST['sub']);
                $category = mysqli_real_escape_string($conn, $_POST['category']);

                $category = str_replace(' ', '_', $category);

                $sql = "SELECT * FROM $category WHERE SUBCATEGORY = '$sub';";

                
        if(mysqli_num_rows(mysqli_query($conn, $sql))){
            echo "<div class='text-warning'>The category <b>'$sub'</b> already exists!</div>";

          }else {
            
            $sql = "INSERT INTO $category ( SUBCATEGORY ) VALUES ('$sub');";
                
            if(mysqli_query($conn, $sql)){

                    echo "<p class='text-success'>Category <b>'$category'</b>  Has Been Added!</p>";
              
            }else{
                echo "<p class='text-warning'>Category Has Failed To published!</p>";
            }

            }
        }else{
            header("Location: https://my.sabooksonline.co.za/");
        }
  

     
?>