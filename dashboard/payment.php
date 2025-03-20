<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="SA Books Online">
<meta name="SA Books Online" content="ATFN">
<!-- css file -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/ace-responsive-menu.css">
<link rel="stylesheet" href="css/menu.css">
<link rel="stylesheet" href="css/fontawesome.css">
<link rel="stylesheet" href="css/flaticon.css">
<link rel="stylesheet" href="css/bootstrap-select.min.css">
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/slider.css">
<link rel="stylesheet" href="css/jquery-ui.min.css">
<link rel="stylesheet" href="css/magnific-popup.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/ud-custom-spacing.css">
<link rel="stylesheet" href="css/dashbord_navitaion.css">
<!-- Responsive stylesheet -->
<link rel="stylesheet" href="css/responsive.css">            

<link rel="stylesheet" href="sweetalert2.min.css">
<!-- Title -->
<title>Online Payment</title>
<!-- Favicon -->
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" />
<!-- Apple Touch Icon -->
<link href="images/apple-touch-icon-60x60.png" sizes="60x60" rel="apple-touch-icon">
<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
<link href="images/apple-touch-icon-180x180.png" sizes="180x180" rel="apple-touch-icon">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="https://jsuites.net/v4/jsuites.js"></script>
<script src="https://jsuites.net/v4/jsuites.webcomponents.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />


<style>
    .dashboard_sidebar_list .sidebar_list_item a:hover,
