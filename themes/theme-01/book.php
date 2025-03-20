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
    <title>Details</title>
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
    <!-- Start main Content -->
    <div class="maincontent bg--white pt--80 pb--55">
        <div class="container">
        <form id="submit">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="wn__single__product">
                        <div class="row">

                        <?php 
                                include 'includes/db.php';

                                if(!isset($_GET['name'])){
                                    header("Location: shop?redirect");
                                } else {

                                    $product = str_replace('-',' ', $_GET['name']);

                                    $sql = "SELECT * FROM products WHERE product_title = '$product';";
                                    $result = mysqli_query($con, $sql);
                                    if(mysqli_num_rows($result) == false){

                                        echo "<script>window.location.replace('index?not-found');</script>";

                                    }else{

                                        while($row = mysqli_fetch_assoc($result)) {

                                            $name = $row['product_title'];
                                            $image = $row['product_image'];
                                            $price = $row['product_price'];
                                            $desc = $row['product_desc'];
                                            $id = $row['product_id'];
                                                                        
                                        }
                                    }
                                }
                            ?>
                            <div class="col-lg-4 col-12">
                                <div class="wn__fotorama__wrapper">
                                    <div class="fotorama wn__fotorama__action" data-nav="thumbs">
                                        <a href="#"><img src="https://sabooksonline.co.za/cms-data/book-covers/<?php echo $image;?>" alt="" style="width: 300px !important;border-radius:10px !important"></a>
                                    </div>
                                </div>
                            </div>


                            <input type="hidden" id="1" value="<?php echo $id;?>" name="contentid"/>


                            <div class="col-lg-8 col-12">
                                <div class="product__info__main">
                                    <h1><?php echo $name;?></h1>
                                    <div class="product-reviews-summary d-flex">
                                        <ul class="rating-summary d-flex">
                                            <li><i class="zmdi zmdi-star-outline"></i></li>
                                            <li><i class="zmdi zmdi-star-outline"></i></li>
                                            <li><i class="zmdi zmdi-star-outline"></i></li>
                                            <li class="off"><i class="zmdi zmdi-star-outline"></i></li>
                                            <li class="off"><i class="zmdi zmdi-star-outline"></i></li>
                                        </ul>
                                    </div>
                                    <div class="price-box">
                                        <span>R<?php echo $price;?></span>
                                    </div>
                                    <div class="product__overview">
                                        <p><?php echo $desc;?></p>
                                    </div>
                                    <div class="box-tocart d-flex">
                                        <span>Qty</span>
                                        <input id="qty" class="input-text qty" name="quantity" min="1" value="<?php 

                                                if(isset($_SESSION['user_id'])){

                                                    $userid = $_SESSION['user_id'];

                                                    $sqls = "SELECT * FROM product_order WHERE user_id = '$userid' AND product_id = '$id' AND product_current='cart';";
                                                    $results = mysqli_query($con, $sqls);
                                                    if(mysqli_num_rows($results) == false){
                                                        echo '1';
                                                    }else{
                                                        while($rows = mysqli_fetch_assoc($results)) {
                                                            echo $rows['product_quantity'];
                                                        }
                                                    }

                                                } else {

                                                    echo '1';

                                                }



                                                ?>"
                                               title="Qty" type="number">
                                        <div class="addtocart__actions">

                                        <?php 

                                            if(isset($_SESSION['user_id'])){

                                                $userid = $_SESSION['user_id'];

                                                $sqlss = "SELECT * FROM product_order WHERE user_id = '$userid' AND product_id = '$id' LIMIT 1;";
                                                $resultss = mysqli_query($con, $sqlss);
                                                if(mysqli_num_rows($resultss) == false){

                                                    echo ' <button class="tocart" type="submit" title="Add to Cart" id="reg_load" >Add to
                                                    Cart
                                                </button>';
                                                    
                                                }else{
                                                    while($rowss = mysqli_fetch_assoc($resultss)) {
                                                        echo '<button type="button" class="tocart remove" data-target="'.$rowss['product_id'].'">
                                                        Remove
                                                    </button>
                                                    
                                                    <button class="tocart" type="submit" title="Add to Cart" id="reg_load" >Update
                                            </button>';
                                                    }
                                                }

                                            } else {

                                                echo '<li class="cart"><a href="my-account" class="btn btn-dark text-white">Please Login</a></li>';

                                            }



                                            ?>
                                            
                                        </div>
                                        
                                       
                                    </div>

                                    <div class="product_meta">
											<span class="posted_in" id="reg_status"></span>
                                    </div>
                                    <div class="product-share">
                                        <ul>
                                            <li class="categories-title">Follow :</li>
                                            <li>
                                                <a href="#">
                                                    <i class="icon-social-twitter icons"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="icon-social-tumblr icons"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="icon-social-facebook icons"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="icon-social-linkedin icons"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
        <form>
        </div>
    </div>
    <!-- End main Content -->
    
    <!-- Footer Area -->
    <?php include 'includes/footer.php';?>
    <!-- END QUICKVIEW PRODUCT -->

</div>
<!-- //Main wrapper -->


<!-- JS Files -->
<script src="js/vendor/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/active.js"></script>

<script>
      $("#submit").on('submit',(function(e) {
        e.preventDefault();

        //let value = $("#reg_load").value();
    
        $("#reg_load").html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
        
        //showSwal('success-message');
            $.ajax({
                    url: "includes/cart/add-inner.php",
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                        cache: false,
                    processData:false,
                    success: function(data)
                {
                    $("#reg_load").html('Update');
                    $("#reg_status").html(data);
                    },
                error: function(){}
                });

            }));
        
   
            $('#reload').load('includes/cart/reload.php');

            setInterval(function(){
            $('#reload').load('includes/cart/reload.php');
            },5000);


            $(".remove").click(function(){
            $(this).html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
            let data = $(this).attr("data-target");

            $(this).load("includes/cart/remove.php?contentid="+data);

            //alert(data);
            })
        </script>

</body>

</html>

<?php endfor; ?>