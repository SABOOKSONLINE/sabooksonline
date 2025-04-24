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
<title>Add Book</title>
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

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->


<script src="https://jsuites.net/v4/jsuites.js"></script>
<script src="https://jsuites.net/v4/jsuites.webcomponents.js"></script>
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">


<style>
    .dashboard_sidebar_list .sidebar_list_item a:hover,
.dashboard_sidebar_list .sidebar_list_item a:active,
.dashboard_sidebar_list .sidebar_list_item a:focus,
.dashboard_sidebar_list .sidebar_list_item a.listings{
  background-color: #222222;
  color: #ffffff;
}



   /* Hide the actual file input */
   #image-upload {
        display: none;
    }

    /* Style the custom file input label */
    .custom-file-label {
        display: inline-block;
        padding: 8px 12px;
        background-color: #5BBB7B;
        color: #fff;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    /* Change styles when hovering over the label */
    .custom-file-label:hover {
        background-color: #5BBB7C;
    }

    .upload-btn {
        background-color: #5BBB7B !important;
    }

    .project-attach {
        background-color: #f3f3f3 !important;
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
                <h2>Add Book Listing</h2>
                <p class="text">You may add a new book and select the new options.</p>
              </div>
            </div>
               
          </div>
          <form class="form-style1" id="upload-book" enctype="multipart/form-data">
            <div class="row">
              <div class="col-xl-12">
                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                  <div class="bdrb1 pb15 mb25">
                    <h5 class="list-title">Basic Information</h5>
                  </div>
                  <div class="col-xl-12">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Book Title</label>
                            <input type="text" class="form-control" placeholder="" name="book_title" required>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Price <small class="text text-danger"><i class="fa fa-info-circle"></i> (NB. For free books leave empty)</small></label>
                            <input type="number" class="form-control" placeholder="" name="book_price">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="mb20">
                            <div class="form-style1">
                              <label class="heading-color ff-heading fw500 mb10">Book Category(s)</label>
                              <div class="bootselect-multiselect">
                                <select class="selectpicker" multiple name="book_category[]" required>
                                <?php
                                                  //DATABASE CONNECTIONS SCRIPT
                                  include '../includes/database_connections/sabooks.php';
                                  $sql = "SELECT * FROM category;";
                                  $result = mysqli_query($conn, $sql);
                                  if(mysqli_num_rows($result) == false){
                                    }else{
                                      while($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$row['CATEGORY'].'">'.$row['CATEGORY'].'</option>';
                                        }
                                      }
                                  ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>


                        <div class="col-sm-6"> 
                          <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Purchase Link</label>
                            <input type="link" class="form-control" name="book_website"  required>
                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">ISBN Number</label>
                            <input type="text" class="form-control" name="book_isbn"  required>
                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Date Published</label>
                            <input type="text" class="form-control datepicker" name="book_date_published" required>
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="mb10">
                            <label class="heading-color ff-heading fw500 mb10">Book Description <small>(Maximum of 600 characters allowed)</small></label>
                            <textarea cols="30" rows="6" placeholder="Description" maxlength="600" name="book_desc"  required></textarea>
                          </div>
                        </div>
                        <!--<div class="col-md-12">
                          <div class="text-start">
                            <a class="ud-btn btn-thm" href="page-contact.html">Save<i class="fal fa-arrow-right-long"></i></a>
                          </div>
                        </div>-->
                      </div>
                  </div>
                </div>

                <div class="col-xl-12">
                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                  <div class="bdrb1 pb15 mb25">
                    <h5 class="list-title">Miscellaneous Information</h5>
                  </div>
                  <div class="col-xl-12">
                    
                      <div class="row">
                        
                        <div class="col-sm-12">
                          <div class="mb20">
                            <div class="form-style1">
                              <label class="heading-color ff-heading fw500 mb10">Language Variations</label>
                              <div class="bootselect-multiselect">
                                <select class="selectpicker" name="book_lang[]" multiple required>
                                <option value="English">English </option>
                                <option value="Afrikaans">Afrikaans </option>
                                      <option value="Zulu" >Zulu </option>
                                      <option value="Venda" >Venda</option>
                                      
                                      <option value="Southern Sotho">Southern Sotho</option>
                                      <option value="Northern Sotho">Northern Sotho</option>
                                      <option value="Swati">Swati</option>
                                      <option value="Tswana">Setswana</option>
                                      <option value="Xhosa">Xhosa</option>
                                      <option value="Tsonga">Tsonga</option>
                                      <option value="Ndebele">Ndebele</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-12">
                          <div class="mb20">
                            <div class="form-style1">
                              <label class="heading-color ff-heading fw500 mb10">Which Book-Store is your book available in?</label>
                              <div class="bootselect-multiselect">
                                <select class="selectpicker" name="book_stores[]"  multiple>

                                <?php
                                  $sql = "SELECT * FROM book_stores WHERE STATUS = 'Active'";
                                  
                                  $result = mysqli_query($conn, $sql);
                                  if(mysqli_num_rows($result) == false){
                                    echo '<div class="alert alert-warning">There are Book Stores listed at the moment</div>';
                                  }else{
                                    echo '<hr>';
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo '<option  value="'.$row['STORE_NAME'].'">'.$row['STORE_NAME'].' - '.$row['STORE_ADDRESS'].'</option>';
                                    }
                                  }
                                ?>
                                          
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
 
                        <div class="col-sm-12">
                          <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Original Author (Seperate by comas if multiple)</label>
                            <input type="text" class="form-control" name="author" required>
                          </div>
                        </div>
                        
                      </div>
                    
                  </div>
                </div>

                <div class="ps-widget bgc-white bdrs12 p30 mb30 overflow-hidden position-relative">
                  <div class="bdrb1 pb15 mb30">
                    <h5 class="list-title">Book Cover</h5>
                  </div>
                  

                  <div class="row">
                  
                <div class="col-6 col-xl-2">
                    <div class="project-attach">
                    <img id="imagePreview" class="w-100 wa-xs" alt="" style="width: 200px !important;">
                    </div>
                  </div>
                    <div class="profile-content ml20 ml0-xs">
                         <div class="d-flex align-items-center my-3">
                            <!-- <a href="#" class="tag-delt text-thm2"><span class="flaticon-delete text-thm2"></span></a>-->
                            <a href="#" class="upload-btn ml10"> 
                                    
                                <input id="image-upload" type="file" name="book_cover" accept="image/*" required>
                                
                                <label for="image-upload" class="custom-file-label">Upload Book Cover <i class="fa fa-file-image"></i></label>
                            </a>
                        </div>
                        <p class="text mb-0 alert alert-danger w-50">Max file size is 1MB, 2 Dimensional cover only! And Suitable files are .jpg & .png</p>
                    </div>

                </div>
                </div>

                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                  <div class="bdrb1 pb15 mb25">
                    <h5 class="list-title">Stock availability  <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" title="The stock availability refers to the books that can be sold on your SA Books website, if In-stock the bookm can be purchased if not then it cannot be purchased."></i></h5>
                  </div>
                  <div class="col-xl-12">
                  
                      <div class="row">
                        <div class="col-sm-12">
                            <div class="bootselect-multiselect">
                                    <select class="selectpicker" name="stock" >
                                        <option value="In-Stock">In-Stock</option>
                                        <option value="Out Of Stock">Out Of Stock</option>
                                    </select>
                            </div>
                        </div>
                      </div>
                      <hr>
                  <hr>
                  <hr>
                  <hr>
                  <hr>
                  <hr>
                  </div>
                </div>

                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                  <div class="bdrb1 pb15 mb25">
                    <h5 class="list-title">Save listing as</h5>
                  </div>
                  <div class="col-xl-12">
                  
                      <div class="row">
                        <div class="col-sm-12">
                        <div class="bootselect-multiselect">
                                  <select class="selectpicker" name="boo_status" >
                                    <option value="Active">Publish</option>
                                    <option value="Draft">Draft</option>
                                  </select>
                                </div>
                        </div>

                        <hr>
                      
                        <div class="col-md-12">
                          <div class="text-start">
                            <button class="ud-btn btn-thm" type="submit" id="reg_load">List Your Book<i class="fal fa-arrow-right-long"></i></button>
                          </div>
                        </div>
                      </div>
                    
                  </div>
                </div>

                

                <div id="reg_status"></div>

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


<script>

		//$("#other").show();


$(document).ready(function() {

	$("#upload-book").on('submit',(function(e) {

        // Create a FormData object and include the entire form data
        var formData = new FormData($("#upload-book")[0]);

		e.preventDefault();

		$("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
		
			//showSwal('success-message');
		$.ajax({
			url: "../includes/backend/upload-book.php",
			type: "POST",
			data: formData,
			contentType: false,
				cache: false,
			processData:false,
			success: function(data)
		{
			$("#reg_load").html('Publish Book');
			$("#reg_status").html(data);
			},
		error: function(){}
		});


	}));
});
		//publiish story upload code

        

</script>


 <script>
        $(document).ready(function() {
            // Initialize Datepicker with custom date format
            $('.datepicker').datepicker({
                format: 'dd MM yyyy', // Use 'dd MM yyyy' for day, month name, and year
                autoclose: true,
                todayHighlight: true
            });

            // Initialize Timepicker
            $('.timepicker').timepicker({
                showMeridian: false
            });
        });
</script>

<script>
    const fileInput = document.getElementById('image-upload');
    const imagePreview = document.getElementById('imagePreview');

    fileInput.addEventListener('change', function() {
        const selectedFile = fileInput.files[0];

        if (selectedFile) {
            const reader = new FileReader();

            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block';
            };

            reader.readAsDataURL(selectedFile);
        } else {
            imagePreview.src = '#';
            imagePreview.style.display = 'none';
        }
    });
</script>


<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

</body>

</html>