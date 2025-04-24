


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
<title>Edit Book Store</title>
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
.dashboard_sidebar_list .sidebar_list_item a.store{
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
                <h2>Edit Book Store Listing</h2>
                <p class="text">You may add a new Book Store and select the new options.</p>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="text-lg-end">
                <a href="#" class="ud-btn btn-dark">Save & Publish<i class="fal fa-arrow-right-long"></i></a>
              </div>
            </div>
          </div>

          <?php

          ini_set('display_errors', 1);
          ini_set('display_startup_errors', 1);
          error_reporting(E_ALL);


          //DATABASE CONNECTIONS SCRIPT
          include '../includes/database_connections/sabooks.php';

          $userkey = $_SESSION['ADMIN_USERKEY'];
          $sql = "SELECT * FROM book_stores WHERE USERID = '$userkey'";
          $result = mysqli_query($conn, $sql);
          while($row = mysqli_fetch_assoc($result)) {
            $store_name = $row['STORE_NAME'];
            $store_email = $row['STORE_EMAIL'];
            $store_address = $row['STORE_ADDRESS'];
            $store_province= $row['STORE_PROVINCE'];
            $store_number = $row['STORE_NUMBER'];
            $store_desc = $row['STORE_DESC'];
            $store_amnemities = $row['STORE_AMNEMITIES'];

            $timesString = $row['STORE_TIMES'];
          }



            ?>


          <form class="form-style1" id="upload_store">
          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Basic Information</h5>
                </div>
                <div class="col-xl-12">
                    <div class="row">
                    <div class="profile-box d-sm-flex align-items-center mb30 d-none">
                        <div class="profile-img mb20-sm">
                        <img id="imagePreview" class="w-100 rounded-circle wa-xs" src="https://sabooksonline.co.za/cms-data/profile-images/<?php  echo $_SESSION['ADMIN_PROFILE_IMAGE']?>" alt="" style="width: 100px !important;height:100px !important;">
                        </div>
                        <div class="profile-content ml20 ml0-xs">
                        <div class="d-flex align-items-center my-3">
                           <!-- <a href="#" class="tag-delt text-thm2"><span class="flaticon-delete text-thm2"></span></a>-->
                            <a href="#" class="upload-btn ml10"> 
                                
                            <input id="image-upload" type="file" name="store_logo" accept="image/*">
                            
                            <label for="image-upload" class="custom-file-label">Upload New Image <i class="fa fa-file-image"></i></label></a>
                        </div>
                        <p class="text mb-0">Max file size is 1MB, Minimum dimension: 330x300 And Suitable files are .jpg & .png</p>
                        </div>
                    </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Book Store name</label>
                          <input type="text" class="form-control" value="<?php echo $store_name; ?>" name="store_name" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Physical Address</label>
                          <input type="text" class="form-control" value="<?php echo $store_address; ?>" name="store_address" required>
                          <input type="hidden" name="store_province" id="province" value="<?php echo $store_province; ?>">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Telephone number</label>
                          <input type="number" class="form-control" value="<?php echo $store_number; ?>" name="store_number" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Email Address</label>
                          <input type="email" class="form-control" value="<?php echo $store_email; ?>" name="store_email" required>
                        </div>
                      </div>

                      <div class="col-sm-12">
                        <div class="mb20">
                          <div class="form-style1">
                            <label class="heading-color ff-heading fw500 mb10">Store Amnemities</label>
                            <div class="bootselect-multiselect">
                              <select class="selectpicker" name="store_amnemities[]" multiple>

                              <?php // Assuming $category contains the category data from the database
                                $langArray = explode("|", $store_amnemities);

                                // Create an associative array with category options
                                $langOptions = array();
                                foreach ($langArray as $langValue) {
                                    $langOptions[$langValue] = $langValue;
                                }

                                // Get selected categories if any
                                $selectedLang = isset($_POST['store_amnemities']) ? $_POST['store_amnemities'] : array();

                                // Create the multiple select input
                                
                                foreach ($langOptions as $langValue => $langLabel) {
                                    $selected = in_array($langValue, $selectedLang) ? 'selected' : '';
                                    echo '<option value="' . $langValue . '" selected>' . $langLabel . '</option>';
                                } ?>
                                <option value="Free Wifi">Free WiFi</option>
                                <option value="Cofee Shop">Cofee Shop</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                     
                      <div class="col-md-12">
                        <div class="mb10">
                          <label class="heading-color ff-heading fw500 mb10">A brief description about the Book Store</label>
                          <textarea cols="30" rows="6" placeholder="Description" name="store_desc" required><?php echo $store_desc; ?></textarea>
                        </div>
                      </div>

                      
                    </div>
                 
                </div>
              </div>

              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative d-">
                    <div class="bdrb1 pb15 mb25">
                    <h5 class="list-title">Operating Times</h5>
                    </div>
                    <div class="col-lg-12">

                    <div class="row">
                    <?php
                      $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                      
                      $timesArray = explode(' - ', $timesString);
                      $formattedTimes = array_chunk($timesArray, 2);

                      for ($dayIndex = 0; $dayIndex < count($daysOfWeek); $dayIndex++) {
                          $formattedDay = $daysOfWeek[$dayIndex];
                          $openingTime = $formattedTimes[$dayIndex][0];
                          $closingTime = $formattedTimes[$dayIndex][1];
                          ?>
                        <div class="col-lg-2">
                            <div class="mb20">
                                <div class="form-style1">
                                    <label class="heading-color ff-heading fw500 mb10 pt-5" for="<?= $formattedDay ?>"><?= $formattedDay ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb20">
                                <div class="form-style1">
                                    <label class="heading-color ff-heading fw500 mb10">Opening Time</label>
                                    <div class="bootselect-multiselect">
                                        <input class="form-control" type="time" name="store_times[]" value="<?= $openingTime ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb20">
                                <div class="form-style1">
                                    <label class="heading-color ff-heading fw500 mb10">Closing Time</label>
                                    <div class="bootselect-multiselect">
                                        <input class="form-control" type="time" name="store_times[]" value="<?= $closingTime ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                    <?php
                    }
                    ?>
                </div>
                </div>
                </div>
              
              <!--<div class="ps-widget bgc-white bdrs12 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb30">
                  <h5 class="list-title">Image Gallery</h5>
                </div>
                <div class="col-xl-9">
                  <div class="d-flex mb30">
                    <div class="gallery-item bdrs4 overflow-hidden">
                      <input type="file" name="store_images[]" accept="image/*" multiple required>
                    </div>
                  </div>
                  <p class="text">Max file size is 1MB, Minimum dimension: 330x300 And Suitable files are .jpg & .png</p>
                  
                </div>
              </div>-->

              <button type="submit" class="ud-btn btn-thm mt-2" id="reg_load">Create Page<i class="fal fa-arrow-right-long"></i></button>
            </div>
          </div>

          </form>

          <div id="reg_status"></div>
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

      $("#upload_store").on('submit',(function(e) {

            // Create a FormData object and include the entire form data
            var formData = new FormData($("#upload_store")[0]);

        e.preventDefault();

        $("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
          //showSwal('success-message');
        $.ajax({
          url: "../includes/backend/update-bookstore.php",
          type: "POST",
          data: formData,
          contentType: false,
            cache: false,
          processData:false,
          success: function(data)
        {
          $("#reg_load").html('Update BookStore');
          $("#reg_status").html(data);
          },
        error: function(){}
        });


      }));
      });
    
    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjMV74EvcWHUwlYrHUIiXM9VVb0LXZzho&libraries=places&callback=initAutocomplete" async defer></script>
    
    <script>
    var placeSearch, autocomplete;
    var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
    };
    
    function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
    {types: ['geocode']});
    
    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
    
    }
    
    function fillInAddress() {
    var place = autocomplete.getPlace();
    if (place && place.address_components) {
    var state = getAddressComponent(place, 'administrative_area_level_1', 'short_name');
    var address = place.formatted_address;
    console.log('State:', state);
    console.log('Address:', address);
    
    $('#province').val(state);
    }
    }
    
    function getAddressComponent(place, componentType, formatType) {
    for (var i = 0; i < place.address_components.length; i++) {
    var component = place.address_components[i];
    var componentTypes = component.types;
    
    for (var j = 0; j < componentTypes.length; j++) {
    if (componentTypes[j] === componentType) {
    return component[formatType];
    }
    }
    }
    return null;
    }
    
    
    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
    if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
    var geolocation = {
    lat: position.coords.latitude,
    lng: position.coords.longitude
    };
    
    
    var circle = new google.maps.Circle({
    center: geolocation,
    radius: position.coords.accuracy
    });
    autocomplete.setBounds(circle.getBounds());
    });
    }
    }
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

</body>

</html>