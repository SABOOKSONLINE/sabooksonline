


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
                <h2>Book Store Image Gallery</h2>
                <p class="text">You may add a new Book Store and select the new options.</p>
              </div>

              <div>

              </div>
            </div>
            <div class="col-lg-3 d-none">
              <div class="text-lg-end">
                <a href="#" class="ud-btn btn-dark">Save & Publish<i class="fal fa-arrow-right-long"></i></a>
              </div>
            </div>
          </div>

        


          <form class="form-style1" id="upload_store">
          <div class="row">
            <div class="col-xl-12">
              <div class="ps-widget bgc-white bdrs4 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb25">
                  <h5 class="list-title">Book Store Images</h5>
                </div>
                <div class="col-xl-12">
                    <div class="row">
                    <div class="profile-box d-sm-flex align-items-center mb30 d-none">
                        <div class="profile-img mb20-sm d-flex justify-content-start flex-wrap">
                        <?php
                        // Set up error reporting and database connection
                        ini_set('display_errors', 1);
                        ini_set('display_startup_errors', 1);
                        error_reporting(E_ALL);

                        include '../includes/database_connections/sabooks.php';

                        // Check for a valid user session
                        if (!isset($_SESSION['ADMIN_USERKEY'])) {
                            // Handle invalid session, redirect or display an error message
                            exit("Session not found or expired");
                        }

                        // Fetch images for a specific bookstore (replace $bookstore_id with the actual bookstore ID)
                        if (isset($_GET['contentid'])) {
                            $bookstore_id = $_GET['contentid']; // Replace this with the actual bookstore ID

                            $sql_fetch_images = "SELECT * FROM bookstores_images WHERE STOREID = ?";
                            $stmt_fetch_images = $conn->prepare($sql_fetch_images);
                            $stmt_fetch_images->bind_param("i", $bookstore_id);
                            $stmt_fetch_images->execute();
                            $result_images = $stmt_fetch_images->get_result();

                            if ($result_images->num_rows > 0) {
                                while ($row = $result_images->fetch_assoc()) {
                                    $image_path = $row['IMAGE'];
                                    $image_id = $row['ID'];
                                    // Display the image
                                    echo "<div><img src='$image_path' alt='Bookstore Image' style='width:300px;height:200px;object-fit:cover;border-radius:5%;margin:1%;'><button class='btn btn-danger text-white delete_item' data-contentid='$image_id' data-bookstoreid='$bookstore_id'>Delete</button></div>";
                                }
                            } else {
                                echo "No images found for this bookstore.";
                            }
 
                            $stmt_fetch_images->close();
                        } else {
                            // Handle case when contentid is not set in URL
                            echo "Content ID not provided.";
                        }
                        ?>


                        </div>
                        
                    </div>

                      <div id="domain_status"></div>
                    </div>
                 
                </div>
              </div>

              <div class="ps-widget bgc-white bdrs12 p30 mb30 overflow-hidden position-relative">
                <div class="bdrb1 pb15 mb30">
                  <h5 class="list-title">Add more images</h5>
                </div>
                <div class="col-xl-9">
                  <div class="d-flex mb30">
                    <div class="gallery-item bdrs4 overflow-hidden">
                      <input type="file" name="store_images[]" accept="image/*" multiple required>
                      <input type="hidden" name="contentid" value="<?php echo $_GET['contentid']?>" required>
                    </div>
                  </div>
                  <p class="text">Max file size is 1MB, Minimum dimension: 330x300 And Suitable files are .jpg & .png</p>
                  
                </div>
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

              <button type="submit" class="ud-btn btn-thm mt-2" id="reg_load">Upload Images<i class="fal fa-arrow-right-long"></i></button>
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
          url: "../includes/backend/scripts/functions/upload_images.php",
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



      $(".delete_item").click(function(){

      let contentid = $(this).attr('data-contentid');
      let bookstoreid = $(this).attr('data-bookstoreid');

      //alert(locate);

      $(this).html('<div class="spinner-border text-white" role="status"> <span class="sr-only">Loading...</span> </div>');

      $.post("../includes/backend/delete-bookstore-image.php", 
      {
          contentid: contentid,
          bookstoreid: bookstoreid
      },

      function(data, status){
          $("#domain_status").html(data);
      }

      /*function(data, status){
          alert("Data: " + data + "\nStatus: " + status);
      }*/);
      });


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