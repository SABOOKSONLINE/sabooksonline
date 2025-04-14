<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="bidding, fiverr, freelance marketplace, freelancers, freelancing, gigs, hiring, job board, job portal, job posting, jobs marketplace, peopleperhour, proposals, sell services, upwork">
<meta name="description" content="SA Books Online">
<meta name="CreativeLayers" content="ATFN">
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
<title>Add Service</title>
<!-- Favicon -->
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" />
<!-- Apple Touch Icon -->
<link href="images/apple-touch-icon-60x60.png" sizes="60x60" rel="apple-touch-icon">
<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
<link href="images/apple-touch-icon-180x180.png" sizes="180x180" rel="apple-touch-icon">

<script src="https://jsuites.net/v4/jsuites.js"></script>
<script src="https://jsuites.net/v4/jsuites.webcomponents.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">

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
.dashboard_sidebar_list .sidebar_list_item a.services{
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
          <?php include 'includes/mobile-guide.php';?>
            <div class="col-lg-9">
              <div class="dashboard_title_area">
                <h2>Add Your Service</h2>
                <p class="text">You may add a new service and select the new options.</p>
              </div>
            </div>
            <!--<div class="col-lg-3">
              <div class="text-lg-end">
                <a href="#" class="ud-btn btn-dark">Save & Publish<i class="fal fa-arrow-right-long"></i></a>
              </div>
            </div>-->
          </div>
          <form class="form-style1" id="membership">
          <div class="row">
            <div class="col-xl-6">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Basic Information</h5>
                </div>
                <div class="col-xl-12">
                  
                    <div class="row">

                      <div class="col-sm-12">
                        <div class="mb20">
                          <div class="form-style1">
                            <label class="heading-color ff-heading fw500 mb10">Type Of Service</label>
                            <div class="bootselect-multiselect">
                                  <select class="selectpicker" name="service_type" >

                                  <?php
                                  
                                  //DATABASE CONNECTIONS SCRIPT
                                  include '../includes/database_connections/sabooks.php';
                                  $userkey = $_SESSION['ADMIN_USERKEY'];
                                    $sql = "SELECT * FROM services_admin WHERE STATUS = 'Active' ORDER BY ID DESC;";
                                    
                                    if(!$result = mysqli_query($conn, $sql)){

                                        echo "<div class='alert alert-info border-none'>You currently have no content uploaded.<a href='dashboard-add-book'> Add New Service</a>.</div";

                                    }else{

                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="'.$row['SERVICE'].'">'.$row['SERVICE'].'</option>';
                                        }
                                    }
                                                    
                                    ?>
                                    
                                </select>
                            </div>
                          </div>
                        </div>
                      </div>


                        <div class="col-lg-12">
                          <div class="ui-content">
                            <h5 class="title">Service Price Range</h5>
                            <!-- Range Slider Desktop Version -->
                            <div class="range-slider-style1 mb-4 mb-lg-5">
                              <div class="range-wrapper">
                                <div class="slider-range mt35 mb20"></div>
                                <div class="text-center">
                                  <input type="text" class="amount" value="200" name="service_price_min"><span class="fa-sharp fa-solid fa-minus mx-2 dark-color"></span>
                                  <input type="text" class="amount2" name="service_price_max" value="10000">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      <div class="text-start">
                        <button class="ud-btn btn-thm" type="submit" id="reg_load">Save & Publish<i class="fal fa-arrow-right-long"></i></button>
                      </div>


                      <div id="reg_status"></div>

                    </div>
                </div>
              </div>

            </div>
          </div>
          </form>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
	

    <script>
  
    
    $(document).ready(function() {
    
        $("#membership").on('submit',(function(e) {
    
            e.preventDefault();
    
            $("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    
                //showSwal('success-message');
            $.ajax({
                    url: "../includes/backend/member-add-service.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#reg_load").html('Save & Publish');
                $("#reg_status").html(data);
                },
            error: function(){}
        });
    
    
        }));
    });
    //publiish story upload code
    
    </script>



</body>

</html>