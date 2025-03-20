<?php
      session_start();
  
      if(!isset($_SESSION['ID'])){

        echo "<script>alert('Please Login before you can Add to cart');</script>";
      }else{

        $current_user = $_SESSION['user_id'];

        include_once '../db.php';

        $product_id = mysqli_real_escape_string($conn, $_POST['contentid']);
        $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

        $quantity = $quantity - 1;

        $total =  $price * $quantity;

            if($total == "0"){
              $total = $price;
            }
    
            $update = "UPDATE product_order SET product_quantity='$quantity', product_total='$total' WHERE product_id='$product_id' AND user_id = '$current_user'";
        
              if(mysqli_query($conn, $update)){
                echo $quantity." Items Updated";
            }else{
                echo "X";
            }
        }
