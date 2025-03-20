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

<link rel="stylesheet" href="sweetalert2.min.css">

<script src="https://jsuites.net/v4/jsuites.js"></script>
<script src="https://jsuites.net/v4/jsuites.webcomponents.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />
<!-- Responsive stylesheet -->
<link rel="stylesheet" href="css/responsive.css">
<!-- Title -->
<title>Subscriptions</title>
<!-- Favicon -->
<!-- Apple Touch Icon -->
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" />

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>  
  
<style>
    .dashboard_sidebar_list .sidebar_list_item a:hover,
.dashboard_sidebar_list .sidebar_list_item a:active,
.dashboard_sidebar_list .sidebar_list_item a:focus,
.dashboard_sidebar_list .sidebar_list_item a.subscriptions-1{
  background-color: #222222;
  color: #ffffff;
}

.choose-plan{cursor: pointer;}

.yearly {
  display: none; /* Hide yearly by default */
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
      <?php include 'includes/header-dash.php';?>
      <div class="dashboard__main pl0-md">
        <div class="dashboard__content hover-bgc-color">
          <div class="row pb40">
          <?php include 'includes/mobile-guide.php';?>
            <div class="col-lg-9">
            <?php include 'includes/suspension_status.php';?>
              <div class="dashboard_title_area">
                <h2>Subscription Plans</h2>
                <p class="text">You can manage, downgrade or upgrade your account here.</p>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="text-lg-end">
              <?php 
                $subscription_btn = $_SESSION['ADMIN_SUBSCRIPTION'];

                if ($subscription_btn == 'Standard') {
                    echo '<a href="page-dashboard-service-plan" class="ud-btn btn-warning default-box-shadow3 text-white">Standard Plan<i class="fa fa-star"></i></a>';
                } elseif ($subscription_btn == 'Premium') {
                    echo '<a href="page-dashboard-service-plan" class="ud-btn btn-success default-box-shadow2 text-white">Premium Plan<i class="fa fa-crown"></i></a>';
                } elseif ($subscription_btn == 'Deluxe') {
                    echo '<a href="page-dashboard-service-plan" class="ud-btn btn-dark default-box-shadow2 text-white">Deluxe Plan<i class="fa fa-certificate"></i></a>';
                } elseif ($subscription_btn == 'Free') {
                    echo '<a href="page-dashboard-service-plan" class="ud-btn btn-info default-box-shadow2 text-white">Free Plan<i class="fa fa-user"></i></a>';
                }
                ?>
              </div>
            </div>
          </div>
            <?php 
                if(isset($_GET['status'])){

                    if($_GET['status'] == 'message'){
                        echo '<div class="alert alert-info">You are <b>currently on the Free Plan</b>, to unlock more features you need to upgrade your plan by choosing any desired plan.</div>';
                    }
                    
                }
            ?>
          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="navtab-style1">
                <div class="row wow fadeInUp" data-wow-delay="200ms">
                    <div class="col-lg-12">
                        <div class="pricing_packages_top d-flex align-items-center justify-content-center mb60">
                        <div class="toggle-btn" id="cycle">
                            <span class="pricing_save1 dark-color ff-heading">Billed Monthly</span>
                            <label class="switch">
                            <input type="checkbox" id="checbox" onclick="checkPrice()"/>
                            <span class="pricing_table_switch_slide round"></span>
                            </label>
                            <span class="pricing_save2 dark-color ff-heading">Billed Yearly</span>
                            <span class="pricing_save3">20% OFF - Yearly</span>
                        </div>
                        </div>
                    </div>
        </div>
        <div class="row wow fadeInUp" data-wow-delay="300ms">

        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        include '../includes/database_connections/sabooks.php';
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbh);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $desired_id = 1; // Replace this with the desired ID value

        $plan = $_SESSION['ADMIN_SUBSCRIPTION'];

        $current_plan = $_SESSION['ADMIN_SUBSCRIPTION'];

        echo '<style> #'.$current_plan.' {border: #5BBB7B solid 4px;color:#fff !important;}#'.$current_plan.' h1 {color: #5BBB7B;} .active-plan {display: none;}#'.$current_plan.' .active-plan { display: block;}#'.$current_plan.' .choose-plans { display: none;} .</style>';

        // SQL to select data
        $sql = "SELECT * FROM subscriptions";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {

                if($row["PUSH"] == "Yes"){
                    $push = "Push notifications on APP for new release or event";
                } else { $push = '';}

                if($row["ANALYTICS"] == "Yes"){
                    $analytics = "Analytics";
                } else { $analytics = '';}

                if($row["API"] == "Yes"){
                    $api = "API Support";
                } else { $api = '';}


                $current_plan = $row["PLAN"];

                echo '<div class="col-sm-6 col-xl-3">
                
                <div class="pricing_packages active text-center bdrs16" id="'.$row["PLAN"].'">
                <div class="active-plan ud-btn btn-thm choose-plan text-center mt-0 mb-0 p-2" style="transform: translateY(-70px) !important;">Active</div>
                  <div class="heading mb10">
                    <h1 class="text2">R'.$row["PRICE"].'<small class="text3">/Month</small></h1>
                    <h1 class="text1">R'.$row["YEARLY"] * 80/100 .' <small class="text3">/Year</small></h1>
                    <h4 class="package_title mt-2">'.$row["PLAN"].' Plan</h4>
                  </div>
                  <hr>
                  <div class="details">
                    <p class="text mb30 d-none">One time fee for one listing or task highlighted in search results.</p>
                    <div class="pricing-list mb40">
                      <ul class="px-0">
                        <li>'.$row["BOOKS"].' Free Book Listings</li>
                        <li>'.str_replace('1000000000','Unlimited', $row["PRICED"]).' Priced Books</li>
                        <li>'.str_replace('1000000000','Unlimited', $row["SERVICES"]).' Service Listing</li>
                        <li>AD '.str_replace('1000000000','Unlimited', $row["EVENTS"]).' events per month </li>
                        <li>'.$row["EMAILS"].' Email addresses</li>
                        <li>Website '.$row["WEBSITE"].'</li>
                        <hr>
                        <li>'.$analytics.'</li>
                        <li>'.$api.'</li>
                        <li>'.$push.'</li>
                      </ul>
                    </div>
                    <div class="d-grid" id="switch-plan">
                      <a href="subscription?plan='.$row["PLAN"].'&current='.$current_plan.'&type=monthly" class="monthly ud-btn btn-thm-border bdrs8 choose-plan choose-plans">Choose Monthly Plan<i class="fal fa-arrow-right-long"></i></a>

                      <a href="subscription?plan='.$row["PLAN"].'&current='.$current_plan.'&type=yearly" class="yearly ud-btn btn-thm-border bdrs8 choose-plan choose-plans">Choose Yearly Plan<i class="fal fa-arrow-right-long"></i></a>
                    </div>
                  </div>             
                </div>
              </div>';
            }
        } else {
            echo "0 results";
        }

        $conn->close();
        ?>
         

        </div>
                  
                </div>
              </div>
            </div>
          </div>     
        </div>
        <?php include 'includes/footer.php';?>
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
<script src="js/pricing-table.js"></script>
<!-- Custom script for all pages --> 
<script src="js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$('choose-plan').click(function(){
    let contentid = $(this).attr('data-target');

    $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

    $(this).load('../includes/backend/scripts/subscription.php?subscription_type='+contentid);
    //alert(contentid);
})