.dashboard_sidebar_list .sidebar_list_item a:active,
.dashboard_sidebar_list .sidebar_list_item a:focus,
.dashboard_sidebar_list .sidebar_list_item a.web{
  background-color: #222222;
  color: #ffffff;
}
</style>
</head>
<body>
<div class="wrapper">
  <div class="preloader"></div>
  
  <!-- Main Header Nav -->
  <?php include 'includes/header-dash-main.php';?>

  <div class="dashboard_content_wrapper">
    <div class="dashboard dashboard_wrapper pr30 pr0-xl">

      <!-- MAIN DASHBOARD HEADER--->
      <?php include 'includes/header-dash.php';?>
      <!-- MAIN DASHBOARD HEADER--->


      <div class="dashboard__main pl0-md">
        <div class="dashboard__content hover-bgc-color">
          <div class="row pb40">


            <?php include 'includes/mobile-guide.php';?>


            <?php

            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            if(!isset($_GET['plan']) || !isset($_GET['current'])){
              //header('Location: page-dashboard');
            } else {
              $subscriptionType = $_GET['plan'];
              $current = $_GET['current'];
            }

            include '../includes/database_connections/sabooks.php';

            $plan = $_SESSION['ADMIN_SUBSCRIPTION'];
            $userkey = $_SESSION['ADMIN_USERKEY'];

            include '../includes/backend/scripts/select/select_subscriptions.php';

            // Subscription downgrade conditions (you can optimize this with arrays)
              if ($current == 'Free') {
                if ($subscriptionType == 'Standard' || $subscriptionType == 'Premium' || $subscriptionType == 'Deluxe') {
                    $headMessage = "You are about to upgrade from <b>Free</b> Plan to <b>$subscriptionType</b>";
                }
            } elseif ($current == 'Standard') {
                if ($subscriptionType == 'Premium' || $subscriptionType == 'Deluxe') {
                   $headMessage = "You are about to upgrade from <b>Standard</b> Plan to <b>$subscriptionType</b>";
                }
            } elseif ($current == 'Premium') {
                if ($subscriptionType == 'Deluxe') {
                    $headMessage = "You are about to upgrade from <b>Premium</b> Plan to <b>$subscriptionType</b>";
                }
            } elseif ($current == 'Deluxe') {
              if ($subscriptionType == 'Standard' || $subscriptionType == 'Premium' || $subscriptionType == 'Free') {
                  $headMessage = "You are about to Downgrade from <b>Premium</b> Plan to <b>$subscriptionType</b>";
              }
            } elseif ($current == 'Premium') {
              if ($subscriptionType == 'Standard' || $subscriptionType == 'Free') {
                  $headMessage = "You are about to Downgrade from <b>Premium</b> Plan to <b>$subscriptionType</b>";
              }
            } elseif ($current == 'Standard') {
              if ($subscriptionType == 'Free') {
                  $headMessage = "You are about to Downgrade from <b>Premium</b> Plan to <b>$subscriptionType</b>";
              }
            }


           include '../includes/backend/scripts/functions/generate_invoice_pre.php';



            ?>
           
             <!-- Shop Order Area -->
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="order_complete_message text-center">
              <div class="icon bgc-thm4 bg-white"><span class="fa fa-check text-thm"></span></div>
              <h2 class="title">You are about to switch your service plan</h2>
              <p class="text">Thank you. Your order has been received.</p>
            </div>
          </div>
        </div>

        <?php
        $totalPrice = number_format($totalPrice, 2);

        $amount = $totalPrice;

        ?>
        <div class="row">
          <div class="col-xl-8 offset-xl-2">
            <div class="shop_order_box mt60">
              <div class="order_list_raw">
                <ul class="d-md-flex align-items-center justify-content-md-between p-0 mb-0">
                  <li class="mb20-sm">
                    <p class="text mb5">Order Number</p>
                    <h6 class="mb-0">#<?php echo $invoiceNumber;?></h6>
                  </li>
                  <li class="mb20-sm">
                    <p class="text mb5">Date</p>
                    <h6 class="mb-0"><?php echo $invoiceDate;?></h6>
                  </li>
                  <li class="mb20-sm">
                    <p class="text mb5">Due Today</p>
                    <h6 class="mb-0">R<?php echo $totalPrice;?></h6>
                  </li>
                  <li>
                    <p class="text mb5">Payment Method</p>
                    <h6 class="mb-0">PayFast</h6>
                  </li>
                </ul>
              </div>

             

              <div class="order_details default-box-shadow1">
                <h4 class="title mb25">Order details</h4>
                <div class="od_content">
                  <ul class="p-0 mb-0">
                    <li class="bdrb1 mb20"><h6>Service Plan <span class="float-end">Amounts</span></h6></li>
                    <li class="mb20"><p class="body-color"><?php echo $subscriptionType;?> <span class="float-end">R<?php echo $totalPrice;?></span></p></li>

                   

                    <li><h6>Total <span class="float-end">R<?php echo $totalPrice;?></span></h6></li>
                  </ul>
                </div>
              </div>

              <hr>
              <div class="text-center"><?php 
              
              if($subscriptionType == 'Free'){

                echo ' <div class="d-grid">
                <a data-target="Free" class="ud-btn btn-thm-border bdrs8 choose-plan">Switch Service Plan<i class="fal fa-arrow-right-long"></i></a>
              </div>';

              } else {
                include '../includes/payment/submission.php';

              }
              
              ?></div>
            </div>            
          </div>
        </div>
      </div>

        </div>



        

      <!-- MAIN DASHBOARD HEADER--->
      <?php include 'includes/footer-dash.php';?>
      <!-- MAIN DASHBOARD HEADER--->
      </div>
    </div>
  </div>
  <a class="scrollToHome" href="#"><i class="fas fa-angle-up"></i></a>
</div>
<!-- Wrapper End -->
<script src="js/jquery-3.6.4.min.js"></script> 
<script src="js/jquery-migrate-3.0.0.min.js"></script> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/bootstrap-select.min.js"></script> 
<script src="js/jquery.mmenu.all.js"></script> 
<script src="js/ace-responsive-menu.js"></script> 
<script src="js/jquery-scrolltofixed-min.js"></script>
<script src="js/dashboard-script.js"></script>
<!-- Custom script for all pages --> 
<script src="js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

      $("#submit").on('submit',(function(e) {
        e.preventDefault();

        //let value = $("#reg_load").value();
    
        $("#reg_load").html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
        
        //showSwal('success-message');
            $.ajax({
                    url: "../includes/backend/scripts/website-backup.php",
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                        cache: false,
                    processData:false,
                    success: function(data)
                {
                    $("#reg_load").html('Create Your Website');
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


<script>
document.querySelector('#color').addEventListener('onchange', function(a,b,c) {
    // Set the font color
    e.target.children[0].style.color = this.value;
    // Show on console
    console.log('New value:' + this.value);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$('.choose-plan').click(function(){
    let contentid = $(this).attr('data-target');

    $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

    $(this).load('../includes/backend/scripts/subscription.php?subscription_type='+contentid);
    //alert(contentid);
})

</script>
</body>


</html>