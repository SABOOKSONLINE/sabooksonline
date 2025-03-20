
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SA Books, Book Stores, Bookstores, South African Bookstores">
    <meta name="keywords" content="SA Books, Book Stores, Bookstores, South African Bookstores, bookstores near me, bookstores johannesburg, book shops, book shops around me, book shops online">
    <meta name="author" content="SA Books Online">
    <title>Bookstores | SA Books, Book Stores, Bookstores, South African Bookstores </title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">


    <!-- GOOGLE WEB FONT -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" as="fetch" crossorigin="anonymous">
    <script type="text/javascript">
    !function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap",r="__3perf_googleFonts_c2536";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
    </script>

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/listing.css" rel="stylesheet">
    <link href="css/leaflet.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/contacts.css" rel="stylesheet">


    <style>
        #map {
            height: 50vh !important;
        }

        .info-div .image {
            width: 60px !important;
            height: 60px !important;
        }
    </style>

</head>

<body>
				
	<?php include 'includes/header-internal.php';?>
	
	<main class="breaker creator-mobile">
		<div class="collapse" id="collapseMap">
			<div id="map" class="map"></div>
		</div>
		<!-- /Map -->

		<div class="container margin_30_20">			
			<div class="row">
				<aside class="col-lg-3" id="sidebar_fixed">
					<a class="btn_map d-flex align-items-center justify-content-center" data-bs-toggle="collapse" href="#collapseMap" aria-expanded="false" aria-controls="collapseMap" ><span class="btn_map_txt" data-text-swap="Hide Map" data-text-original="View on Map">View on Map</span></a>
					<div class="type_delivery d-none">
						<ul class="clearfix">
						    <li>
						        <label class="container_radio">Delivery
						            <input type="radio" name="type_d" checked="checked">
						            <span class="checkmark"></span>
						        </label>
						    </li>
						    <li>
						        <label class="container_radio">Take away
						            <input type="radio" name="type_d">
						            <span class="checkmark"></span>
						        </label>
						    </li>
						</ul>
					</div>
					<!-- /type_delivery -->

					<a class="btn_map mobile btn_filters" data-bs-toggle="collapse" href="#collapseMap"><i class="icon_pin_alt"></i></a>
					<a href="#0" class="open_filters btn_filters"><i class="icon_adjust-vert"></i><span>Filters</span></a>
				
					<div class="filter_col">
						<div class="inner_bt clearfix">Filters<a href="#" class="open_filters"><i class="icon_close"></i></a></div>
						<div class="filter_type">
							<h4><a href="#filter_1" data-bs-toggle="collapse" class="opened">Sort By Province</a></h4>
							<div class="collaps" id="filter_1">
								<ul>
								    
								    <li>
								        <label class="container_check">KwaZulu-Natal
								            <input type="checkbox" id="province1" name="filter_sort" value="KZN">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								   
								     <li>
								        <label class="container_check">Limpopo
								            <input type="checkbox" id="province2" name="filter_sort" value="LP">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">Eastern Cape
								            <input type="checkbox" id="province3" name="filter_sort" value="EC">
								            <span class="checkmark"></span>
								        </label>
								    </li>

                                    <li>
								        <label class="container_check">Northern Cape
								            <input type="checkbox" id="province4" name="filter_sort" value="NC">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">North West
								            <input type="checkbox" id="province5" name="filter_sort" value="NW">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								   
								     <li>
								        <label class="container_check">Western Cape
								            <input type="checkbox" id="province6" name="filter_sort" value="WC">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">Mpumalanga
								            <input type="checkbox" id="province7" name="filter_sort" value="MP">
								            <span class="checkmark"></span>
								        </label>
								    </li>

                                    <li>
								        <label class="container_check">Gauteng
								            <input type="checkbox" id="province8" name="filter_sort" value="GP">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">Free State
								            <input type="checkbox" id="province9" name="filter_sort" value="FS">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								</ul>
							</div>
						</div>
						<!-- /filter_type -->
						
						<p><a href="register/account" class="btn_1 full-width" id="update_filter" target="_blank">List Your Book Store Now</a></p>
					</div>
				</aside>

				<div class="col-lg-9">
					<div class="row">
						<div class="col-12">
							<div class="col-12">

							<?php if(isset($_GET['k'])){
							//echo '<h2 class="title_small">Results for <b>'.$_GET['k'].'</b> :</h2>';
							
							}else {
								echo '<h2 class="title_small">BookStores </h2>';
							} ?>

							<div class="owl-carousel owl-theme categories_carousel_in listing mt-1 d-none">
							
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="" data-bs-toggle="tooltip" data-bs-placement="top">All Provinces</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="GP" data-bs-toggle="tooltip" data-bs-placement="top">Gauteng</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="EC" data-bs-toggle="tooltip" data-bs-placement="top">Eastern Cape</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="FS" data-bs-toggle="tooltip" data-bs-placement="top">Free State</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="KZN" data-bs-toggle="tooltip" data-bs-placement="top">KwaZulu Natal</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="LP" data-bs-toggle="tooltip" data-bs-placement="top">Limpopo</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="MP" data-bs-toggle="tooltip" data-bs-placement="top">Mpumalanga</a>
								
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="NC" data-bs-toggle="tooltip" data-bs-placement="top">Northern Cape</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="NW" data-bs-toggle="tooltip" data-bs-placement="top">North West</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="WC" data-bs-toggle="tooltip" data-bs-placement="top">Western Cape</a>
								
									
									
                                       
							</div>
						</div>
						</div>
					</div>
					<!-- /row -->

					<hr>

					<div class="promo d-non" style="background-image: url('sabooksonline.co.zaimg/pattern-banner.jpg');background-size: contain;">
						<h3>List your book store today!</h3>
						<p>Join Our Network of Book Stores, & Unlock Opportunities as a Book Store.</p>
						<i class="icon-food_icon_delivery"></i>
					</div>
					<!-- /promo -->

                    <hr>
					
                    <div class="row" id="results-container">
                        <!-- Results will be displayed here -->
                    </div>


                    <div class="pagination_fg d-nons" id="pagination">
                        <!-- Pagination links will be generated here -->
                    </div>
				</div>
				<!-- /col -->
			</div>		
		</div>
		<!-- /container -->
		
	</main>
	<!-- /main -->

	<<?php include 'includes/footer.php';?>

	<div id="toTop"></div><!-- Back to top button -->

	
	<!-- COMMON SCRIPTS -->
    <script src="js/common_scripts.min.js"></script>
    <script src="js/common_func.js"></script>
    <script src="assets/validate.js"></script>

    <!-- SPECIFIC SCRIPTS -->
    <script src="js/sticky_sidebar.min.js"></script>
    <script src="js/specific_listing.js"></script>

   

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjMV74EvcWHUwlYrHUIiXM9VVb0LXZzho&callback=initMap&v=weekly" defer></script>

	<script>
    $(document).ready(function() {
        // Function to update the results based on selected checkboxes
        function updateResults() {
            var selectedServices = [];
            var selectedProvinces = [];

            // Get selected service keywords
            $("input[id^='service']:checked").each(function() {
                selectedServices.push($(this).val());
            });

            // Get selected provinces
            $("input[id^='province']:checked").each(function() {
                selectedProvinces.push($(this).val());
            });

            // AJAX request to fetch and display filtered results
            $.ajax({
                type: "GET",
                url: "fetch_store.php", // Replace with the URL of your PHP script
                data: {
                    service: selectedServices,
                    province: selectedProvinces
                },
                success: function(data) {
                    // Update the results container with the retrieved data
                    $("#results-container").html(data);
                }
            });
        }

        // Bind the updateResults function to checkbox change events
        $("input[type='checkbox']").change(function() {
            updateResults();
        });

        // Initial update when the page loads
        updateResults();
    });
    </script>

