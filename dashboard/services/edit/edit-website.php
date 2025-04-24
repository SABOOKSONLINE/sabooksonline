<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/database_connections/sabooks_plesk.php';

$userkey = $_SESSION['ADMIN_USERKEY'];

// Step 1: Retrieve data from the source table
$sqlSelect = "SELECT * FROM plesk_accounts WHERE USERKEY = '$userkey'";
$result = $mysqli->query($sqlSelect);

// Step 2: Insert or update the data in the destination table
if ($result->num_rows > 0) {
    // Loop through each row of the result
    while ($row = $result->fetch_assoc()) {
        $site_key = $row['SITE_MERCHANT_KEY'];
        $site_id = $row['SITE_MERCHANT_ID'];
        $site_number = $row['SITE_NUMBER'];
        $site_desc = $row['DESCRIPTION'];
        $site_domain = $row['DOMAIN'];
        $site_address = $row['SITE_ADDRESS'];       
        $site_title = $row['SITE_TITLE'];
        $site_email = $row['EMAIL'];
        $site_pass_phrase = $row['SITE_LONGITUDE'];
        $site_shipping = $row['SITE_BOOKS'];
        
    }
    //echo "Data transferred successfully!";
} else {
   // echo "No data found in the source table.";
}       

// Close the connection
$mysqli->close();
?>




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
<title>Edit Website</title>
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
           
            <form id="submit" class="row pb40">
            <div class="col-lg-7">
              <div class="dashboard_title_area">
                <h2>Edit Your Website</h2>
                <p class="text">You can create your website by filling in the relavant fields.</p>
              </div>
            </div>

          </div>

          <!-- Breadcumb Sections -->
          <div class="breadcumb-section pt-0">
            <div class="cta-employee-single cta-banner mx-auto maxw1700 pt120 pt60-sm pb120 pb60-sm bdrs16 position-relative d-flex align-items-center">
                <img class="service-v1-vector at-job bounce-x d-none d-xl-block" src="images/vector-img/vector-service-v1.png" alt="">
                <div class="container">
                <div class="row wow fadeInUp">
                    <div class="col-xl-7">
                    <div class="position-relative">
                        <h2>Your domain is <b><?php echo $site_domain;?></b></h2>
                        <p class="text">.</p>
                    </div>
                    <div class="advance-search-tab bgc-white p10 bdrs4 mt30">
                        <div class="row">
                            <div class="col-md-3 col-lg-2 col-xl-6">
                                <div class="text-center text-xl-start">
                                <button class="ud-btn btn-thm2 w-100 vam" type="submit">Create PayFast Account</button>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-2 col-xl-6">
                                <div class="text-center text-xl-start">
                                <button class="ud-btn btn-thm2 w-100 vam" type="submit">Request Help?</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>


            <?php 
            
            
            if($_SESSION['ADMIN_SUBSCRIPTION'] == 'Free'){

               
            } else {

                echo '<div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative show">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Payment Details</h5>
                </div>
               
                <div class="col-lg-12">
                  <div class="form-style1">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">PayFast Merchant ID</label>
                          <input type="text" class="form-control" name="merchant_id" value="'.$site_id.'" required>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">PayFast Merchant Key</label>
                          <input type="text" class="form-control" name="merchant_key" value="'.$site_key.'" required>
                        </div>
                      </div>

                      <div class="col-sm-4">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Security Passphrase</label>
                          <input type="text" class="form-control" name="passphrase" value="'.$site_pass_phrase.'" required>
                        </div>
                      </div>
                     
                    </div>
                </div>
                </div>
              </div>';
            }
            
            ?>

            
          
          <div class="row">
            <div class="col-xl-12">
              

              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Contact Details</h5>
                </div>
               
                <div class="col-lg-12">
                  <div class="form-style1">
                    <div class="row">
                      
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Phone Number</label>
                          <input type="text" class="form-control" name="number" value="<?php  echo $site_number; ?>" required>
                          <input type="hidden" class="form-control" name="profile" value="<?php  echo $_SESSION['ADMIN_PROFILE_IMAGE']?>"required>
                          <input type="hidden" class="form-control" name="domain" value="<?php echo $site_domain; ?>"required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Email Address</label>
                          <input type="text" class="form-control" name="email" value="<?php  echo $site_email;?>" required>
                        </div>
                      </div>

                      <div class="col-sm-12">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Physical Address</label>
                          <input type="text" class="form-control" name="address" value="<?php  echo $site_address; ?>" required>
                        </div>
                      </div>
                     
                    </div>
                  </div>
                </div>
              </div>

              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Shipping Information</h5>
                </div>
               
                <div class="col-lg-12">
                  <div class="form-style1">
                    <div class="row">

                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Standard Shipping Fee (R)</label>
                          <input type="number" class="form-control" name="shipping" placeholder="R195" value="<?php  echo $site_shipping; ?>" required>
                        </div>
                      </div>
                     
                    </div>
                  </div>
                </div>
              </div>

              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Description</h5>
                </div>
               
                <div class="col-lg-12">
                  <div class="form-style1">
                    <div class="row">
                     
                      <div class="col-md-12">
                        <div class="mb10">
                          <label class="heading-color ff-heading fw500 mb10">About Author</label>
                          <textarea cols="30" rows="6" name="desc" required><?php  echo $site_desc; ?></textarea>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>


              <div class="ps-widget bgc-white bdrs12 p30 mb30 position-relative d-none">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Choose theme color of your site</h5>
                </div>
                <div class="col-xl-12">
                  <div class="extra-service-tab">
                    <jsuites-color value="#009688" name="color"></jsuites-color>
                  </div>
                </div>
              </div>


              <div class="col-lg-3">
              <div class="text-lg-start">
                <button type="submit" class="ud-btn btn-dark default-box-shadow2" id="reg_load">Update Website<i class="fal fa-arrow-right-long"></i></button>

              </div>
            </div>

            <div id="reg_status"></div>

            </div>
          </div>

            </form>
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
                    url: "../includes/backend/scripts/website-update.php",
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
</body>


</html>