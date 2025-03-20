<tbody id="cart">
<?php

session_start();
                                                //DATABASE CONNECTIONS SCRIPT
                                                include '../db.php';

                                                if(!isset($_SESSION['user_id'])){

                                                    //header("Location: my-account?login");

                                                    echo '<script>window.location.href = "my-account?redirect";</script>';

                                                } else {

                                                $userid = $_SESSION['user_id'];

                                                $sql = "SELECT * FROM product_order WHERE product_current = 'cart' AND user_id = '$userid';";
                                                $result = mysqli_query($con, $sql);
                                                    if(mysqli_num_rows($result) == false){
                                                    }else{
                                                    while($row = mysqli_fetch_assoc($result)) {

                                                        echo '<tr>
                                                        <td>
                                                            <div class="product-box">
                                                                <div class="img-box">
                                                                    <img src="store/product_images/'.$row['product_image'].'" alt="">
                                                                </div>
                                                                <h3><a href="product?name='.strtolower(str_replace(' ','-',$row['product_title'])).'">'.$row['product_title'].'</a></h3>
                                                            </div>
                                                        </td>
                                                        <td>R'.$row['product_price'].'</td>
                                                        <td>
                                                            <div class="quantity-box">
                                                                <button type="button" class="sub"><i class="fa fa-minus"></i></button>
                                                                <input type="number" id="product-1" value="'.$row['product_quantity'].'" />
                                                                <button type="button" class="add"><i class="fa fa-plus"></i></button>
                                                            </div>
                                                        </td>
                                                        <td>
                                                        R'.$row['product_total'].'
                                                        </td>
                                                        <td>
                                                            <div class="cross-icon remove" data-target="'.$row['product_id'].'">
                                                                <i class="icon-close remove-icon"></i>
                                                            </div>
                                                        </td>
                                                    </tr>';
                                                    }
                                                }
                                            }
                                            ?> </tbody>