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
<title>Book Listings</title>       
<!-- Favicon -->
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
<link href="../img/favicon.png" sizes="128x128" rel="shortcut icon" />
<link href="images/apple-touch-icon-180x180.png" sizes="180x180" rel="apple-touch-icon">

<script src="https://jsuites.net/v4/jsuites.js"></script>
<script src="https://jsuites.net/v4/jsuites.webcomponents.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">

<script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>

<style>
    .dashboard_sidebar_list .sidebar_list_item a:hover,
.dashboard_sidebar_list .sidebar_list_item a:active,
.dashboard_sidebar_list .sidebar_list_item a:focus,
.dashboard_sidebar_list .sidebar_list_item a.listings{
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

            <?php if(isset($_GET['result'])){
							if($_GET['result'] == "success"){
								echo "<div class='alert alert-success border-none'>Your Book has been successfully Uploaded!.</div>";
								} else if($_GET['result'] == "failed") {
									echo "<p class='text-warning'>Your <b>Book</b> could not be <b>Deleted</b>!</p>";
								} else if($_GET['result'] == "maxupload"){
                                    echo "<div class='alert alert-danger border-none'>Your book could not be uploaded! You have reached maximum upload for Priced books.</div>";
                                    }
								}

                              
								if(isset($_GET['result1'])){
									if($_GET['result1'] == "success"){
										echo "<div class='alert alert-success border-none'>Your Book has been successfully Update!</div>";
										} else if($_GET['result1'] == "failed") {
											echo "<p class='text-warning'>Your <b>Book</b> could not be <b>Uploaded</b>!</p>";
										}
										}

										if(isset($_GET['status'])){
											if($_GET['status'] == "success"){
												echo "<div class='alert alert-success border-none'>Your Book has been successfully Removed!</div>";
												} else if($_GET['status'] == "failed") {
													echo "<p class='text-warning'>Your <b>Book</b> could not be <b>Removed</b>!</p>";
												}
												}
								
							?>

            <div class="col-lg-6">
              <div class="dashboard_title_area">
                <h2>Manage Book Listings</h2>
                <p class="text">You can manage, add or delete your book listings.</p>
              </div>
            </div>

            

            <div class="col-lg-6 d-flex justify-content-end">
              <div class="m-1">
                <a type="submit" class="ud-btn btn-dark default-box-shadow2 booklistingsadd" id="addbook" href="add-book">Add New Book<i class="fal fa-arrow-right-long"></i></a>
                </div>

                <?php if($_SESSION['ADMIN_SUBSCRIPTION'] == 'Deluxe' || $_SESSION['ADMIN_SUBSCRIPTION'] == 'Standard' || $_SESSION['ADMIN_SUBSCRIPTION'] == 'Premium'){
                    
                echo '<div class="m-1">
                <a type="submit" class="ud-btn btn-dark default-box-shadow2" id="audio" href="audio-submission">Submit Audio Book To App<i class="fal fa-arrow-right-long"></i></a>
                </div>';

                echo '<div class="m-1">
                <a type="submit" class="ud-btn btn-dark default-box-shadow2" id="script" href="script-submission">Submit eBook to App<i class="fal fa-arrow-right-long"></i></a>
                </div>
                ';
                
                }?>
              
            </div>
          </div>

          <div class="alert alert-success">
            Submit your <b>PDF Manuscript</b> and <b>MP3 Audio Book File</b>  to our Mobile App! Submissions can take up-to 5 working days.
          </div>
          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="packages_table table-responsive">
                  <table class="table-style3 table at-savesearch">
                    <thead class="t-head">
                      <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Price</th>
                        <th scope="col">Created</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody class="t-body">

                    <?php
                                  
                                  //DATABASE CONNECTIONS SCRIPT
                                  include '../includes/database_connections/sabooks.php';
                                  $userkey = $_SESSION['ADMIN_USERKEY'];
                                $sql = "SELECT * FROM posts WHERE USERID = '$userkey' ORDER BY ID DESC;";
                                

								if(!$result = mysqli_query($conn, $sql)){ 

									echo "<div class='alert alert-info border-none'>You currently have no content uploaded.<a href='dashboard-add-book'> Add New Book</a>.</div";

								}else{

									while($row = mysqli_fetch_assoc($result)) {
                                            $status = $row['STATUS'];
                                            $service_status = $row['STATUS'];

											if($status == "Active" || $status == "active"){
												$status = '<td class="vam"><span class="pending-style style1 bg-success text-white">'.ucwords($row['STATUS']).'</span></td>';
											} else if($status == "Service Locked"){
												$status = '<td class="vam"><span class="pending-style style1 bg-danger text-white">'.ucwords($row['STATUS']).'</span></td>';
											} else if($status == "Pending"){
												$status = '<td class="vam"><span class="pending-style style1 bg-warning text-white">'.ucwords($row['STATUS']).'</span></td>';
											} else if($status == "Draft"){
												$status = '<td class="vam"><span class="pending-style style1 bg-info text-white">'.ucwords($row['STATUS']).'</span></td>';
											}

                                            if($service_status == "Service Locked"){
												$service_status = '';
											} else {
                                                $service_status = '<a href="edit-book?contentid='.$row['CONTENTID'].'" class="icon me-2 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="flaticon-pencil"></span></a>';
                                            }
	
											echo '<tr>
                                            <th scope="row">
                                              <div class="freelancer-style1 p-0 mb-0 box-shadow-none">
                                                <div class="d-lg-flex align-items-lg-center">
                                                  <div class="thumb w60 position-relative rounded-circle mb15-md">
                                                    <img class="mx-auto" src="https://sabooksonline.co.za/cms-data/book-covers/'.$row['COVER'].'" alt="" width="50px">
                                                    <span class="online-badge2"></span>
                                                  </div>
                                                  <div class="details ml15 ml0-md mb15-md">
                                                    <h5 class="title mb-2">'.$row['TITLE'].'</h5>
                                                    <h6 class="mb-0 text-thm">'.ucwords($row['CATEGORY']).'</h6>
                                                  </div>
                                                </div>
                                              </div>
                                            </th>
                                            <td class="vam"><span class="fz15 fw400"><b>R'.ucwords($row['RETAILPRICE']).'</b></span></td>
                                            
                                            <td class="vam"><span>'.$row['DATEPOSTED'].'</span></td>
                                            <td class="vam"><span>'.$row['STOCK'].'</span></td>
                                            '.$status.'
                                            <td>
                                              <div class="d-flex">

                                              '.$service_status .'
                                                
                                                <a data-bs-toggle="modal" data-bs-target="#exampleModal'.$row['CONTENTID'].'" class="icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="flaticon-delete"></span></a>
                                              </div>
                                            </td>

                                            <!-- Modal -->
														<div class="modal fade" id="exampleModal'.$row['CONTENTID'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
														<div class="modal-dialog modal-dialog-centered border-none" style="border: none !important;">
															<div class="modal-content border-none" style="border: none !important;">
															<div class="modal-header">
																<h5 class="modal-title" id="exampleModalLabel">Are You Sure You want to delete the book?</h5>
																<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
															</div>
															<div class="modal-body">
																You are about to delete <b>'.ucwords($row['TITLE']).'</b>, this action cannot be undone once complete. Your book will be removed from this platform instantly.
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancel</button>
																<a href="#" type="button" class="btn btn-danger text-white delete_item" data-contentid="'.$row['CONTENTID'].'" data-locate="'.$row['COVER'].'" data-id="'.$row['ID'].'">Continue <small class="icon_trash"></small></a>
															</div>
															</div>
														</div>
														</div>
                                          </tr>
													
												   ';
	
									 }
								}
                                 
                                                                  ?>

	                                    
                    </tbody>
                  </table>

                  <div id="domain_status"></div>
                  
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
<script src="js/chart.min.js"></script>
<script src="js/chart-custome.js"></script>
<!-- Custom script for all pages --> 
<script src="js/script.js"></script>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    $(".delete_item").click(function(){

    let contentid = $(this).attr('data-contentid');
    let locate = $(this).attr('data-locate');
    let id = $(this).attr('data-id');

    //alert(locate);

    $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

    $.post("../includes/backend/delete-book.php",
    {
        contentid: contentid,
        locate: locate,
        id: id
    },

    function(data, status){
        $("#domain_status").html(data);
    }

    /*function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
    }*/);
    });

</script>


<!-- Add this script after including Shepherd.js -->
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
        text: '<b>Book Listings:</b><hr> You can upload, edit and manage your book listings.',
        attachTo: { element: '#element1', on: 'right' },
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


    <?php 

				if($_SESSION['ADMIN_SUBSCRIPTION'] == "Free"){

         


				} else {

          echo "
          tour.addStep({
            id: 'step1',
            text: '<b>Manuscript Book:</b><hr>  You can upload your Manuscript Book to the SA Books Online Mobile App.',
            attachTo: { element: '#script', on: 'bottom' },
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
        });";

        echo "// Add tour steps
        tour.addStep({
            id: 'step1',
            text: '<b>Submit Audio Book:</b><hr>  You can upload your Audio Book to the SA Books Online Mobile App.',
            attachTo: { element: '#audio', on: 'bottom' },
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
        });";


				}
								
		?>


    
      // Add tour steps
      tour.addStep({
        id: 'step10',
        text: '<b>Add Book Listing:</b><hr> In this section you can upload your book to SA Books Online',
        attachTo: { element: '#addbook', on: 'left' },
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