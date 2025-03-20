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
<title>Book Store</title>
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
.dashboard_sidebar_list .sidebar_list_item a.store{
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


            <div class="col-lg-8">
              <div class="dashboard_title_area">
                <h2>Manage Book Store</h2>
                <p class="text">You can manage, add or delete your pages.</p>
              </div>
            </div>

            

            <div class="col-lg-4">
              <div class="text-lg-end">

                <a type="submit" class="ud-btn btn-dark default-box-shadow2" id="addbookstore" href="add-bookstore">Add New Book Store<i class="fal fa-arrow-right-long"></i></a>

                <div id="reg_statu"></div>
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
                        <th scope="col">Book Store</th>
                        <th scope="col">Address</th>
                        <th scope="col">Email</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody class="t-body">

                    <?php
                                  
                                  //DATABASE CONNECTIONS SCRIPT
                                  include '../includes/database_connections/sabooks.php';
                                  $userkey = $_SESSION['ADMIN_USERKEY'];
                                $sql = "SELECT * FROM book_stores WHERE USERID = '$userkey' ORDER BY ID DESC;";
                                

								if(!$result = mysqli_query($conn, $sql)){

									echo "<div class='alert alert-info border-none'>You currently have no content uploaded.<a href='dashboard-add-book'> Add New Service</a>.</div";

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
                                                $service_status = '<a href="edit-bookstore?contentid='.$row['ID'].'" class="icon me-2 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="flaticon-pencil"></span></a>';
                                            }
	
											echo '<tr>
                                            <th scope="row">
                                              <div class="freelancer-style1 p-0 mb-0 box-shadow-none">
                                                <div class="d-lg-flex align-items-lg-center">
                                                <img class="mx-auto" src="https://sabooksonline.co.za/'.str_replace('../../../../','', $row['STORE_LOGO']).'" data-src="https://sabooksonline.co.za/'.str_replace('../../../../','', $row['STORE_LOGO']).'" alt="" width="50px">
                                                  <div class="details ml15 ml0-md mb15-md">
                                                    <h5 class="title mb-2">'.$row['STORE_NAME'].'</h5>
                                                  </div>
                                                </div>
                                              </div>
                                            </th>
                                            <td class="vam"><span class="fz15 fw400"><b>'.ucwords($row['STORE_ADDRESS']).'</b></span></td>
                                            
                                            <td class="vam"><span><b>'.$row['STORE_EMAIL'].'</b></span></td>
                                            '.$status.'
                                            <td>
                                              <div class="d-flex">

                                              '.$service_status .'
                                                
                                                <a data-bs-toggle="modal" data-bs-target="#exampleModal'.$row['ID'].'" class="icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="flaticon-delete"></span></a>
                                              </div>
                                            </td>

                                            <!-- Modal -->
														<div class="modal fade" id="exampleModal'.$row['ID'].'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
														<div class="modal-dialog modal-dialog-centered border-none" style="border: none !important;">
															<div class="modal-content border-none" style="border: none !important;">
															<div class="modal-header">
																<h5 class="modal-title" id="exampleModalLabel">Are You Sure You want to delete the book?</h5>
																<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
															</div>
															<div class="modal-body">
																You are about to delete <b>'.ucwords($row['STORE_NAME']).'</b>, this action cannot be undone once complete. Your book store will be removed from this platform instantly.
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancel</button>
																<a href="#" type="button" class="btn btn-danger text-white delete_item" data-contentid="'.$row['ID'].'">Continue <small class="icon_trash"></small></a>
															</div>
															</div>
														</div>
														</div>
                                          </tr>

                                          <div class="alert alert-info w-100">To Update/Upload images to your Book Store please <a href="edit-bookstore-images?contentid='.$row['ID'].'"><b>click here</b></a></div>
													
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
<!-- Custom script for all pages --> 
<script src="js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    $(".delete_item").click(function(){

    let contentid = $(this).attr('data-contentid');

    //alert(locate);

    $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

    $.post("../includes/backend/delete-store.php",
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
        text: '<b>Book Store:</b><hr> You can upload, edit and manage your book store listings.',
        attachTo: { element: '#element11', on: 'right' },
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
        text: '<b>Add BookStore:</b><hr> In this section you can upload your book store to SA Books Online',
        attachTo: { element: '#addbookstore', on: 'bottom' },
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