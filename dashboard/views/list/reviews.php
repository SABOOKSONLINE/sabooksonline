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
<title>Reviews</title>         
<!-- Favicon -->
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" />
<!-- Apple Touch Icon -->
<link href="images/apple-touch-icon-60x60.png" sizes="60x60" rel="apple-touch-icon">
<link href="images/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
<link href="images/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
<link href="images/apple-touch-icon-180x180.png" sizes="180x180" rel="apple-touch-icon">

<link rel="stylesheet" href="sweetalert2.min.css">

<script src="https://jsuites.net/v4/jsuites.js"></script>
<script src="https://jsuites.net/v4/jsuites.webcomponents.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />


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
.dashboard_sidebar_list .sidebar_list_item a.reviews{
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


            <div class="col-lg-12">
              <div class="dashboard_title_area">
                <h2>Reviews</h2>
                <p class="text">You can manage, add or delete your reviews based on listings.</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="packages_table table-responsive">
                  <div class="navtab-style1">
                    <nav>
                      <div class="nav nav-tabs mb20" id="nav-tab2" role="tablist">
                        <button class="nav-link active fw500 ps-0" id="nav-item1-tab" data-bs-toggle="tab" data-bs-target="#nav-item1" type="button" role="tab" aria-controls="nav-item1" aria-selected="true">All Reviews</button>
                        <!--<button class="nav-link fw500" id="nav-item2-tab" data-bs-toggle="tab" data-bs-target="#nav-item2" type="button" role="tab" aria-controls="nav-item2" aria-selected="false">Events</button>
                        <button class="nav-link fw500" id="nav-item3-tab" data-bs-toggle="tab" data-bs-target="#nav-item3" type="button" role="tab" aria-controls="nav-item3" aria-selected="false">Services</button>
                        <button class="nav-link fw500" id="nav-item3-tab" data-bs-toggle="tab" data-bs-target="#nav-item4" type="button" role="tab" aria-controls="nav-item3" aria-selected="false">Book-Store</button>-->
                      </div>
                    </nav>  
                    <div class="tab-content" id="nav-tabContent">
                      <div class="tab-pane fade show active" id="nav-item1" role="tabpanel" aria-labelledby="nav-item1-tab">

                      <?php
                        // Start the session
                        session_start();

                        // Include your database connection script
                        include '../includes/database_connections/sabooks.php';

                        // Get the userkey from the session
                        $userkey = $_SESSION['ADMIN_USERKEY'];

                        // Modify your SQL query to select reviews based on contentid and userkey
                        $sql = "SELECT r.*
                                FROM reviews r
                                JOIN posts p ON r.CONTENTID = p.CONTENTID
                                WHERE p.USERID = '$userkey'
                                ORDER BY r.ID DESC";

                        // Execute the SQL query
                        $results = mysqli_query($conn, $sql);

                        // Check for errors
                        if (!$results) {
                            echo "Error: " . mysqli_error($conn); // Print the MySQL error message for debugging
                            exit; // Exit the script if there's an error
                        }

                        // Check if there are any reviews
                        if (mysqli_num_rows($results) == 0) {
                            echo '<div class="alert alert-info b-none" style="border: none !important;">
                                    No content for reviews yet
                                </div>';
                        } else {
                            // Loop through the results and display reviews
                            while ($row = mysqli_fetch_assoc($results)) {
                                // Extract review data
                                $reviewTitle = ucfirst($row['TITLE']);
                                $reviewReview = ucfirst($row['REVIEW']);
                                $reviewDate = ucfirst($row['DATEPOSTED']);
                                $reviewRating = ucfirst($row['RATING']);
                                $reviewUsername = ucfirst($row['USERNAME']);
                                $reviewid = ucfirst($row['ID']);

                                // Display each review
                                echo '<div class="col-md-12">
                                        <div class="bdrb1 pb20">
                                        <div class="mbp_first position-relative d-sm-flex align-items-center justify-content-start mb30-sm mt30">
                                            <img src="../img/avatar.jpg" class="mr-3" alt="comments-2.png" width="50px" style="border-radius:50%;">
                                            <div class="ml20 ml0-xs mt20-xs">
                                            <div class="del-edit"><span class="flaticon-flag"></span></div>
                                            <h6 class="mt-0 mb-1">'.$reviewUsername.'</h6>
                                            <div class="d-flex align-items-center">
                                                <div><i class="fas fa-star vam fz10 review-color me-2"></i><span class="fz15 fw500">'.$reviewRating.'</span></div>
                                                <div class="ms-3"><span class="fz14 text">'.$reviewDate.'</span></div>
                                            </div>
                                            </div>
                                        </div>
                                        <p class="text mt20 mb20">'.$reviewTitle.'</p>
                                        <a href="#" data-contentid="'.$reviewid.'" class="ud-btn bgc-thm4 text-thm delete_item">Delete Review</a>
                                        </div>
                                    </div>';
                            }
                        }

                        // Close the database connection
                        mysqli_close($conn);
                        ?>

                       <div id="domain_status"></div>
                      
                      </div>
                      <div class="tab-pane fade" id="nav-item2" role="tabpanel" aria-labelledby="nav-item2-tab">
                        <div class="col-md-12">
                          <div class="bdrb1 pb20">
                            <div class="mbp_first position-relative d-sm-flex align-items-center justify-content-start mb30-sm mt30">
                              <img src="images/blog/comments-2.png" class="mr-3" alt="comments-2.png">
                              <div class="ml20 ml0-xs mt20-xs">
                                <div class="del-edit"><span class="flaticon-flag"></span></div>
                                <h6 class="mt-0 mb-1">Ali Tufan</h6>
                                <div class="d-flex align-items-center">
                                  <div><i class="fas fa-star vam fz10 review-color me-2"></i><span class="fz15 fw500">4.98</span></div>
                                  <div class="ms-3"><span class="fz14 text">Published 2 months ago</span></div>
                                </div>
                              </div>
                            </div>
                            <p class="text mt20 mb20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                            <a href="page-service-single.html" class="ud-btn bgc-thm4 text-thm">Respond</a>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="bdrb1 pb20">
                            <div class="mbp_first position-relative d-sm-flex align-items-center justify-content-start mb30-sm mt30">
                              <img src="images/blog/comments-2.png" class="mr-3" alt="comments-2.png">
                              <div class="ml20 ml0-xs mt20-xs">
                                <h6 class="mt-0 mb-1">Ali Tufan</h6>
                                <div class="del-edit"><span class="flaticon-flag"></span></div>
                                <div class="d-flex align-items-center">
                                  <div><i class="fas fa-star vam fz10 review-color me-2"></i><span class="fz15 fw500">4.98</span></div>
                                  <div class="ms-3"><span class="fz14 text">Published 2 months ago</span></div>
                                </div>
                              </div>
                            </div>
                            <p class="text mt20 mb20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                            <a href="page-service-single.html" class="ud-btn bgc-thm4 text-thm">Respond</a>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="mbp_first position-relative d-sm-flex align-items-center justify-content-start mb30-sm mt30">
                            <img src="images/blog/comments-2.png" class="mr-3" alt="comments-2.png">
                            <div class="ml20 ml0-xs mt20-xs">
                              <h6 class="mt-0 mb-1">Ali Tufan</h6>
                              <div class="d-flex align-items-center">
                                <div><i class="fas fa-star vam fz10 review-color me-2"></i><span class="fz15 fw500">4.98</span></div>
                                <div class="ms-3"><span class="fz14 text">Published 2 months ago</span></div>
                                <div class="del-edit"><span class="flaticon-flag"></span></div>
                              </div>
                            </div>
                          </div>
                          <p class="text mt20 mb20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                          <a href="page-service-single.html" class="ud-btn bgc-thm4 text-thm">Respond</a>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="nav-item3" role="tabpanel" aria-labelledby="nav-item3-tab">
                        <div class="col-md-12">
                          <div class="bdrb1 pb20">
                            <div class="mbp_first position-relative d-sm-flex align-items-center justify-content-start mb30-sm mt30">
                              <img src="images/blog/comments-2.png" class="mr-3" alt="comments-2.png">
                              <div class="ml20 ml0-xs mt20-xs">
                                <div class="del-edit"><span class="flaticon-flag"></span></div>
                                <h6 class="mt-0 mb-1">Ali Tufan</h6>
                                <div class="d-flex align-items-center">
                                  <div><i class="fas fa-star vam fz10 review-color me-2"></i><span class="fz15 fw500">4.98</span></div>
                                  <div class="ms-3"><span class="fz14 text">Published 2 months ago</span></div>
                                </div>
                              </div>
                            </div>
                            <p class="text mt20 mb20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                            <a href="page-service-single.html" class="ud-btn bgc-thm4 text-thm">Respond</a>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="bdrb1 pb20">
                            <div class="mbp_first position-relative d-sm-flex align-items-center justify-content-start mb30-sm mt30">
                              <img src="images/blog/comments-2.png" class="mr-3" alt="comments-2.png">
                              <div class="ml20 ml0-xs mt20-xs">
                                <div class="del-edit"><span class="flaticon-flag"></span></div>
                                <h6 class="mt-0 mb-1">Ali Tufan</h6>
                                <div class="d-flex align-items-center">
                                  <div><i class="fas fa-star vam fz10 review-color me-2"></i><span class="fz15 fw500">4.98</span></div>
                                  <div class="ms-3"><span class="fz14 text">Published 2 months ago</span></div>
                                </div>
                              </div>
                            </div>
                            <p class="text mt20 mb20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                            <a href="page-service-single.html" class="ud-btn bgc-thm4 text-thm">Respond</a>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="mbp_first position-relative d-sm-flex align-items-center justify-content-start mb30-sm mt30">
                            <img src="images/blog/comments-2.png" class="mr-3" alt="comments-2.png">
                            <div class="ml20 ml0-xs mt20-xs">
                              <div class="del-edit"><span class="flaticon-flag"></span></div>
                              <h6 class="mt-0 mb-1">Ali Tufan</h6>
                              <div class="d-flex align-items-center">
                                <div><i class="fas fa-star vam fz10 review-color me-2"></i><span class="fz15 fw500">4.98</span></div>
                                <div class="ms-3"><span class="fz14 text">Published 2 months ago</span></div>
                              </div>
                            </div>
                          </div>
                          <p class="text mt20 mb20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                          <a href="page-service-single.html" class="ud-btn bgc-thm4 text-thm">Respond</a>
                        </div>
                      </div>
                    </div>
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
<!-- Custom script for all pages --> 
<script src="js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(".delete_item").click(function(){

    let contentid = $(this).attr('data-contentid');

    //alert(locate);

    $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

    $.post("../includes/backend/delete-reviews.php",
    {
        contentid: contentid
    },

    function(data, status){
        $("#domain_status").html(data);
    }

    /*function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
    }*/);
    });
</script>
</body>

</html>