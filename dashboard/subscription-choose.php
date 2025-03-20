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
.dashboard_sidebar_list .sidebar_list_item a.subscriptions-1{
  background-color: #222222;
  color: #ffffff;
}


/* WebKit-based browsers (Chrome, Safari) */
::-webkit-scrollbar {
    width: 12px; /* Width of the scrollbar track */
}

::-webkit-scrollbar-thumb {
    background-color: #888; /* Color of the scrollbar thumb */
    border-radius: 6px; /* Rounded corners for the thumb */
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

            $plan = $_GET['plan'];
            $userkey = $_SESSION['ADMIN_USERKEY'];

            include '../includes/backend/scripts/select/select_subscriptions.php';


            ?>
           
             <!-- Shop Order Area -->
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="order_complete_message text-center">
              <div class="icon bgc-thm4 bg-white"><span class="fa fa-check text-thm"></span></div>
              <h2 class="title">You are about to switch your service plan</h2>
              <p class="text">Your chose a <b><?php echo $_GET['type'];?></b> subscription option, meaning you will be billed <b><?php echo $_GET['type'];?></b>.</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-8 offset-xl-2">
            <div class="shop_order_box mt60">
              <div class="order_list_raw">
                <ul class="d-md-flex align-items-center justify-content-md-between p-0 mb-0">
                  <li class="mb20-sm">
                    <p class="text mb5">Order Number</p>
                    <h6 class="mb-0">#</h6>
                  </li>
                  <li class="mb20-sm">
                    <p class="text mb5">Subscription Date</p>
                    <h6 class="mb-0"><?php echo date("Y-m-d");?></h6>
                  </li>
                  <li class="mb20-sm">
                    <p class="text mb5">Renewal Date</p>
                    <h6 class="mb-0"><?php 

                    if($_GET['type'] == 'yearly'){
                      $cycle = '+1 year';
                      $price = $sub_yearly * 80/100;
                      $frequency = 6;
                    }elseif($_GET['type'] == 'monthly'){
                      $cycle = '+1 month';
                      $price = $sub_price;
                      $frequency = 3;
                    }
                    
                    $next_invoice_date = date("Y-m-d", strtotime($cycle));
                    
                    echo $next_invoice_date;
                    
                    ?></h6>
                  </li>
                  <li class="mb20-sm">
                    <p class="text mb5"><?php echo ucwords($_GET['type']);?> Subscription</p>
                    <h6 class="mb-0">R<?php echo $price;?></h6>
                  </li>
                  <li>
                    <p class="text mb5">Payment Method</p>
                    <h6 class="mb-0">PayFast</h6>
                  </li>
                </ul>
              </div>

             

              <div class="order_details default-box-shadow1">
                <h4 class="title mb25 text-center">Subscription Agreement</h4>
                <div class="od_content" style="height: 400px;overflow: scroll;">

                <p>Online Subscription Agreement</p>

                <p>This Online Subscription Agreement (the "Agreement") is entered into by and between:</p>

                <p><strong>SA Books Online (PTY) LTD</strong>, a company registered under the laws of <b>South Africa</b>, with its principal place of business located at <b>68 Melville Road, Illovo Point 13th Floor, Illovo Sandton</b> ("Company" or "We" or "Us" or "Our").</p>

                <p>And,</p>

                <p><b><?php echo $_SESSION['ADMIN_NAME'];?></b>, an individual/entity residing/registered at <b><?php echo $_SESSION['ADMIN_GOOGLE'];?></<b> ("Subscriber" or "You" or "Your").</p>

                <p>1. Subscription Services</p>

                <p> 1.1. Company provides subscription-based services ("Services") as described on its website <b>www.sabooksonline.co.za</b>.</p>

                <p>1.2. By subscribing to the Services, Subscriber agrees to comply with and be bound by the terms and conditions of this Agreement.</p>

                <p>2. Subscription Plan and Payment</p>

                <p>2.1. Subscriber shall select a subscription plan, which may include, but is not limited to, the following details:</p>

                <p>Subscription Name: <b><?php echo $_SESSION['ADMIN_NAME'];?></b></p>
                <p>Subscription Cycle: <b><?php echo ucwords($_GET['type']);?></b></p>
                <p>Subscription Fee: <b>R<?php echo $price;?><b></p>
                <p>2.2. Subscriber shall pay the subscription fee in advance using the payment method specified on the Company's website.</p>

                <p>3. Subscription Term and Renewal</p>

                <p>3.1. The initial subscription term shall commence upon payment and continue for the duration specified in the selected subscription plan.</p>

                <p>3.2. Unless Subscriber cancels the subscription in accordance with Section 4, the subscription will automatically renew for successive terms of the same duration as the initial term.</p>

                <p>4. Cancellation and Termination</p>

                <p>4.1. Subscriber may cancel the subscription by following the cancellation process outlined on the Company's website.</p>

                <p>4.2. Company reserves the right to terminate or suspend the subscription in case of any violation of this Agreement or for other valid reasons, at its sole discretion.</p>

                <p>5. Refunds</p>

                <p>5.1. Refund policies, if applicable, are specified on the Company's website.</p>

                <p>6. Privacy and Data</p>

                <p>6.1. Subscriber data is subject to the Company's Privacy Policy available at <a href="https://secure.sabooksonline.co.za/privacy-policy" target="_blank">https://secure.sabooksonline.co.za/privacy-policy</a>.</p>

                <p>7. Intellectual Property</p>

                <p>7.1. Any intellectual property rights associated with the Services, including but not limited to copyrights and trademarks, are owned by the Company.</p>

                <p> 8. Limitation of Liability</p>

                <p>8.1. Company's liability is limited to the extent provided in the Terms and Conditions, available on the Company's website.</p>

                <p>9. Governing Law</p>

                <p>9.1. This Agreement shall be governed by and construed in accordance with the laws of South Africa.</p>

                <p>10. Entire Agreement</p>

                <p> 10.1. This Agreement constitutes the entire agreement between the parties and supersedes all prior agreements and understandings, whether oral or written.</p>

                <p>11. Contact Information</p>

                <p>11.1. If you have any questions or concerns about this Agreement, please contact us at admin@sabooksonline.co.za.</p>

                <p>By subscribing to our Services, you acknowledge that you have read, understood, and agreed to be bound by this Agreement.</p>

                <p>Subscriber's Full Name: <b><?php echo $_SESSION['ADMIN_NAME'];?></b></p>

                <p>Date: <b><?php echo date("Y-m-d");?></b></p>
                  
                </div>
              </div>

              <hr>

              <p class="text text-center">You can make your payment by choosing a payment method either <b>PayFast</b> or <b>Yoco</b>.</p>
              <div class="text-center">
              <?php 
              
              if($plan == 'Free'){
                
                $_SESSION['payment'] = 'true';
                $_SESSION['subscription'] = 'Free';

                // You can perform additional actions here if needed

                echo '<a class="ud-btn btn-thm mt-2" href="../includes/subscriptions/verify_payment_free">Accept & Subscribe to Free Plan</a>';

              }else{

                $_SESSION['yoco_amount'] = $price;

                include '../includes/subscriptions/payment.php';  


                echo '<p class="text text-center">OR</p>';
                echo '<hr>

                <div class="d-flex justify-content-center"><a class="ud-btn btn-thm mt-2" href="../includes/yoco_payment/payment.php">Pay With Yoco </a><img src="../img/yoco.svg" style="margin-left: 2% !important" width="160px"></div>';
              }
              
              ?>

              
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