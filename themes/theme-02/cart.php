
<?php 
// API SCRIPT FOR FETCHING DATA FROM SABOOKS ONLINE
$fileContent = file_get_contents('includes/api_key.txt');
include 'includes/api_fetch.php';
?>

<?php for ($i = 0; $i < count($title); $i++): ?>

<!doctype html>
<html class="no-js" lang="zxx">


<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Shopping Cart </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicons -->

    <!-- Google font (font-family: 'Roboto', sans-serif; Poppins ; Satisfy) -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,600i,700,700i,800"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/plugins.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Cusom css -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- Modernizer js -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>

    <style>
        header {
            background-color: #f3f3f3 !important;
            marging-bottom: 1%;
        }

        li a {
            color: #333 !important;
        }
    </style>
</head>

<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade
    your browser</a> to improve your experience and security.</p>
<![endif]-->

<!-- Main wrapper -->
<div class="wrapper" id="wrapper">

    <!-- Header -->
    <?php include 'includes/header.php';?>
    <!-- End Search Popup -->
    <br><br>
    <!-- cart-main-area start -->
    <div class="cart-main-area section-padding--lg bg--white">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 ol-lg-12">
                    <form action="#">
                        <div class="table-content wnro__table table-responsive">
                            <table>
                                <thead>
                                <tr class="title-top">
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                                </thead>
                                <tbody>

                                 
                        <?php
                                                //DATABASE CONNECTIONS SCRIPT
                                                include 'includes/db.php';

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
                                                        <td class="product-thumbnail"><a href="#"><img
                                                                src="https://sabooksonline.co.za/cms-data/book-covers/'.$row['product_image'].'" alt="product img" width="50px"></a></td>
                                                        <td class="product-name"><a href="#">'.$row['product_title'].'</a></td>
                                                        <td class="product-price"><span class="amount">R'.$row['product_price'].'</span></td>
                                                        <td class="product-quantity"><input type="number" value="'.$row['product_quantity'].'"></td>
                                                        <td class="product-subtotal">R'.$row['product_total'].'</td>
                                                        <td class="product-remove remove" data-target="'.$row['product_id'].'"><a class="remove-icon">X</a></td>
                                                    </tr>';
                                                    }
                                                }
                                            }
                                            ?>

                                
                                <!--<tr>
                                    <td class="product-thumbnail"><a href="#"><img
                                            src="images/product/sm-3/2.jpg" alt="product img"></a></td>
                                    <td class="product-name"><a href="#">Quisque fringilla</a></td>
                                    <td class="product-price"><span class="amount">$50.00</span></td>
                                    <td class="product-quantity"><input type="number" value="1"></td>
                                    <td class="product-subtotal">$50.00</td>
                                    <td class="product-remove"><a href="#">X</a></td>
                                </tr>-->
                                
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-6">
                    <div class="cartbox__total__area">
                        <div class="cartbox-total d-flex justify-content-between">
                            <ul class="cart__total__list">
                                <li>Sub-total</li>
                                <li>Shipping</li>
                            </ul>
                            <ul class="cart__total__tk">
                            <li>  <?php
                                                //DATABASE CONNECTIONS SCRIPT
                                                include 'includes/db.php';
                                                $userid = $_SESSION['user_id'];
                                                $id = $_SESSION['user_id'];
                                                $result = mysqli_query($con, "SELECT SUM(product_total) AS value_sum FROM product_order WHERE product_current = 'cart' AND user_id = '$userid';"); 
                                                $row = mysqli_fetch_assoc($result); 
                                                $sum = $row['value_sum'];

                                                echo 'R'.$sum.'';
                                            ?></li>
                                <li><?php echo $books[$i]?></li>
                                
                            </ul>
                        </div>
                        <div class="cart__total__amount">
                            <span>Grand Total</span>
                            <span>R<?php echo $sum + $books[$i];?></span>
                        </div>
                        <hr class="mt-3" >
                        <div class="mt-3" id="payfast"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cart-main-area end -->
    <!-- Footer Area -->
    <?php include 'includes/footer.php';?>
    <!-- //Footer Area -->

</div>
<!-- //Main wrapper -->

<!-- JS Files -->
<script src="js/vendor/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/active.js"></script>

<script>
        $(".remove").click(function(){
            //$(this).html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
            let data = $(this).attr("data-target");

            $(this).load("includes/cart/remove.php?contentid="+data);

            //alert(data);
        })

        $(".minus").click(function(){
            //$(this).html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
            let data = $(this).value();
            //let quantity = $(this).attr("data-quantity");

            //let value = $('#1').value();

            //$(this).load("includes/cart/minus.php?contentid="+data+"&quantity="+quantity);

            alert(data);
        })
    
    
      $("#payfast").load("includes/payment/submission.php?amount=<?php echo $sum ?>&invoice=<?php echo $id ?>");

      $('#reload').load('includes/cart/reload.php');

      setInterval(function(){
      $('#reload').load('includes/cart/reload.php');
        },5000);

    </script>

</body>


</html>

<?php endfor; ?>