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
<title>Add Event</title>
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
.dashboard_sidebar_list .sidebar_list_item a.events{
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
                <h2>Create New Event</h2>
                <p class="text">You may add a new event and select the new options.</p>
              </div>
            </div>
            <!--<div class="col-lg-3">
              <div class="text-lg-end">
                <a href="#" class="ud-btn btn-dark">Save & Publish<i class="fal fa-arrow-right-long"></i></a>
              </div>
            </div>-->
          </div>
          <form class="form-style1" id="upload-event" enctype="multipart/form-data">
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
                          <label class="heading-color ff-heading fw500 mb10">Event Title</label>
                          <input type="text" class="form-control" name="event_title">
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Event Address</label>
                          <input type="text" class="form-control" id="autocomplete" name="event_address">

                        <input type="hidden" name="event_province" id="province">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Event Email</label>
                          <input type="text" class="form-control" name="event_email">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Event Telephone</label>
                          <input type="text" class="form-control" name="event_number">
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="mb10">
                          <label class="heading-color ff-heading fw500 mb10">Event Description</label>
                          <textarea cols="30" rows="6" placeholder="Description" name="event_desc"></textarea>
                        </div>
                      </div>

                      <div class="col-sm-12">
                        <div class="mb20">
                          <div class="form-style1">
                            <label class="heading-color ff-heading fw500 mb10">How can attendats attend this event?</label>
                            <div class="bootselect-multiselect">
                              <select class="selectpicker" name="event_type[]" multiple>
                              <option value="In-Person" selected>In-Person</option>
                                <option value="Virtual">Virtual</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="col-sm-12">
                        <div class="mb20">
                          <div class="form-style1  overflow-visible position-relative">
                            <label class="heading-color ff-heading fw500 mb10">Type Of Event</label>
                            <div class="bootselect-multiselect">
                              <select class="selectpicker" name="services[]" multiple>

                              <?php
                              //DATABASE CONNECTIONS SCRIPT
                                  include '../includes/database_connections/sabooks.php';
                                  $sqlc = "SELECT * FROM events_type WHERE STATUS = 'Active'";
                                  $resultc = mysqli_query($conn, $sqlc);
                                  if(mysqli_num_rows($resultc) == false){
                                    }else{
                                      while($rowc = mysqli_fetch_assoc($resultc)) {
                                        echo '<option value="'.$rowc['CATEGORY'].'">'.$rowc['CATEGORY'].'</option>';
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
                          <label class="heading-color ff-heading fw500 mb10">Any Other Type Event</label>
                          <input type="text" class="form-control" name="reg_service_other">
                        </div>
                      </div>

                    </div>
                </div>
              </div>

              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Event Times</h5>
                </div>
                <div class="col-xl-8">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10 ">Start Date</label>
                          <input type="date" class="form-control" name="dates_start">
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10 ">End Date</label>
                          <input type="date" class="form-control" name="dates_end">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10">Event Start Time</label>
                          <input type="time" class="form-control" name="event_start_time">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="mb20">
                          <label class="heading-color ff-heading fw500 mb10 ">Event End Time</label>
                          <input type="time" class="form-control" name="event_end_time">
                        </div>
                      </div>

                    </div>
                </div>
              </div>

              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-visible position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Advert Duration</h5>
                </div>
                <div class="col-xl-8">
                    <div class="row">
                      
                    <div class="col-sm-12">
                        <div class="mb20">
                          <div class="form-style1">
                            <label class="heading-color ff-heading fw500 mb10">How long would you like to show your event?</label>
                            <div class="bootselect-multiselect" style="z-index:1000 !important;">
                              <select class="selectpicker" name="child">

                              <?php

                              if($_SESSION['ADMIN_SUBSCRIPTION'] == 'Free'){
                                echo '<option value="1">1 Day</option>
                                <option value="2">2 Day</option>
                                <option value="3">3 Days</option>';
                              }elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Standard'){
                                echo ' <option value="1">1 Day</option>
                                <option value="2">2 Day</option>
                                <option value="3">3 Days</option>
                                <option value="7">1 Week</option>
                                <option value="14">2 Weeks</option>';
                              }elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Premium'){
                                echo '<option value="1">1 Day</option>
                                <option value="2">2 Day</option>
                                <option value="3">3 Days</option>
                                <option value="7">1 Week</option>
                                <option value="14">2 Weeks</option>
                                <option value="30">1 Month</option>';
                              }elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Deluxe'){
                                echo '<option value="1">1 Day</option>
                                <option value="2">2 Day</option>
                                <option value="3">3 Days</option>
                                <option value="7">1 Week</option>
                                <option value="14">2 Weeks</option>
                                <option value="30">1 Month</option>
                                <option value="60">2 Months</option>
                                <option value="90">3 Months</option>';
                              }

                                ?>
                                
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                </div>
              </div>
              <div class="ps-widget bgc-white bdrs12 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Upload Event Cover</h5>
                </div>
                <div class="row">
                <div class="d-flex mb30">
                      <div class="gallery-item me-3 bdrs4 overflow-hidden position-relative">
                        <img class="w-100" id="imagePreview" src="https://sabooksonline.co.za/cms-data/book-covers/<?php echo $cover;?>" style="width: 500px !important"  alt="">
                        
                      </div>
                      
                    </div>
                    
                    <p class="text">Max file size is 1MB, Minimum dimension: 1000x300 And Suitable files are .jpg & .png</p>

                    <div class="col-lg-3">
                    <a href="#" class="upload-btn ml10"> 
                                    
                                <input id="image-upload" type="file" name="event_cover" accept="image/*">
                                
                                <label for="image-upload" class="custom-file-label">Upload Event Cover <i class="fa fa-file-image"></i></label>
                            </a></div>
                </div>
                
              </div>

              <div class="text-start">
                  <button class="ud-btn btn-thm" type="submit" id="reg_load">Save & Publish<i class="fal fa-arrow-right-long"></i></button>
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

<!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>


<script>
$(document).ready(function() {
    // Initialize Datepicker
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
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
    
    //$("#other").show();
    
    $('.timepicker').timepicker({
        timeFormat: 'h:mm p',
        interval: 60,
        minTime: '12:00am',
        maxTime: '11:00pm',
        defaultTime: '11',
        startTime: '1:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    $(document).ready(function() {

        $("#upload-event").on('submit',(function(e) {

            // Create a FormData object and include the entire form data
            var formData = new FormData($("#upload-event")[0]);

            e.preventDefault();

            $("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
                //showSwal('success-message');
            $.ajax({
                url: "../includes/backend/member-add-event.php",
                type: "POST",
                data: formData,
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#reg_load").html('Publish Event');
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