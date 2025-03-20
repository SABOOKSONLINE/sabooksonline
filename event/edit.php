<?php

            //DATABASE CONNECTIONS SCRIPT

			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);

            session_start();

			if(!isset($_SESSION['ADMIN_USERKEY'])){

				header("Location: ../");

			} else {

			$id = $_GET['id'];
			$userkey = $_SESSION['ADMIN_USERKEY'];

			include '../includes/database_connections/sabooks.php';
            $sql = "SELECT * FROM events WHERE USERKEY = '$userkey' AND ID = '$id'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == false){

				$event_title = 'Not found';

				
            }else{
                while($row = mysqli_fetch_assoc($result)) {

					$event_title = $row['TITLE'];
					$event_id = $row['ID'];
					$event_email = $row['EMAIL'];
					$event_number = $row['NUMBER'];
					$event_type = $row['EVENTTYPE'];
					$event_desc = $row['DESCRIPTION'];
					$event_address = $row['VENUE'];
					$event_province = $row['PROVINCE'];
					$event_time = $row['EVENTTIME'];
					$event_date = $row['EVENTDATE'];
					$event_date_end = $row['EVENTEND'];
					$event_days = $row['DURATION'];
					$event_end = $row['LINK'];
					$event_cover = $row['COVER'];


                    }
                }

			}
            
        ?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="SA Books Online">
    <title>Edit Event</title>

    <!-- Favicons-->
	<link rel="shortcut icon" href="../img/favicon.png" type="image/x-icon">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/menu.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
	<link href="css/vendors.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">
	
	<!-- MODERNIZR -->
	<script src="js/modernizr.js"></script>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

</head>

