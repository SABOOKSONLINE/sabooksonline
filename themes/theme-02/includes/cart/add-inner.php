<?php

ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-warning mb-3'>You need to log in to add to cart!<a href='../../my-account'>login Here</a></div>";
       
}else {
    $current_user = $_SESSION['user_id'];

    include_once '../db.php';

    $product_id = mysqli_real_escape_string($con, $_POST['contentid']);
    $quantity = mysqli_real_escape_string($con, $_POST['quantity']);

    $sql = "SELECT * FROM product_order WHERE product_id = '$product_id' AND user_id = '$current_user' AND product_current = 'cart';";

    if (mysqli_num_rows(mysqli_query($con, $sql))) {
        
        $sql = "SELECT * FROM product_order WHERE PRODUCT_ID = '$product_id';";
        $row = mysqli_fetch_assoc(mysqli_query($con, $sql));

        $image = $row['product_image'];
        $title = $row['product_title'];
        $price = $row['product_price'];
        $desc = $row['product_desc'];

        $category = $row['product_cat'];
        $sku = $row['product_cat'];
        $brand = $row['product_brand'];

        $total = $price * $quantity;

        $update = "UPDATE product_order SET product_image='$image',product_title='$title',product_price='$price',product_desc='$desc',product_quantity='$quantity',product_current='cart',product_cat='$category',product_total='$total'WHERE product_id='$product_id' AND user_id = '$current_user'";
        
        if (mysqli_query($con, $update)) {
            echo "Updated";
            
        }else {
            echo "<div class='alert alert-danger mb-3'>x</div>";
        }

    }else {

        
        $sql = "SELECT * FROM products WHERE product_id = '$product_id';";
         
        $row = mysqli_fetch_assoc(mysqli_query($con, $sql));

        $image = $row['product_image'];
        $title = $row['product_title'];
        $price = $row['product_price'];
        $desc = $row['product_desc'];
        $price = $row['product_price'];

        $category = $row['product_cat'];
        $sku = $row['product_cat'];
        $brand = $row['product_brand'];

        $total = $price * $quantity;

        $class = 'class';

        $sql ="INSERT INTO product_order (product_id, product_title, product_cat, product_brand, product_quantity, product_image, product_current, product_total, product_desc, user_id, product_price, invoice_number) VALUES('$product_id','$title','$category','$brand','$quantity','$image','cart','$total','$desc','$current_user','$price', '');";
    
        if (mysqli_query($con, $sql)) {
            echo "Added";
       
        }else {
            echo "<div class='alert alert-danger mb-3'>x</div>";
       
        }
    }
}

?>