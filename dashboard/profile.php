<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
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
<title>Profile</title>         
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
.dashboard_sidebar_list .sidebar_list_item a.profile{
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
</style>
</head>
<body>
<div class="wrapper">
  <div class="preloader"></div>
  
  <!-- Main Header Nav -->
  <?php include 'includes/header-dash-main.php';?>

  <div class="dashboard_content_wrapper">
    <div class="dashboard dashboard_wrapper pr30 pr0-xl">

      <!-- MAIN DASHBOARD HEADER--->
      <?php include 'includes/header-dash.php';?>
      <!-- MAIN DASHBOARD HEADER--->


      <div class="dashboard__main pl0-md">
        <div class="dashboard__content hover-bgc-color">
          <div class="row pb40">
           <?php include 'includes/mobile-guide.php';?>

           
							<hr>
							<?php if(isset($_GET['result'])){
							if($_GET['result'] == "success"){
								echo "<div class='alert alert-success border-none'>Your Profile has been successfully updated!.</div>";
								} else if($_GET['result'] == "failed") {
									echo "<p class='text-warning'>Your <b>Book</b> could not be <b>Deleted</b>!</p>";
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
            <div class="col-lg-9">
              <div class="dashboard_title_area">
                <h2>My Profile</h2>
                
              </div>
            </div>
          </div>
          <form  id="update" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-12">
                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                    <div class="bdrb1 pb15 mb25">
                    <h5 class="list-title">Profile Details</h5>
                    </div>
                    <div class="col-xl-7">
                    <div class="profile-box d-sm-flex align-items-center mb30">
                        <div class="profile-img mb20-sm">
                        		<?php
							
							$string = $_SESSION['ADMIN_PROFILE_IMAGE'];

						if (strpos($string, 'googleusercontent.com') !== false) {
								echo '<img id="imagePreview" class="w-100 rounded-circle wa-xs" src="'.$_SESSION['ADMIN_PROFILE_IMAGE'].'" alt="" style="width: 100px !important;height:100px !important;">';
						} else {
							echo '<img id="imagePreview" class="w-100 rounded-circle wa-xs" src="https://sabooksonline.co.za/cms-data/profile-images/'.$_SESSION['ADMIN_PROFILE_IMAGE'].'" alt="" style="width: 100px !important;height:100px !important;">';
							
							
						}
							?>
                        
                        </div>
                        <div class="profile-content ml20 ml0-xs">
                        <div class="d-flex align-items-center my-3">
                           <!-- <a href="#" class="tag-delt text-thm2"><span class="flaticon-delete text-thm2"></span></a>-->
                            <a href="#" class="upload-btn ml10"> 
                                
                            <input id="image-upload" type="file" name="reg_profile" accept="image/*" >
                            
                            <label for="image-upload" class="custom-file-label">Upload New Image <i class="fa fa-file-image"></i></label></a>
                        </div>
                        <p class="text mb-0">Max file size is 1MB, Minimum dimension: 330x300 And Suitable files are .jpg & .png</p>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-12">
                    <form class="form-style1">
                        <div class="row">
                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Username</label>
                            <input type="text" class="form-control" value="<?php  echo $_SESSION['ADMIN_NAME']?>" name="reg_name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Email Address</label>
                            <input type="email" class="form-control" value="<?php  echo $_SESSION['ADMIN_EMAIL']?>" name="reg_email" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Phone Number</label>
                            <input type="text" class="form-control" name="reg_number"  value="<?php  echo $_SESSION['ADMIN_NUMBER']?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Website</label>
                            <input type="text" class="form-control" name="reg_website"  value="<?php  echo $_SESSION['ADMIN_WEBSITE']?>">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Physical Address</label>
                            <input type="text" class="form-control" name="reg_address" id="autocomplete" onFocus="geolocate()"  value="<?php  echo $_SESSION['ADMIN_GOOGLE']?>">
                            <input type="hidden" name="reg_province" class="form-control required" placeholder="Physical Address" id="province" value="<?php echo $_SESSION['ADMIN_PROVINCE'];?>">
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb10">
                            <label class="heading-color ff-heading fw500 mb10">Author Bio</label>
                            <textarea cols="30" rows="6" name="reg_bio"><?php  echo $_SESSION['ADMIN_BIO']?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 d-none">
                            <div class="text-start">
                            <a class="ud-btn btn-thm" href="#">Save<i class="fal fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                        </div>
                    </form>
                    </div>
                </div>

                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                    <div class="bdrb1 pb15 mb25">
                    <h5 class="list-title">Social Media Links</h5>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Facebook Link</label>
                            <input type="text" class="form-control" name="reg_facebook" placeholder="" value="<?php  echo $_SESSION['ADMIN_FACEBOOK']?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Instagram Link</label>
                            <input type="text" class="form-control" name="reg_instagram" placeholder="" value="<?php  echo $_SESSION['ADMIN_INSTAGRAM']?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Twitter Link</label>
                            <input type="text" class="form-control" type="text" name="reg_twitter" placeholder="" value="<?php  echo $_SESSION['ADMIN_TWITTER']?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Linkedin Link</label>
                            <input type="text" class="form-control" name="reg_linkedin" placeholder="" value="<?php  echo $_SESSION['ADMIN_LINKEDIN']?>">
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
               
                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative d-none">
                    <div class="bdrb1 pb15 mb30 d-sm-flex justify-content-between">
                    <h5 class="list-title">Services</h5>
                    <!--<a href="#" class="add-more-btn text-thm"><i class="icon far fa-plus mr10"></i>Add Aducation</a>-->
                    </div>
                    <div class="position-relative">
                    


                        <div class="col-sm-12">
                            <div class="mb20">
                            <div class="form-style1">
                                <label class="heading-color ff-heading fw500 mb10">Services You Offer</label>
                                <div class="bootselect-multiselect">
                                <select class="selectpicker" multiple>
                                    <option>Book Reading</option>
                                    <option>Editing</option>
                                    <option>Distribution</option>
                                    <option>Proof Reading</option>
                                </select>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb20">
                            <label class="heading-color ff-heading fw500 mb10">Other Service</label>
                            <input type="text" class="form-control" placeholder="">
                            </div>
                        </div>
                        


                    <div class="text-start d-none">
                        <a class="ud-btn btn-thm" href="#">Update Services<i class="fal fa-arrow-right-long"></i></a>
                    </div>
                    </div>
                </div>

                
                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                    <div class="bdrb1 pb15 mb25">
                    <h5 class="list-title">Change password</h5>
                    </div>
                    <div class="col-lg-7">
                    <div class="row">
                        <div class="row">
                            <div class="col-sm-12">
                            <div class="mb20">
                                <label class="heading-color ff-heading fw500 mb10">New Password</label>
                                <input type="password" class="form-control password" id="password" name="reg_password" placeholder="********">
                            </div>
                            </div>
                            <div class="col-sm-12">
                            <div class="mb20">
                                <label class="heading-color ff-heading fw500 mb10">Confirm New Password</label>
                                <input type="password" class="form-control password" id="password2" name="reg_confirm_password" placeholder="********">
                            </div>
                            </div>
                            <div class="col-md-12 d-none">
                            <div class="text-start">
                                <a class="ud-btn btn-thm" href="#">Change Password<i class="fal fa-arrow-right-long"></i></a>
                            </div>
                            </div>

                            <div class="switch-style1">
                                <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="showPassword">
                                <label class="form-check-label" for="flexSwitchCheckDefault2">Show Password</label>
                                </div>
                            </div>
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                            var passwordFields = document.querySelectorAll('input[type="password"]');
                            var showPasswordCheckbox = document.getElementById('showPassword');
                            
                            showPasswordCheckbox.addEventListener('change', function() {
                                var passwordType = showPasswordCheckbox.checked ? 'text' : 'password';
                                passwordFields.forEach(function(passwordField) {
                                passwordField.type = passwordType;
                                });
                            });
                            });
                            </script>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative d-none">
                    <div class="col-lg-7">
                    <div class="row">
                        <div class="bdrb1 pb15 mb25">
                        <h5 class="list-title">Delete Account</h5>
                        </div>
                    
                        <div class="row">
                            <div class="col-sm-12">
                            <h6>Close account</h6>
                            <p class="text">Warning: If you close your account, you will be unsubscribed from all your 5 courses, and will lose access forever.</p>
                            </div>
                            <div class="col-sm-6">
                            <div class="mb20">
                                <label class="heading-color ff-heading fw500 mb10">Enter Password</label>
                                <input type="text" class="form-control" placeholder="********">
                            </div>
                            <div class="text-start">
                                <a class="ud-btn btn-thm btn-danger" href="page-contact.html">Request Account Deletion<i class="fal fa-arrow-right-long"></i></a>
                            </div>
                            </div>
                        </div>
                    
                    </div>
                    </div>
                </div>
                

                <div class="col-md-12 d-">
                            <div class="text-start">
                                <button class="ud-btn btn-thm" id="reg_load" type="submit">Update Profile<i class="fal fa-arrow-right-long"></i></button>
                            </div>
                            </div>
                </div>
            </div>
          </form>
        </div>

        <div id="reg_status"></div>

        

      <!-- MAIN DASHBOARD HEADER--->
      <?php include 'includes/footer-dash.php';?>
      <!-- MAIN DASHBOARD HEADER--->
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
    $(document).ready(function() {

        $("#update").on('submit',(function(e) {

            // Create a FormData object and include the entire form data
            var formData = new FormData($("#update")[0]);

            e.preventDefault();

            $("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            $.ajax({
                url: "../includes/backend/update-profile.php",
                type: "POST",
                data: formData,
                contentType: false,
                    cache: false,
                processData:false,
                success: function(data)
            {
                $("#reg_load").html('Update Profile');
                $("#reg_status").html(data);
                },
            error: function(){}
            });


        }));
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
</body>

</html>