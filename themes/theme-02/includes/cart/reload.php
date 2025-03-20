<?php

session_start();

    include '../db.php';

    if (!isset($_SESSION['user_id'])) {
        echo "0";
    }else {
        $userkey = $_SESSION['user_id'];$rows_query =mysqli_query($con, "SELECT COUNT(*) as number_rows FROM product_order WHERE product_current = 'cart' AND user_id = '$userkey';");
        $rows = mysqli_fetch_assoc($rows_query);echo $rows['number_rows'];
    }
                                                            ?>