<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Wilio Survey, Quotation, Review and Register form Wizard by Ansonika.">
    <meta name="author" content="Ansonika">
    <title>Registration | SA Books Online</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="https://sabooksonline.co.za/img/favicon.png" type="image/x-icon">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/menu.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
	<link href="css/vendors.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">
	
	<!-- MODERNIZR MENU -->
	<script src="js/modernizr.js"></script>

	<style>
		#services, #book-store {
			display: none;
		}
	</style>

</head>

<body>
	
	<div id="preloader">
		<div data-loader="circle-side"></div>
	</div><!-- /Preload -->
	
	<div id="loader_form">
		<div data-loader="circle-side-2"></div>
	</div><!-- /loader_form -->
	
	<!-- <nav>
		<ul class="cd-primary-nav">
			<li><a href="index.html" class="animated_link">Home</a></li>
			<li><a href="quotation-wizard-version.html" class="animated_link">Quote Version</a></li>
			<li><a href="review-wizard-version.html" class="animated_link">Review Version</a></li>
			<li><a href="registration-wizard-version.html" class="animated_link">Registration Version</a></li>
			<li><a href="about.html" class="animated_link">About Us</a></li>
			<li><a href="contacts.html" class="animated_link">Contact Us</a></li>
			<li><a href="rtl/index.html" class="animated_link">RTL Version</a></li>
		</ul>
	</nav>
	/menu -->
	
	<div class="container-fluid full-height">
		<div class="row row-height" style="overflow: hidden !important;">
			<div class="col-lg-6 content-left" style="background: rgba(0,0,0,.7) !important;overflow: hidden !important;height:100vh !important;position:inherit;">

								<div style="position: fixed;top:-20%;left:-15%;z-index:-200;overflow: hidden !important;transform: rotate(30deg);" class="col-lg-9">
									
									<?php
                                    //DATABASE CONNECTIONS SCRIPT
                                    include '../includes/database_connections/sabooks.php';
                                    $sql = "SELECT * FROM posts WHERE STATUS = 'active' ORDER BY RAND() LIMIT 60;";
                                    //$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
                                    $result = mysqli_query($conn, $sql);
                                    $result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($result) == false){
                                        }else{
                                        while($row = mysqli_fetch_assoc($result)) {

                                            echo ' <img src="../cms-data/book-covers/'.$row['COVER'].'" width="100px" style="overflow: hidden !important;">';
                                        }
                                    }
                                ?>

								</div>
				<div class="content-left-wrapper">
					<a href="#" id="logo"><img src="../img/logo-high.png" alt="" width="100"></a>
					<div id="social">
						<ul>
							<li><a href="#0"><i class="icon-facebook"></i></a></li>
							<li><a href="#0"><i class="icon-twitter"></i></a></li>
							<li><a href="#0"><i class="icon-google"></i></a></li>
							<li><a href="#0"><i class="icon-linkedin"></i></a></li>
						</ul>
					</div>
					<!-- /social -->
					<div>
						<!--<figure><img src="img/info_graphic_2.svg" alt="" class="img-fluid"></figure>-->
						<h2>Account Registration</h2>
						<p>SA Books Online is South Africa's first book repository that is positioning itself as the primary platform for everything that is and connects to South African literature.</p>
						<a href="#" class="btn_1 rounded" target="_parent">Login Instead</a>
						<a href="#start" class="btn_1 rounded mobile_btn">Start Now!</a>
					</div>
					<div class="copy">© 2023 SA Books Online</div>
				</div>
				<!-- /content-left-wrapper -->
			</div>
			<!-- /content-left -->

			<div class="col-lg-6 content-right" id="start" style="z-index:1000 !important;background-color:#fff;">
				<div id="wizard_container">
					<div id="top-wizard">
							<div id="progressbar"></div>
						</div>
						<!-- /top-wizard -->
						<form id="membership">
							<input id="website" name="website" type="text" value="">
							
							<!-- Leave for security protection, read docs for details -->
							<div>
								<!-- /step-->
							<div class="step" id="middle-wizard">
								<h3 class="main_question"><strong>1/5</strong>How would you describe your self?</h3>
								<div class="form-group">
									<label class="container_radio version_2">Author
										<input type="radio" name="reg_type" value="author" class="required">
										<span class="checkmark"></span>
									</label>
								</div>
								<div class="form-group">
									<label class="container_radio version_2">Publisher
										<input type="radio" name="reg_type" value="publisher" class="required">
										<span class="checkmark"></span>
									</label>
								</div>
								<div class="form-group">
									<label class="container_radio version_2">Service Provider
										<input type="radio" name="reg_type" value="service-provider" class="required">
										<span class="checkmark"></span>
									</label>
								</div>
								<div class="form-group">
									<label class="container_radio version_2">Book Store
										<input type="radio" name="reg_type" value="book-store">
										<span class="checkmark"></span>
									</label>
								</div>

							</div>
							<!-- /step-->

							<!-- /step-->
							<div class="step">
								<h3 class="main_question"><strong>2/5</strong>Tell us a little about yourself or organisation</h3>
								<div class="form-group add_top_30">
									<textarea name="reg_bio" class="form-control review_message required" placeholder="Write your review here..." onkeyup="getVals(this, 'review_message');" id="myInput"></textarea>
								</div>

								<div class="form-group add_top_30">
									<label>Profile Image or Logo (Optional)<br><small>(Files accepted: gif,jpg,jpeg,.png - Max file size: 150k for demo purpose, you can increase based on your host settings.)</small></label>
									<div class="fileupload">
										<input type="file" name="reg_profile" accept="image/*">
									</div>
								</div>

								<div class="form-group hide">
										<input type="text" name="reg_address" class="form-control required" placeholder="Physical Address" id="autocomplete">
										<input type="hidden" name="reg_province" class="form-control required" placeholder="Physical Address" id="province">
									</div>
								
								
								
							</div>

							<div class="step">
								<div class="service-provider">
									<h3 class="main_question"><strong>3/5</strong>How would you like to use SA Books Online (You may select multiple)</h3>

									<div class="creator">
										<div class="form-group -hide">
											<label class="container_check version_2">Profile Listing
												<input type="checkbox" name="services[]" value="Showcase" class="required" checked>
												<span class="checkmark"></span>
											</label>
										</div>
									</div>
									
									<div class="service-provider">
										<div class="form-group service-hide">
											<label class="container_check version_2">Editing
												<input type="checkbox" name="services[]" value="Editor" class="required">
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="form-group service-hide">
											<label class="container_check version_2">Proof Reading
												<input type="checkbox" name="services[]" value="Proof Reader" class="required" >
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="form-group service-hide">
											<label class="container_check version_2">Printing
												<input type="checkbox" name="services[]" value="Printer" class="required" >
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="form-group service-hide">
											<label class="container_check version_2">Distribution
												<input type="checkbox" name="services[]" value="Distributor" class="required" >
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="form-group service-hide">
											<label class="container_check version_2">Illustrations
												<input type="checkbox" name="services[]" value="Illustrator" class="required" >
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="form-group service-hide">
											<label class="container_check version_2">Writting
												<input type="checkbox" name="services[]" value="Writter" class="required">
												<span class="checkmark"></span>
											</label>
										</div>

										<div class="form-group service-hide">
											<input type="text" name="reg_service_other" class="form-control input" placeholder="Any Other Service">
										</div>
									</div>

									

								</div>
							</div>

								<div class="step">
									<h3 class="main_question"><strong>4/5</strong>Please fill with your details</h3>
									<div class="form-group">
										<input type="text" name="reg_name" class="form-control required input" placeholder="Organisation's Name">
									</div>
									<div class="form-group">
										<input type="email" name="reg_email" class="form-control required input" placeholder="Your Email">
									</div>
									<div class="form-group">
										<input type="text" name="reg_number" class="form-control required input" placeholder="Cellphone Number">
									</div>
								</div>

								

								<div class="submit step">
									<h3 class="main_question"><strong>5/5</strong>Let's set up your password!</h3>

									<div class="form-group">
										<input class="form-control required" type="password" id="password1" name="reg_password" placeholder="Password" onchange="getVals(this, 'password');">
									</div>
									<div class="form-group">
										<input class="form-control required" type="password" id="password2" name="reg_confirm_password" placeholder="Confirm Password">
									</div>

									<div id="pass-info" class="clearfix"></div>
									
									
									<div class="form-group terms">
										<label class="container_check">Please accept our <a href="#" data-bs-toggle="modal" data-bs-target="#terms-txt">Terms and conditions</a>
											<input type="checkbox" name="terms" value="Yes" class="required">
											<span class="checkmark"></span>
										</label>
									</div>
								</div>
								
							</div>
							<!-- /middle-wizard -->
							<div id="bottom-wizard">
								<button type="button" name="backward" class="backward">Prev</button>
								<button type="button" name="forward" class="forward">Next</button>
								<button type="submit" name="process" class="submit" id="reg_load">Complete Registration</button>
							</div>
							<!-- /bottom-wizard -->

							<div id="reg_status"></div>
						</form>
					</div>
					<!-- /Wizard container -->
			</div>
			<!-- /content-right-->
		</div>
		<!-- /row-->
	</div>
	<!-- /container-fluid -->

	<div class="cd-overlay-nav">
		<span></span>
	</div>
	<!-- /cd-overlay-nav -->

	<div class="cd-overlay-content">
		<span></span>
	</div>
	<!-- /cd-overlay-content -->

	<a href="#0" class="cd-nav-trigger">Menu<span class="cd-icon"></span></a>
	<!-- /menu button -->
	
	<!-- Modal terms -->
	<div class="modal fade" id="terms-txt" tabindex="-1" role="dialog" aria-labelledby="termsLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="termsLabel">Terms and conditions</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in <strong>nec quod novum accumsan</strong>, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus. Lorem ipsum dolor sit amet, <strong>in porro albucius qui</strong>, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn_1" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	
	<!-- COMMON SCRIPTS -->
	<script src="js/jquery-3.6.4.min.js"></script>
    <script src="js/common_scripts.min.js"></script>
	<script src="js/velocity.min.js"></script>
	<script src="js/functions.js"></script>
	<script src="js/pw_strenght.js"></script>

	<!-- Wizard script -->
	<script src="js/registration_func.js"></script>


	<script>

		//$("#other").show();