<?php
// Include your database connection
include 'includes/database_connections/sabooks.php';

// Initialize an empty array to hold user data
$userDetails = array();

// Check if a specific service is requested (replace 'service_name' with the actual service name)
if (isset($_GET['service']) && !empty($_GET['service'])) {
    $requestedService = $_GET['service'];

    // Prepare and execute the SELECT query with JOIN and service filter
    $query = "SELECT u.STORE_ID, u.STORE_NAME, u.STORE_ADDRESS, s.STORE_LOGO, u.STORE_AMNEMITIES
            FROM book_stores u
            JOIN users s ON u.ADMIN_USERKEY = s.USERID
            WHERE s.SERVICE = '$requestedService'";
} else {
    // If no specific service is requested, retrieve all users and services
    $query = "SELECT u.ADMIN_NAME, u.ADMIN_GOOGLE, u.ADMIN_PROFILE_IMAGE, s.SERVICE, u.ADMIN_PROVINCE
            FROM users u
            JOIN services s ON u.ADMIN_USERKEY = s.USERID";
}

$result = $conn->query($query);

if ($result) {
    // Loop through the result set and accumulate user details including services
    while ($row = $result->fetch_assoc()) {
        $username = $row['ADMIN_NAME'];
        $address = $row['ADMIN_GOOGLE'];
        $logo = $row['ADMIN_PROFILE_IMAGE'];
        $service = $row['SERVICE'];
        $province = $row['ADMIN_PROVINCE'];

        // Create an associative array to represent each user
        $user = array(
            'username' => $username,
            'address' => $address,
            'logo' => $logo,
            'service' => $service,
            'province' => $province
        );

        // Add the user details to the $userDetails array
        $userDetails[] = $user;
    }

    // Free the result set
    $result->free();
} else {
    echo "Query execution failed: " . $conn->error;
}

