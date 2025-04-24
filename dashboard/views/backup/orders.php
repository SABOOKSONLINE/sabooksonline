<!DOCTYPE html>
<html dir="ltr" lang="en">

<!-- Mirrored from SA Books Online.net/themes/freeio-html/invoice.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 14 Jul 2023 19:23:42 GMT -->
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
<title>Orders</title>
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
          <div class="row">
          <?php include 'includes/mobile-guide.php';?>
          </div>
          <div class="row align-items-center justify-content-between">
            <div class="col-xl-4">
              <div class="dashboard_title_area">
                <h2>Orders</h2>
                <p class="text">You can manage and view your online orders.</p>
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
          </div>

          <div class="row">
            <div class="col-sm-6 col-xxl-3" id="income">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Net Income</div>
                  <div class="title">R
                    
                    <?php
                        //DATABASE CONNECTIONS SCRIPT
                        include '../includes/database_connections/sabooks_user.php';

                        if($websitedata == false){
                            echo '';
                        } else {
                          
                                                
                        $result = mysqli_query($con, "SELECT SUM(product_total) AS value_sum FROM product_order WHERE product_current = 'COMPLETED'"); 
                        $row = mysqli_fetch_assoc($result); 
                        $sum = $row['value_sum'];

                        echo $sum;

                        }
                                            ?>

                  </div>
                </div>
                <div class="icon text-center"><i class="flaticon-income"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3" id="transactions">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Transactions</div>
                    <div class="title">
                      <?php

                        if($websitedata == false){
                            echo '0';
                        } else {
  

                        $rows_query = mysqli_query($con, "SELECT COUNT(*) as number_rows FROM product_order WHERE product_current = 'COMPLETED';");

                        $rows = mysqli_fetch_assoc($rows_query);

                        echo $rows['number_rows'];
                        
                        }
                      ?>
                    </div>
                </div>
                <div class="icon text-center"><i class="flaticon-withdraw"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3" id="customers">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Total Customers</div>
                  <div class="title"><?php

              
                    if($websitedata == false){
                        echo '0';
                    } else {
                    
                    $rows_query = mysqli_query($con, "SELECT COUNT(*) as number_rows FROM user_info;");

                    $rows = mysqli_fetch_assoc($rows_query);

                    echo $rows['number_rows'];

                    }

                    ?></div>
                </div>
                <div class="icon text-center"><i class="flaticon-review"></i></div>
              </div>
            </div>
            <div class="col-sm-6 col-xxl-3" id="orders">
              <div class="d-flex align-items-center justify-content-between statistics_funfact">
                <div class="details">
                  <div class="fz15">Pending Orders</div>
                  <div class="title"><?php

                    if($websitedata == false){
                        echo '0';
                    } else {
                    

                  $rows_query = mysqli_query($con, "SELECT COUNT(*) as number_rows FROM  product_order WHERE product_current = 'cart';");

                  $rows = mysqli_fetch_assoc($rows_query);

                  echo $rows['number_rows'];

                    }

                  ?></div>
                </div>
                <div class="icon text-center"><i class="flaticon-review-1"></i></div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="packages_table table-responsive">
                  <table class="table-style3 table at-savesearch">
                    <thead class="t-head">
                      <tr>
                        <th scope="col">Invoice ID</th>
                        <th scope="col">Client Name</th>
                        <th scope="col">Purchase Date</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody class="t-body">
                    <?php

                                       
                                                //$userid = $_SESSION['user_id'];

                                                if($websitedata == false){
                                                    echo '';
                                                } else {
                                                  

                                                $sql = "SELECT * FROM invoices ORDER BY invoice_id DESC";
                                                $result = mysqli_query($con, $sql);
                                                    if(mysqli_num_rows($result) == false){
                                                        //echo 'not dound';
                                                    }else{
                                                    while($row = mysqli_fetch_assoc($result)) {

                                                        $status = ['invoice_status'];

                                                        $userids = $row['invoice_user'];

                                                        if($status = 'COMPLETED'){
                                                            $status = '<span class="pending-style style2">Completed</span>';
                                                        } else {
                                                            $status = '<span class="pending-style style1">Pending</span>';
                                                        }

                                                        echo ' <tr>
                                                        <th scope="row">
                                                          <div>#'.$row['invoice_number'].' </div></th>  ';
                                                          
                                                            //$userid = $_SESSION['user_id'];

                                                            
                
                                                            $sqls = "SELECT * FROM user_info WHERE user_id = '$userids'";

                                                            $results = mysqli_query($con, $sqls);
                                                                if(mysqli_num_rows($results) == false){

                                                                    echo '<a>No content found!</a>';

                                                                }else{
                                                                $rows = mysqli_fetch_assoc($results);

                                                                    echo '<td class="vam"><span class="pending-style style4">'.ucwords($rows['first_name']).' '. ucwords($rows['last_name']).'</span></td>';
                                                            }
                                                          
                                                          echo '
                                                        
                                                        <td class="vam">'.$row['invoice_date'].'</td>
                                                        <td class="vam">R'.$row['invoice_total'].'</td>
                                                        <td class="vam">'.$status.'</td>
                                                        <td class="vam">
                                                          <a href="view-order-user?invoice='.$row['invoice_number'].'&invoiceid='.$row['invoice_user'].'" class="table-action fz15 fw500 text-thm2" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><span class="flaticon-website me-2 vam"></span> </a>
                                                        </td>
                                                      </tr>';
                                                    }
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
        text: '<b>Websites:</b><hr> You can create, edit and manage your website.',
        attachTo: { element: '#element7', on: 'right' },
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
        id: 'step2',
        text: '<b>Income:</b><hr> You can view your total generated income from website sales.',
        attachTo: { element: '#income', on: 'bottom' },
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
        id: 'step2',
        text: '<b>Transactions:</b><hr> You can view the number of completed transactions from your website.',
        attachTo: { element: '#transactions', on: 'bottom' },
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
        id: 'step2',
        text: '<b>Customers:</b><hr> You can view the number of customers on your website.',
        attachTo: { element: '#customers', on: 'bottom' },
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
        text: '<b>Pending Orders:</b><hr> You can view all the unpaid orders that are still pending.',
        attachTo: { element: '#orders', on: 'bottom' },
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