$(document).ready(function() {

	setInterval(function(){

		let inputType = $("input[name=reg_type]:checked").val();

		if(inputType === 'book-store'){

			$('.bookstore-hide').show();
			$('.service-hide').hide();
			$('.creator-hide').hide();
			//$('.service-hide').append();

		} 

	}, 1000);


	setInterval(function(){

		let inputType = $("input[name=reg_type]:checked").val();

		if(inputType === 'service-provider'){

			$('.service-hide').show();
			$('.bookstore-hide').hide();
			$('.creator-hide').hide();
			//$('.service-hide').append();

		}

	}, 1000);

	setInterval(function(){

	let inputType = $("input[name=reg_type]:checked").val();

	if(inputType === 'author'){

		$('.service-hide').hide();
		$('.bookstore-hide').hide();
		$('.creator-hide').show();
		//$('.service-hide').append();

	}

	}, 1000);

	setInterval(function(){

	let inputType = $("input[name=reg_type]:checked").val();

	if(inputType === 'publisher'){

		$('.service-hide').hide();
		$('.bookstore-hide').hide();
		$('.creator-hide').show();
		//$('.service-hide').append();

	}

	}, 1000);

	setInterval(function(){

	let inputType = $("input[name=reg_type]:checked").val();

	if(inputType === 'publisher'){

		$('.service-hide').hide();
		$('.bookstore-hide').hide();
		$('.creator-hide').show();
		//$('.service-hide').append();

	}

	}, 1000);


		// Get the text input
		const input = document.getElementById('myInput');

		// Add keypress event listener
		input.addEventListener('keypress', function(event){
			
			// Get the key 
			let key = event.key;
			
			let regex = new RegExp('^[a-zA-Z0-9" -%,.()!@]+$');
			
			// Check if key is in the reg exp
			if(!regex.test(key)){
				
				// Restrict the special characters
				event.preventDefault();  
				return false;
			}
		});

		// Get the text input
		const personal = document.getElementByClass('myInput');

		// Add keypress event listener
		input.addEventListener('keypress', function(event){
			
			// Get the key 
			let key = event.key;
			
			let regex = new RegExp('^[a-zA-Z0-9" -%]+$');
			
			// Check if key is in the reg exp
			if(!regex.test(key)){
				
				// Restrict the special characters
				event.preventDefault();  
				return false;
			}
		});
	
		
		//$('input').on('input', function() {
		//let input = $(this).val($(this).val().replace(/[^a-z0-9]/gi, ''));

		//alert(input);
		//});

});
		//publiish story upload code

</script>

<script>

		//$("#other").show();


$(document).ready(function() {

	$("#membership").on('submit',(function(e) {

		e.preventDefault();

		$("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
		
			//showSwal('success-message');
		$.ajax({
				url: "../includes/backend/member-registration.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
				cache: false,
			processData:false,
			success: function(data)
		{
			$("#reg_load").html('Complete Registration');
			$("#reg_status").html(data);
			},
		error: function(){}
		});


	}));
});
		//publiish story upload code

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