// Close the database connection
$conn->close();

// Encode $userDetails as JSON and store it in a JavaScript variable
echo '<script>';
echo 'var usersData = ' . json_encode($userDetails) . ';';
echo '</script>';
?>




    <script>
      /**
       * @license
       * Copyright 2019 Google LLC. All Rights Reserved.
       * SPDX-License-Identifier: Apache-2.0
       */
      // This example displays a marker at the center of Australia.
      // When the user clicks the marker, an info window opens.
      function initMap() {
        

        <?php 

            include 'includes/backend/google/user-location.php';

        ?>

        // Try to get the user's current location
        if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
            const userLatLng = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            // Set the map center to the user's location
            map.setCenter(userLatLng);

            // Add a marker to the user's location
            new google.maps.Marker({
                position: userLatLng,
                map: map,
                title: 'Your Location'
            });
            },
            function(error) {
            console.log(error.message);
            }
        );
        } else {
        console.log('Geolocation is not supported by this browser.');
        }

        const current_location = { lat: <?php echo $latitude ?>, lng: <?php echo $longitude  ?> };
            
        <?php
            echo 'const map = new google.maps.Map(document.getElementById("map"), {
                mapId: "2f909951b58bd213",
                zoom: 13,
                center: current_location,
            });'
            
        ?>

            const image = {
            url: "https://cdn4.iconfinder.com/data/icons/web-ui-color/128/Marker-1024.png", // url
            scaledSize: new google.maps.Size(50, 50), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
                };

                const priceTag = document.createElement("div");

                priceTag.className = "map-profile";
                priceTag.textContent = '<img src="https://cdn4.iconfinder.com/data/icons/web-ui-color/128/Marker-1024.png">';



        <?php
            //DATABASE CONNECTIONS SCRIPT
            include 'includes/database_connections/sabooks.php';
            $sql = "SELECT * FROM users WHERE ADMIN_TYPE = 'book-store';";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == false){
            }else{
                while($row = mysqli_fetch_assoc($result)) {

                    $content = str_replace(' ', '_', $row['ADMIN_NAME']).'content';
                    $window = str_replace(' ', '_', $row['ADMIN_NAME']).'window';
					echo '
                    
                    const '.$content.' =
                    "<div><h1>'.$row['ADMIN_NAME'].'</h1></div>";
                    
                    const '.str_replace(' ', '_', $row['ADMIN_NAME']).' = { lat: '.$row['ADMIN_LATITUDE'].', lng: '.$row['ADMIN_LONGITUDE'].'};
                    
                    const '.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).' = new google.maps.Marker({
                        position: '.str_replace(' ', '_', $row['ADMIN_NAME']).',
                        map,
                        title: "'.$row['ADMIN_NAME'].'",
                        icon: {
                            url: "cms-data/profile-images/'.$row['ADMIN_PROFILE_IMAGE'].'", // URL of the image
                            scaledSize: new google.maps.Size(50, 50) // Size of the image
                          }
                      });
                      
                      '.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).'.addListener("click", () => {
                        $(".info-tab").hide();
                         $("#'.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).'_tab").toggle(300);
                         
                      });
                      
                      const '.$window.' = new google.maps.InfoWindow({
                        content: '.$content.',
                        ariaLabel: "Uluru",
                      });
              
              
                      ';
                    }
                }
        ?>

      }

      window.initMap = initMap;

       
    </script>

    <script>