function checkPrice() {
  var checkBox = document.getElementById("checbox");
  var text1 = document.getElementsByClassName("text1");
  var text2 = document.getElementsByClassName("text2");
  var text3 = document.getElementsByClassName("text3");
  var monthly = document.getElementsByClassName("monthly");
  var yearly = document.getElementsByClassName("yearly");

  for (var i = 0; i < text1.length; i++) {

    if (checkBox.checked == true) {
      text1[i].style.display = "block";
      text2[i].style.display = "none";
      yearly[i].style.display = "block";
      monthly[i].style.display = "none";
    } else if (checkBox.checked == false) {
      text1[i].style.display = "none";       
      text2[i].style.display = "block";
      monthly[i].style.display = "block";
      yearly[i].style.display = "none";
    }
  }
}


</script>




<!-- Add this script after including Shepherd.js -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Shepherd.js
    const tour = new Shepherd.Tour({
        defaultStepOptions: {
            classes: 'shepherd-theme-arrows', // Use the blue theme
            arrowClass: 'custom-arrow', // Add custom arrow class
            attachToElement: document.body, // Ensure arrow visibility
            arrow: true, // Show arrow
            arrowColor: '#111', // Set arrow color
            arrowSize: 15, // Set arrow size
        },
    });  

    // Add tour steps
    tour.addStep({
        id: 'step1',
        text: '<b>Upgrade/Downgrade Account:</b><hr> You can upgrade or downgrade your account.',
        attachTo: { element: '#element9', on: 'right' },  
        classes: 'example-step-extra-class custom-step-content', // Add custom step class
        buttons: [
            {
                text: 'Skip',
                action: tour.cancel,
            },
            {
                text: 'Next',
                action: tour.next,
            },
        ],
    });


    // Add tour steps
    tour.addStep({
        id: 'step1',
        text: '<b>Payment Cycle:</b><hr> You can switch through payment cycles either pay monthly or annually.',
        attachTo: { element: '#cycle', on: 'top' },
        classes: 'example-step-extra-class custom-step-content', // Add custom step class
        buttons: [
            {
                text: 'Skip',
                action: tour.cancel,
            },
            {
                text: 'Next',
                action: tour.next,    
            },
        ],
    });



      // Add tour steps
      tour.addStep({
        id: 'step10',
        text: '<b>Switch Service Plan:</b><hr> You can switch your service plan, you may be required to pay a fee for a specific upgrade.',
        attachTo: { element: '#switch-plan', on: 'left' },
        classes: 'example-step-extra-class custom-step-content', // Add custom step class
        buttons: [
            {
                text: 'Skip',
                action: tour.cancel,
            },
            {
                text: 'Complete',
                action: tour.complete,
            },
        ],
    });

    

    // Attach the click event listener to the button
    document.getElementById('startTourButton').addEventListener('click', function () {
        // Start the tour  
        tour.start();
    });

});

</script>


</body>

</html>