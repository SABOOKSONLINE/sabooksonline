<?php session_start();?>

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
<!-- Title -->
<title>Plan</title>       
<!-- Favicon -->
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" />


<script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>  

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
    .dashboard_sidebar_list .sidebar_list_item a:hover,
.dashboard_sidebar_list .sidebar_list_item a:active,
.dashboard_sidebar_list .sidebar_list_item a:focus,
.dashboard_sidebar_list .sidebar_list_item a.subscriptions{
  background-color: #222222;
  color: #ffffff;
}

.fz15 {font-size:.8rem !important}
.title {font-size:1.5rem !important;font-weigh:bold !important;}
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
          <div class="row">
          <?php include 'includes/mobile-guide.php';?>
          </div>
          <div class="row align-items-center justify-content-between">
          <?php include 'includes/suspension_status.php';?>
            <div class="col-xl-4">

            <div>
              
            </div>
              <div class="dashboard_title_area">     
             
                <?php


$servername = "localhost";
                $username = "sabooks_library";
                $password = "1m0g7mR3$";
                $dbh = "manqas_subscriptions";
                
                // Create connection
                $mysqli_2 = new mysqli($servername, $username, $password, $dbh);
                
                // Check connection
                if ($mysqli_2->connect_error) {
                die("Connection failed: " . $mysqli_2->connect_error);
                }


                $userid = $_SESSION['ADMIN_USERKEY']; // Replace with the specific user_id you want to query

                // Select the last entry for the specified user_id
                $sql = "SELECT * FROM subscriptions WHERE user_id = ? ORDER BY subscription_id DESC LIMIT 1";
                $stmt = $mysqli_2->prepare($sql);
                $stmt->bind_param("i", $userid); // Assuming user_id is an integer, use "i" as the type

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Fetch the last entry
                    $row = $result->fetch_assoc();
                    
                    // Retrieve and display the "next_invoice_date" column from the last entry
                    $nextInvoiceDate = $row['next_invoice_date'];
                    $next = "Renewal Date: $nextInvoiceDate";
                } else {
                  $next = " ";
                }
                // Close the database connection
                ?>


                <h2>Billing Subscription </h2>
                <h5><b class="text-success"><?php echo $next;?></b></h5>
                <hr>
              </div>
            </div>
            <!--<div class="col-xl-4">
              <div class="dashboard_search_meta">
                <div class="search_area">
                  <input type="text" class="form-control bdrs4" placeholder="Search Invoice">
                  <label><span class="far fa-magnifying-glass"></span></label>
                </div>
              </div>
            </div>-->

            <div class="col-lg-3">
              <div class="text-lg-end">

              <?php 
                $subscription_btn = $_SESSION['ADMIN_SUBSCRIPTION'];
                $subscription_text = $_SESSION['ADMIN_SUBSCRIPTION'];

                if ($subscription_btn == 'Standard') {
                    echo '<a href="plan" class="ud-btn btn-warning default-box-shadow3 text-white"  id="switch">Standard Plan<i class="fa fa-star"></i></a>';
                } elseif ($subscription_btn == 'Premium') {
                    echo '<a href="plan" class="ud-btn btn-success default-box-shadow2 text-white" id="switch">Premium Plan<i class="fa fa-crown"></i></a>';
                } elseif ($subscription_btn == 'Deluxe') {
                    echo '<a href="plan" class="ud-btn btn-dark default-box-shadow2 text-white" id="switch">Deluxe Plan<i class="fa fa-certificate"></i></a>';
                } elseif ($subscription_btn == 'Free') {
                    echo '<a href="plan" class="ud-btn btn-info default-box-shadow2 text-white" id="switch">Free Plan<i class="fa fa-user"></i></a>';
                }

                ?>
              
                <a href="plan" class="ud-btn btn-danger default-box-shadow2 d-none"> Cancel Subscription<i class="fa fa-certificate"></i></a>
              </div>
            </div>
          </div>

          <?php include '../includes/backend/scripts/select/select_subscription_data.php';?>

          
          <hr>
          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="packages_table table-responsive">
                  <table class="table-style3 table at-savesearch">
                    <thead class="t-head">
                      <tr>
                        <th scope="col">Payment ID</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Date</th>
                        <th scope="col"> Amount</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody class="t-body">

                       <?php
$userid = $_SESSION['ADMIN_USERKEY'];

$sql = "SELECT * FROM payments WHERE invoice_id = '$userid' ORDER BY payment_id DESC";
$result = mysqli_query($conn, $sql); // Use the connection to the first database

if (mysqli_num_rows($result) == false) {
    // Handle no rows returned
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $paymentId = $row['payment_id'];
        $paymentDate = $row['payment_date'];
        $token = $row['token'];
        $amountPaid = $row['amount_paid'];
        $status = $row['status']; // Fetch the status of the payment

        // Determine if the "Attempt Capture" button should be shown
        $attemptCaptureButton = ($status !== 'Paid') ? 
            '<a href="../includes/subscriptions/attempt_capture.php?token=' . $token . '&amount=7&userid=' . $userid . '&userid='.$userid.'&item='.$subscription_text.'&id='.$paymentId.'" class="table-action fz15 fw500 text-thm2 bg-success text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Attempt Capture">Pay Now</a>'         
            : '';
                  
            if($status !== 'Paid'){

                $statusShow = '<a href="" class="text-danger"><b>Unpaid</b></a>';

            } else {

                $statusShow = '<a href="" class="text-success"><b>Paid</b></a>';  

            }
     
        echo '<tr>
                <td><a href="invoice?id=' . $paymentId . '&user=' . $userid . '">#' . $paymentId . ' - '.$statusShow.'</a></td>
                <td><a href="#">PayFast</a></td>
                <td>' . $paymentDate . '</td>
                <td>R' . $amountPaid . '</td>
                <td class="vam">
                  <a href="view-invoice?id=' . $paymentId . '&user=' . $userid . '" class="table-action fz15 fw500 text-thm2" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><span class="flaticon-website me-2 vam"></span> </a>
                  <a href="https://admin-dashboard.sabooksonline.co.za/composer/generate_invoice_pdf.php?id=' . $paymentId . '" class="table-action fz15 fw500 text-thm2" data-bs-toggle="tooltip" data-bs-placement="top" title="Download"><span class="fa fa-download me-2 vam"></span></a>  
                  ' . $attemptCaptureButton . '  
                </td>
            </tr>';   
    }
}  
?>
                                    
                    </tbody>
                  </table>
                  
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
<!-- Custom script for all pages --> 
<script src="js/script.js"></script>

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
        text: '<b>Account Billing:</b><hr> You can manage your account and switch to your desired plan.',
        attachTo: { element: '#element8', on: 'right' },
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
        attachTo: { element: '#switch', on: 'left' },
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