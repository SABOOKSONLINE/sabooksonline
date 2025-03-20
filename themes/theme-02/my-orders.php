
<?php session_start(); ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);?>
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
  

                                 <!-- cart-main-area start -->
                                <div class="wishlist-area section-padding--lg bg__white">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="wishlist-content">
                                                    <form action="#">
                                                        <div class="wishlist-table wnro__table table-responsive">
                                                            <table>
                                                                <thead>
                                                                <tr>
                                                                    
                                                                    <th class="product-name p-3"><span class="nobr">Order Number</span></th>
                                                                    <th class="product-price"><span class="nobr"> Date </span></th>
                                                                    <th class="product-price"><span class="nobr"> Total </span></th>
                                                                    <th class="product-stock-stauts"><span class="nobr"> Status
                                                                                </span></th>
                                                                    <th class="product-add-to-cart"></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>



                        <?php
                                                //DATABASE CONNECTIONS SCRIPT
                                                include 'includes/db.php';


                                                if(!isset($_SESSION['user_id'])){

                                                    header("Location: my-account?login");

                                                } else {

                                                $userid = $_SESSION['user_id'];

                                                $sql = "SELECT * FROM invoices WHERE invoice_user = '$userid' ORDER BY invoice_id DESC";
                                                $result = mysqli_query($con, $sql);
                                                    if(mysqli_num_rows($result) == false){
                                                        echo '<p class="alert alert-info">No orders have been made yet.  <a href="index#shop">Click here to order</a></p>';
                                                    }else{
                                                    while($row = mysqli_fetch_assoc($result)) {

                                                        $status = ['invoice_status'];

                                                        if($status = 'COMPLETED'){
                                                            $status = '<p class="text-success pl-5">Completed</p>';
                                                        } else {
                                                            $status = '<p class="text-warning pl-5">Pending</p>';
                                                        }

                                                        echo ' <tr>
                                                        <td class="product-name p-3"><a href="#">#'.$row['invoice_number'].'</a></td>
                                                        <td class="product-name p-3"><a href="#">'.$row['invoice_date'].'</a></td>
                                                        <td class="product-price"><span class="amount">R'.$row['invoice_total'].'</span></td>
                                                        <td class="product-stock-status">'.$status.'</td>
                                                        <td class="product-add-to-cart"><a href="orders?invoice='.$row['invoice_number'].'"> View Information</a></td>
                                                    </tr>';
                                                    }
                                                }
                                            }
                                            ?>
                            

                                                               
                                                                
                                                            
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </form>
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