<body>
	
	<div id="preloader">
		<div data-loader="circle-side"></div>
	</div><!-- /Preload -->
	
	<div id="loader_form">
		<div data-loader="circle-side-2"></div>
	</div><!-- /loader_form -->
	
	<nav>
		<ul class="cd-primary-nav">
			<li><a href="../index" class="animated_link">Home</a></li>
			<li><a href="../dashboard-2" class="animated_link">Dashboard</a></li>
			<li><a href="../logout" class="animated_link">Logout</a></li>
		</ul>
	</nav>
	<!-- /menu -->
	
	<div class="container-fluid full-height">
		<div class="row row-height">
			<div class="col-lg-6 content-left">
				<div class="content-left-wrapper bg_hotel">
					<div class="wrapper">
						<a href="../index" id="logo"><img src="https://my.sabooksonline.co.za/img/logo-high.png" alt="" width="100"></a>
						<div id="social">
							<ul>
								<li><a href="#0"><i class="social_facebook"></i></a></li>
								<li><a href="#0"><i class="social_twitter"></i></a></li>
								<li><a href="#0"><i class="social_instagram"></i></a></li>
							</ul>
						</div>
						<!-- /social -->
						<div class="left_title">
							<h3><?php echo $event_title; ?></h3>
							<p>Advertising Starting from R10 per day</p>
						</div>
					</div>
				</div>
				<!--/content-left-wrapper -->
			</div>
			<!-- /content-left -->

			<div class="col-lg-6 content-right" id="start">
				<div id="wizard_container">
					<div id="top-wizard">
							<div id="progressbar"></div>
						</div>
						<!-- /top-wizard -->
						<form id="membership">
							<input id="website" name="website" type="text" value="">
							<!-- Leave for security protection, read docs for details -->
							
							<div id="middle-wizard">
								<div class="step">
									<h3 class="main_question"><strong>1/3</strong>Editing <b><?php echo $event_title; ?></b> </h3>

                                    <div class="form-group">
										<input type="text" name="event_title" class="form-control required" placeholder="Event Title" value="<?php echo $event_title; ?>">
										<input type="hidden" name="event_id" class="form-control required" placeholder="Event Title" value="<?php echo $event_id; ?>">
										<i class="icon-user"></i>
									</div>
									
									<div class="form-group">
										<input type="email" name="event_email" class="form-control required" placeholder="Event Email" value="<?php echo $event_email; ?>">
										<i class="icon-envelope"></i>
									</div>
									<div class="form-group">
										<input type="text" name="event_number" class="form-control" placeholder="Event Telephone" value="<?php echo $event_number; ?>">
										<i class="icon-phone"></i>
									</div>


									
									<div class="form-group">
										<textarea class="form-control notes" placeholder="Event Description" name="event_desc"> <?php echo $event_desc; ?></textarea>
									</div>

                                    <div class="form-group add_top_30">
                                        <label>Event Cover Image (Required)<br><small>(Files accepted: gif,jpg,jpeg,.png - Max file size: 150k</small></label>
                                        <div class="fileupload">
                                            <input type="file" name="event_cover" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-group hide">
                                        <small class="text-danger"><span class="icon_info_alt"></span> Please select the suggested address</small>
                                            <input type="text" name="event_address" class="form-control" placeholder="Event Address" id="autocomplete" value="<?php echo $event_address; ?>">
                                            <input type="hidden" name="event_province" class="form-control" placeholder="Physical Address" id="province" value="<?php echo $event_province; ?>">
                                    </div>
								</div>
								<!-- /step-->

                                <div class="step">
									<h3 class="main_question"><strong>2/3</strong>Event Details</h3>
									

                                    <div class="form-group">
                                    <label><small>Event Start Date:</small></label>
										<input type="text" name="dates_start" class="form-control required" placeholder="Start Date" value="<?php echo $event_date; ?>">
										<i class="icon-hotel-calendar_3"></i>
									</div>
									<div class="form-group">

                                    <label><small>Event End Date:</small></label>
										<input type="text" name="dates_end" class="form-control required" placeholder="End Date" value="<?php echo $event_date_end; ?>">
										<i class="icon-hotel-calendar_3"></i>
									</div>

                                    <div class="form-group">
										<div class="styled-select clearfix">
                                        <label>Event Start Time:</small></label>
                                        <input type="text" name="event_start_time" class="form-control timepicker" placeholder="Event Start Time" value="<?php echo $event_time; ?>">
										</div>
									</div>
                                    <div class="form-group">
                                    <label>Event End Time:</small></label>
										<div class="styled-select clearfix">
                                        <input type="text" name="event_end_time" class="form-control timepicker" placeholder="Event End Time" value="<?php echo $event_end; ?>">
										</div>
									</div>

                                    <div class="form-group">
										<div class="styled-select clearfix">
											 <select class="required ddslick" name="event_type">
												<option value="">Event Type</option>
                                        		<option value="In-Person Event">In-Person Event</option>
                                                <option value="Virtual/Online Event">Virtual/Online Event</option>
                                        		<option value="Hybrid Event">Hybrid Event</option>
											</select>
										</div>
									</div>

								</div>
								<!-- /step-->
								
								
								<div class="submit step">
									<h3 class="main_question"><strong>3/3</strong>Event Billing</h3>

									<div class="row no-gutters pb-1">
										<div class="col-12 pr-2">
											<div class="form-group">
                                            <label>Number of days to show event<br><small>(Min: 7 Days & Max: 30 Days)</small></label>
												<div class="qty-buttons">
                                                <input type="button" value="+" class="qtyplus" name="child">
													<input type="text" name="child" id="adults" min="7" max="30" class="qty form-control required" placeholder="Adults" value="<?php echo $event_days; ?>">
													<input type="button" value="-" class="qtyminus" name="child">
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-group terms">
										<label class="container_check">Please accept our <a href="#" data-toggle="modal" data-target="#terms-txt">Terms and conditions</a>
											<input type="checkbox" name="terms" value="Yes" class="required">
											<span class="checkmark"></span>
										</label>
									</div>
								</div>
								<!-- /step-->
								
							</div>
							<!-- /middle-wizard -->
							<div id="bottom-wizard">
								<button type="button" name="backward" class="backward">Prev</button>
								<button type="button" name="forward" class="forward">Next</button>
								<button type="submit" name="process" class="submit" id="reg_load">Update Event</button>
							</div>
							<!-- /bottom-wizard -->

							<div id="reg_status"></div>
						</form>
					</div>
					<!-- /Wizard container -->
				
					<div class="footer">
						<ul>
							<li><a href="../index" class="animated_link">Home</a></li>
							<li><a href="../dashboard-events" class="animated_link" target="_parent">Back to Dashboard</a></li>
						</ul>
					</div>
					<!-- Footer -->
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
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in <strong>nec quod novum accumsan</strong>, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus. Lorem ipsum dolor sit amet, <strong>in porro albucius qui</strong>, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
					<p>Lorem ipsum dolor sit amet, in porro albucius qui, in nec quod novum accumsan, mei ludus tamquam dolores id. No sit debitis meliore postulant, per ex prompta alterum sanctus, pro ne quod dicunt sensibus.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn_1" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	
	<!-- COMMON SCRIPTS -->
	<script src="js/jquery-3.7.0.min.js"></script>
    <script src="js/common_scripts.min.js"></script>
	<script src="js/velocity.min.js"></script>
	<script src="js/functions.js"></script>

	<!-- Wizard script -->
	<script src="js/booking_spa_func.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
	

<script>

//$("#other").show();


$(document).ready(function() {

	$("#membership").on('submit',(function(e) {

		e.preventDefault();

		$("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');

			//showSwal('success-message');
		$.ajax({
				url: "../includes/backend/member-update-event.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
				cache: false,
			processData:false,
			success: function(data)
		{
			$("#reg_load").html('Update Event');
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