function initMap() {
  // Create a map object and specify the DOM element for display
  var map = new google.maps.Map(document.getElementById('map'), {
    mapId: "2f909951b58bd213",
    center: {lat: -26.1328495, lng: 28.0718598}, // Set initial map center
    zoom: 12 // Set initial zoom level
  });

  // Add a marker to the map
  var marker = new google.maps.Marker({
    position: {lat: -26.1328495, lng: 28.0718598}, // Set marker position
    map: map // Set the map to display the marker
  });

  // Create an info window content

  <?php
            //DATABASE CONNECTIONS SCRIPT
            include 'includes/database_connections/sabooks.php';
            $sql = "SELECT * FROM users WHERE ADMIN_TYPE = 'book-store';";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == false){
            }else{
                while($row = mysqli_fetch_assoc($result)) {

                    $content = str_replace(' ', '_', $row['ADMIN_NAME']).'content';
                    $window = str_replace(' ', '_', $row['ADMIN_NAME']).'window';

                    $class = 'class="btn btn-primary" href="provider?provider='.$row['ADMIN_NAME'].'&id='.$row['ADMIN_ID'].'" style="background-color: #e54750;color: #fff;"';

                    $arrow = '<i class="fa fa-arrow-right"></i>';
					echo "var $content = '<a $class>Book Store Details $arrow</a>';";

                    echo 'var '.$window.' = new google.maps.InfoWindow({
                        content: '.$content.'
                      });
                    
                      // Add a click event listener to the marker
                      marker.addListener("click", function() {
                        '.$window.'.open(map, marker); // Open the info window when marker is clicked
                      });';
                    }
                }
        ?>


  
    

  // Create an info window
  
}


</script>



<script>
	//REMOVES SPACES IN OWL CAROUSEL

	$('a.0').parent('.owl-item').remove();

	//REMOVES SPACES IN OWL CAROUSEL

	$(document).ready(function(){

	$('#demo').html('<div class="d-flex justify-content-center"><div class="spinner-border text-secondary"  style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>')

	var filter_sort = $("input[name='filter_sort']:checked").val();

	var filter_category = [];
	$("input[name='category[]']:checked").each(function(i){
		filter_category[i] = $(this).val();
	});

	$('#demo').load('includes/backend/screens/book-store.php');
	});

	$(".0").remove();

	$('#update_filter').click(function(){

		$('#demo').html('<div class="d-flex justify-content-center"><div class="spinner-border text-secondary"  style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>')

		var filter_sort = $("input[name='filter_sort']:checked").val();

		var filter_category = [];
		$("input[name='category[]']:checked").each(function(i){
			filter_category[i] = $(this).val();
		});

		//alert(filter_sort + ' ' + filter_category);
		$('#demo').load('includes/backend/screens/book-store.php?sort='+ filter_sort +'&key='+ filter_category);
	})


	$('.cat-switch').click(function(){

	$('#demo').html('<div class="d-flex justify-content-center"><div class="spinner-border text-secondary"  style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>')

	//var filter_sort = $("input[name='filter_sort']:checked").val();
	var filter_sort = $(this).attr("data-src");
	//var filter_keywords = $("input[name='filter_keywords']").val();

	$('#demo').load('includes/backend/screens/book-store.php?sort='+ filter_sort +'&key=');
	})







</script>

<script>
	var countries = [ <?php
                                    //DATABASE CONNECTIONS SCRIPT
    include 'includes/database_connections/sabooks.php';
    $sql = "SELECT * FROM posts WHERE STATUS = 'active';";
    //$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == false){
    }else{
        while($row = mysqli_fetch_assoc($result)) {

        echo '"'.$row["TITLE"].'",';
        }
    }
    ?> " "];
	</script>

	<script src="js/custom.js"></script>


</body>
</html>