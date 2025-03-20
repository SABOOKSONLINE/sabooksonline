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
            <div class="col-lg-12">
              <div class="dashboard_title_area">
                <h2>Manage Order #<?php echo $_GET['invoice'];?></h2>

               <?php 

               

                //DATABASE CONNECTIONS SCRIPT
                include '../includes/database_connections/sabooks_user.php';

                
                                               
                                               $user_invoice = $_GET['invoice'];

                                               //$userid = $_SESSION['user_id'];

                                               $userids = $_GET['invoiceid'];
   
                                               $sqls = "SELECT * FROM user_info WHERE user_id = '$userids'";

                                               $results = mysqli_query($con, $sqls);
                                                   if(mysqli_num_rows($results) == false){

                                                     echo '<a>No content found!</a>';

                                                   }else{
                                                   while($rows = mysqli_fetch_assoc($results)) {

                                              
                                                       echo '
                                                       <td class="vam"><span class="pending-style style4"><i class="fa fa-user"></i> '.ucwords($rows['first_name']).' '. ucwords($rows['last_name']).'</span></td>
                                                       
                                                       <td class="vam"><span class="pending-style style4"><i class="fa fa-marker"></i> '.ucwords($rows['address1']).'</span></td>
                                                       
                                                       <td class="vam ml-1"><span class="pending-style style8"><i class="fa fa-envelope"></i> '.ucwords($rows['email']).'</span></td>';
                                                   }
                                               }
                                           ?>
                
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="packages_table table-responsive">
                  <table class="table-style3 table at-savesearch">
                    <thead class="t-head">
                      <tr>
                        <th scope="col">Cover</th>
                        <th scope="col">Title</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                      </tr>
                    </thead>
                    <tbody class="t-body">

                                
                    <?php
                                               
                                                $user_invoice = $_GET['invoice'];

                                                //$userid = $_SESSION['user_id'];
    
                                                $sql = "SELECT * FROM product_order WHERE invoice_number = '$user_invoice'";

                                                $result = mysqli_query($con, $sql);
                                                    if(mysqli_num_rows($result) == false){

                                                      echo '<a>No content found!</a>';

                                                    }else{
                                                    while($row = mysqli_fetch_assoc($result)) {

                                                        echo '<tr>
                                                        <td class="product-thumbnail"><a href="#"><img
                                                                src="https://sabooksonline.co.za/cms-data/book-covers/'.$row['product_image'].'" alt="product img" width="50px"></a></td>
                                                        <td class="product-name"><a href="#">'.$row['product_title'].'</a></td>
                                                        <td class="product-price"><span class="amount">R'.$row['product_price'].'</span></td>
                                                        
                                                        <td class="product-subtotal"><b>'.$row['product_quantity'].'</b></td>
                                                        <td class="product-subtotal">R'.$row['product_total'].'</td>
                                                        
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
</body>

</html>