<?php

error_reporting(1);
ini_set('display_errors', 1);
                                        // DATABASE CONNECTIONS SCRIPT
                                        include '../includes/database_connections/sabooks.php';// Connection to the first database

                                       // $userid = $_SESSION['ADMIN_USERKEY'];

                                       $userkey = $_GET['user'];
                                       $id = $_GET['id'];

                                        $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$userkey'";
                                        $result = mysqli_query($conn, $sql); // Use the connection to the first database
                                        if (mysqli_num_rows($result) == false) {
                                        } else {
                                            while ($row = mysqli_fetch_assoc($result)) {

                                                $name = $row['ADMIN_NAME'];
                                                $username_plesk = $row['ADMIN_NAME'];  
                                                $user_id_plesk = $row['ADMIN_ID'];
                                                $email = $row['ADMIN_EMAIL'];
                                                $date = $row['ADMIN_DATE'];
                                                $website = $row['ADMIN_WEBSITE'];
                                                $desc = $row['ADMIN_BIO'];
                                                $number = $row['ADMIN_NUMBER'];
                                                $subscription = $row['ADMIN_SUBSCRIPTION'];
                                                $profile = $row['ADMIN_PROFILE_IMAGE'];
                                                $address = $row['ADMIN_GOOGLE'];
                                               
                                                   
                                            }    
                                        }     



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
<!-- Title -->
<title>View Order</title>
<!-- Favicon -->
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" />
<!-- Apple Touch Icon -->
<link href="images/apple-touch-icon-60x60.png" sizes="60x60" rel="apple-touch-icon">
<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
<link href="images/apple-touch-icon-180x180.png" sizes="180x180" rel="apple-touch-icon">

<style>
    .dashboard_sidebar_list .sidebar_list_item a:hover,
.dashboard_sidebar_list .sidebar_list_item a:active,
.dashboard_sidebar_list .sidebar_list_item a:focus,
.dashboard_sidebar_list .sidebar_list_item a.orders{
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
        <?php include 'includes/header-dash.php';?>
      <div class="dashboard__main pl0-md">
        <div class="dashboard__content hover-bgc-color">
          <div class="row pb40">
            <?php include 'includes/header-guide.php';?>
            
          </div>

              <!-- Our Invoice Page -->
    <section class="our-invoice my-0 py-0">
      <div class="container wow fadeInUp" data-wow-delay="300ms">
        <div class="row">
          <div class="col-lg-12">
            <div class="invoice_table">
              <div class="wrapper">
                <div class="row mb20 align-items-center">
                  <div class="col-lg-7">
                    <div class="main_logo mb30-md"><img src="https://sabooksonline.co.za/img/social.png" width="150px"></div>
                  </div>
                  <div class="col-lg-5">
                    <div class="invoice_deails">
                      <h3 class="float-start dark-color">Invoice #</h3>
                      <h5 class="float-end"><?php echo $id;?></h5>
                    </div>
                  </div>
                </div>
                <div class="row mt55">
                  <div class="col-sm-6 col-lg-7">
                    <div class="invoice_date mb60">
                      <div class="title mb5 ff-heading dark-color">Registration Date:</div>
                      <h6 class="fw500 mb0"><?php echo $date;?></h6>
                    </div>
                    <div class="invoice_address">
                      <h4 class="mb20">Customer</h4>

                      <p class="dark-color ff-heading"> 
                        <?php echo $name;?><br>
                                                    
                        <?php echo $address;?><br>
                                                    
                        <?php echo $email;?><br>
                                                    
                        <abbr title="Phone">P:</abbr> <?php echo $number;?>
                      </p>

                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-5">
                    <div class="invoice_date mb60">
                      <div class="title mb5 ff-heading dark-color">Payment Method:</div>
                      <h6 class="fw500 mb0">PayFast Credit Card</h6>
                    </div>
                    <div class="invoice_address">
                      <h4 class="mb20">Service Provider</h4>
                      <p class="dark-color ff-heading">
                                                    SA Books Online<br>
                                                    68 Melville Rd, Illovo Point,<br> Sandton, 2196<br>
                                                    <abbr title="Phone">P:</abbr> (010) 900 2869
</p>
                    </div>
                  </div>
                </div>
                <div class="row mt50">
                  <div class="col-lg-12">
                    <div class="table-responsive invoice_table_list">
                      <table class="table table-borderless">
                        <thead class="thead-light">
                          <tr class="tblh_row">
                            <th class="tbleh_title" scope="col">Transaction ID</th>
                            <th class="tbleh_title" scope="col">Payment Details</th>
                            <th class="tbleh_title" scope="col">Payment Date</th>
                            <th class="tbleh_title" scope="col">Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                        
                          <?php
                                                        // DATABASE CONNECTIONS SCRIPT
                                                        include 'includes/database_connections/sabooks.php'; // Connection to the first database
                                                        include 'includes/database_connections/other_database.php'; // Connection to the second database

                                                    $userid = $_GET['id'];

                                                        $sql = "SELECT * FROM payments WHERE payment_id = '$userid' ORDER BY payment_id DESC";
                                                        $result = mysqli_query($conn, $sql); // Use the connection to the first database
                                                        if (mysqli_num_rows($result) == false) {
                                                            
                                                        } else {
                                                            while ($row = mysqli_fetch_assoc($result)) {

                                                                $status = $row['status']; // Fix the assignment operator here

                                                                if ($status == 'active') {
                                                                    $status = '<span class="pending-style style2">Completed</span>';
                                                                } else if ($status == 'pending') { // Fix the assignment operator here
                                                                    $status = '<span class="pending-style style1">Pending</span>';
                                                                }

                                                                $sub = $row['amount_paid'];

                                                                echo '<tr class="bdrb1">
                                                                <td class="tbl_title" scope="row">' . $row['payment_id'] . '</td>
                                                                <td class="tbl_title" >
                                                                    <b>SABO Subscription</b> <br/>
                                                                    Token: ' . $row['token'] . '
                                                                </td>
                                                                <td class="tbl_title" >' . $row['payment_date'] . '</td>
                                                                <td  class="tbl_title" ><b>R' . $row['amount_paid'] . '</b></td>
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
              <div class="invoice_footer">
                <div class="row justify-content-center">
                  <div class="col-auto">
                    <div class="invoice_footer_content text-center">
                      <a class="ff-heading" href="#">www.sabooksonline.co.za</a>
                    </div>
                  </div>
                  <div class="col-auto">
                    <div class="invoice_footer_content text-center">
                      <a class="ff-heading" href="#">accounts@sabooksonline.co.za</a>
                    </div>
                  </div>
                  <div class="col-auto">
                    <div class="invoice_footer_content text-center">
                      <a class="ff-heading" href="#">010 900 2869</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-print-none mt-4">
                                            <div class="text-center">
                                                <a href="https://admin-dashboard.sabooksonline.co.za/composer/generate_invoice_pdf.php?id=<?php echo $_GET['id'];?>&user=<?php echo $_GET['user'];?>" class="btn btn-primary"><i class="ri-printer-line"></i> Download Invoice</a>
                                            </div>
                                        </div>   
                                        <!-- end buttons -->  
            </div>
          </div>
        </div>
      </div>
    </section>
          
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
</body>

</html>