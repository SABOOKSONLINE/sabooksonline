<?php
        if(!isset($_POST['submit'])){

               //DATABASE CONNECTIONS SCRIPT
              include '../../../includes/database_connections/sabooks.php';

                $category = mysqli_real_escape_string($conn, $_POST['category']);

                $sql = "SELECT * FROM category WHERE category = '$category';";

                
        if(mysqli_num_rows(mysqli_query($conn, $sql))){
            echo "<div class='text-warning'>The category <b>'$category'</b> already exists!</div>";

          }else {
            
            $sql = "INSERT INTO category ( CATEGORY ) VALUES ('$category');";
                
            if(mysqli_query($conn, $sql)){

                $category = str_replace(' ', '_', $category);

                $createTable = "CREATE TABLE $category (
                    ID int(225) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    SUBCATEGORY text NOT NULL
                  );";
        
                  if(mysqli_query($conn, $createTable)){
                    echo "<p class='text-success'>Category <b>'$category'</b>  Has Been Added!</p>";
                  }else{
                    echo "<p class='text-warning'>Category Has Failed To published!</p>"; 
                  }
                
            }else{
                echo "<p class='text-warning'>Category Has Failed To published!</p>";
            }

            }
        }else{
            header("Location: https://my.sabooksonline.co.za/");
        }
  

     
?>