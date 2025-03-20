
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SA Books Online Services, South African Books Services, South African Literature Services, SA Literature Services">
	<meta name="keywords" content="SA Books Online Services, South African Books Services, South African Literature Services, SA Literature Services">
    <meta name="author" content="SA Books Online">
    <title>SA Books Online Services, SA Books Online Services, South African Books Services, South African Literature Services, SA Literature - The Gateway To South African Literature</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">


    <!-- GOOGLE WEB FONT -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" as="fetch" crossorigin="anonymous">
    <script type="text/javascript">
    !function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap",r="__3perf_googleFonts_c2536";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
    </script>
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-V7MRDHEHSZ"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-V7MRDHEHSZ');
		gtag('config', 'AW-11379832900');
	</script>

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/listing.css" rel="stylesheet">
    <link href="css/leaflet.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">


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
	
	<main class="breaker">
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
							<div class="collapse " id="filter_1">
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
						<div class="filter_type">
							<h4><a href="#filter_2" data-bs-toggle="collapse" class="open">Categories</a></h4>
							<div class="collapse show" id="filter_2">
								<ul>
								    <li>
								        <label class="container_check">Editors<small></small>
								            <input type="checkbox" id="service1" name="category[]" value="Editor">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">Proof Readers <small></small>
								            <input type="checkbox" id="service2" name="category[]" value="Proof-Reader">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">Illustrator <small></small>
								            <input type="checkbox" id="service3" name="category[]" value="Illustrator">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">Printers <small></small>
								            <input type="checkbox" id="service4" name="category[]" value="Printer">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">Distributors <small></small>
								            <input type="checkbox" id="service5" name="category[]" value="Distributor">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    <li>
								        <label class="container_check">Writers <small></small>
								            <input type="checkbox" id="service6" name="category[]" value="Writer">
								            <span class="checkmark"></span>
								        </label>
								    </li>

                                    <li>
								        <label class="container_check">Reviewers <small></small>
								            <input type="checkbox" id="service7" name="category[]" value="Reviewer">
								            <span class="checkmark"></span>
								        </label>
								    </li>

                                    <li>
								        <label class="container_check">Speakers <small></small>
								            <input type="checkbox" id="service8" name="category[]" value="Speaker">
								            <span class="checkmark"></span>
								        </label>
								    </li>
								    
								</ul>
							</div>
						</div>
						<!-- /filter_type -->
						<div class="filter_type d-none">
							<h4><a href="#filter_3" data-bs-toggle="collapse" class="closed">Distance</a></h4>
							<div class="collapse" id="filter_3">
								<div class="distance"> Radius around selected destination <span></span> km</div>
								<div class="add_bottom_25"><input type="range" min="10" max="50" step="5" value="20" data-orientation="horizontal"></div>
							</div>
						</div>
                        <hr>
						
                        <p><a href="register/account" class="btn_1 full-width" id="update_filter" target="_blank">List Your Services Now</a></p>
					</div>
				</aside>

				<div class="col-lg-9">
					<div class="row">
						<div class="col-12">
							<div class="col-12">

							<?php if(isset($_GET['k'])){
							//echo '<h2 class="title_small">Results for <b>'.$_GET['k'].'</b> :</h2>';
							
							}else {
								echo '<h2 class="title_small">Service Providers</h2>';
							} ?>

							<div class="owl-carousel owl-theme categories_carousel_in listing mt-1 d-none">
							
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="" data-bs-toggle="tooltip" data-bs-placement="top">All Categories</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="editor" data-bs-toggle="tooltip" data-bs-placement="top">Editors</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="proof-reader" data-bs-toggle="tooltip" data-bs-placement="top">Proof Readers</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="illustrator" data-bs-toggle="tooltip" data-bs-placement="top">Illustrators</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="printer" data-bs-toggle="tooltip" data-bs-placement="top">Printers</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="distributor" data-bs-toggle="tooltip" data-bs-placement="top">Distributors</a>
									<a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="writter" data-bs-toggle="tooltip" data-bs-placement="top">Writters</a>
                                    <a href="#" class="badge text-dark p-3 m-0 w-100 cat-switch" style="background: #f3f3f3;" data-src="reviewer" data-bs-toggle="tooltip" data-bs-placement="top">Reviewers</a>

							</div>
						</div>
						</div>
					</div>
					<!-- /row -->

                    <hr>

					<div class="promo d-non" style="background-image: url('sabooksonline.co.zaimg/pattern-banner.jpg');background-size: contain;">
						<h3>Become a service provider today!</h3>
						<p>Join Our Network of Providers, & Unlock Opportunities as a Service Provider.</p>
						<i class="icon-food_icon_delivery"></i>
					</div>
					<!-- /promo -->

                    <hr>
					
                    <div class="row" id="results-container">
                        <!-- Results will be displayed here -->
                    </div>


                    <div class="pagination_fg d-none" id="pagination">
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
            $sql = "SELECT * FROM users WHERE ADMIN_TYPE = 'service-provider' AND USER_STATUS = 'Verified';";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == false){
            }else{
                while($row = mysqli_fetch_assoc($result)) {

                    $content = str_replace(' ', '_', $row['ADMIN_NAME']).$row['ADMIN_ID'].'content';
                    $window = str_replace(' ', '_', $row['ADMIN_NAME']).$row['ADMIN_ID'].'window';
					echo '
                    
                    const '.$content.' =
                    "<div><h1>'.$row['ADMIN_NAME'].'</h1></div>";
                    
                    const '.str_replace(' ', '_', $row['ADMIN_NAME']).$row['ADMIN_ID'].' = { lat: '.$row['ADMIN_LATITUDE'].', lng: '.$row['ADMIN_LONGITUDE'].'};
                    
                    const '.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).$row['ADMIN_ID'].' = new google.maps.Marker({
                        position: '.str_replace(' ', '_', $row['ADMIN_NAME']).$row['ADMIN_ID'].',
                        map,
                        title: "'.$row['ADMIN_NAME'].'",
                        icon: {
                            url: "cms-data/profile-images/'.$row['ADMIN_PROFILE_IMAGE'].'", // URL of the image
                            scaledSize: new google.maps.Size(50, 50) // Size of the image
                          }
                      });
                      
                      '.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).$row['ADMIN_ID'].'.addListener("click", () => {
                        $(".info-tab").hide();
                         $("#'.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).$row['ADMIN_ID'].'_tab").toggle(300);
                         
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
                url: "fetch_services.php", // Replace with the URL of your PHP script
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
    $query = "SELECT u.ADMIN_NAME, u.ADMIN_GOOGLE, u.ADMIN_PROFILE_IMAGE, s.SERVICE, u.ADMIN_PROVINCE
            FROM users u
            JOIN services s ON u.ADMIN_USERKEY = s.USERID
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
$(document).ready(function() {
    // Sample data, you should replace this with your actual data

    var itemsPerPage = 3;
    var currentPage = 1;

    // Function to display user details and services for the current page
    function displayUsers(page) {
        var startIndex = (page - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;

        var userDetailsHtml = "";
        for (var i = startIndex; i < endIndex && i < usersData.length; i++) {
            userDetailsHtml += '<div class="card info-div col-4">';
            userDetailsHtml += '<!-- Add your existing HTML structure for user details here -->';
            userDetailsHtml += '</div>';
        }

        $("#user-details-container").html(userDetailsHtml);
    }

    // Function to update the pagination links based on the current page
    function updatePagination() {
        var totalItems = usersData.length;
        var totalPages = Math.ceil(totalItems / itemsPerPage);

        var paginationHtml = "";

        if (totalPages > 1) {
            paginationHtml += '<a href="#" id="prevPage">&laquo;</a>';
            for (var i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    paginationHtml += '<a href="#" class="active">' + i + '</a>';
                } else {
                    paginationHtml += '<a href="#" class="pageLink">' + i + '</a>';
                }
            }
            paginationHtml += '<a href="#" id="nextPage">&raquo;</a>';
        }

        $("#pagination").html(paginationHtml);

        // Click event for pagination links
        $(".pageLink").click(function() {
            currentPage = parseInt($(this).text());
            displayUsers(currentPage);
            updatePagination();
        });

        // Click event for "Previous" and "Next" links
        $("#prevPage").click(function() {
            if (currentPage > 1) {
                currentPage--;
                displayUsers(currentPage);
                updatePagination();
            }
        });

        $("#nextPage").click(function() {
            if (currentPage < totalPages) {
                currentPage++;
                displayUsers(currentPage);
                updatePagination();
            }
        });
    }

    // Initial display of user details and pagination
    displayUsers(currentPage);
    updatePagination();

    onsole.log("currentPage: " + currentPage);
});
</script>


	<script src="js/custom.js"></script>

</body>
</html>