<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Wilio Survey, Quotation, Review and Register form Wizard by Ansonika.">
    <meta name="author" content="Ansonika">
    <title>Login | SA Books Online</title>

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
			<div class="col-lg-6 content-left" style="background: rgba(0,0,0,.7) !important;overflow: hidden !important;;">

								<div style="position: absolute;top:-20%;left:-15%;z-index:-200;overflow: hidden !important;transform: rotate(30deg);" class="col-lg-9">
									
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
					<a href="#" id="logo"><img src="https://sabooksonline.co.za/img/favicon.png" alt="" width="49"></a>
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
						<h2>Account Login</h2>
						<p>Tation argumentum et usu, dicit viderer evertitur te has. Eu dictas concludaturque usu, facete detracto patrioque an per, lucilius pertinacia eu vel. Adhuc invidunt duo ex. Eu tantas dolorum ullamcorper qui.</p>
						<a href="#" class="btn_1 rounded" target="_parent">Register Instead</a>
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
						<form id="wrappe">
							<input id="website" name="website" type="text" value="">
							
							<!-- Leave for security protection, read docs for details -->
							<div>
								<!-- /step-->

								<div class="submit step">
									<h3 class="main_question">Login To Account</h3>

									<hr>

									<div class="form-group">
										<input class="form-control required" type="text" name="reg_email" placeholder="Email address" onchange="getVals(this, 'password');">
									</div>
									<div class="form-group">
										<input class="form-control required" type="password" id="password2" name="reg_password" placeholder="Your Password">
									</div>

									<div id="pass-info" class="clearfix"></div>
									
								</div>
								
							</div>
							<!-- /middle-wizard -->
							<div id="bottom-wizard">
								<button type="submit" name="process" class="submit" id="reg_load">Login</button>
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
		$("#reg_load").html('Submit Application');
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
// Get the place details from the autocomplete object.
var place = autocomplete.getPlace();

for (var component in componentForm) {
document.getElementById(component).value = '';
document.getElementById(component).disabled = false;
}

// Get each component of the address from the place details
// and fill the corresponding field on the form.
for (var i = 0; i < place.address_components.length; i++) {
var addressType = place.address_components[i].types[0];
if (componentForm[addressType]) {
var val = place.address_components[i][componentForm[addressType]];
document.getElementById(addressType).value = val;
}
}
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