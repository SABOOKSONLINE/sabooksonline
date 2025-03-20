<?php

            //DATABASE CONNECTIONS SCRIPT

			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);

            session_start();

			if(!isset($_SESSION['ADMIN_USERKEY'])){

				header("Location: ../login");

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
    <title>Event Creation</title>

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
						<a href="index.html" id="logo"><img src="https://my.sabooksonline.co.za/img/logo-high.png" alt="" width="100"></a>
						<div id="social">
							<ul>
								<li><a href="#0"><i class="social_facebook"></i></a></li>
								<li><a href="#0"><i class="social_twitter"></i></a></li>
								<li><a href="#0"><i class="social_instagram"></i></a></li>
							</ul>
						</div>
						<!-- /social -->
						<div class="left_title">
							<h3>Discover Uplifting Events Near You</h3>
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
									<h3 class="main_question"><strong>1/4</strong>Events details</h3>

                                    <div class="form-group">
										<input type="text" name="event_title" class="form-control required" placeholder="Event Title">
										<i class="icon-user"></i>
									</div>
									
									<div class="form-group">
										<input type="email" name="event_email" class="form-control required" placeholder="Event Email">
										<i class="icon-envelope"></i>
									</div>
									<div class="form-group">
										<input type="text" name="event_number" class="form-control" placeholder="Event Telephone">
										<i class="icon-phone"></i>
									</div>


									
									<div class="form-group">
										<textarea class="form-control notes" placeholder="Event Description" name="event_desc"></textarea>
									</div>

                                    <div class="form-group add_top_30">
                                        <label>Event Cover Image (Required)<br><small>(Files accepted: gif,jpg,jpeg,.png - Max file size: 150k)</small></label>
                                        <div class="fileupload">
                                            <input type="file" name="event_cover" accept="image/*" required>
                                        </div>
                                    </div>

                                    <div class="form-group hide">
                                        <small class="text-danger"><span class="icon_info_alt"></span> Please select the suggested address</small>
                                            <input type="text" name="event_address" class="form-control required" placeholder="Event Address" id="autocomplete">
                                            <input type="hidden" name="event_province" class="form-control required" placeholder="Physical Address" id="province">
                                    </div>
								</div>
								<!-- /step-->

                                <div class="step">
									<h3 class="main_question"><strong>2/4</strong>Event Details</h3>
									<div class="form-group">
                                    <label><small>Event Start Date:</small></label>
										<input type="text" name="dates_start" class="form-control required" placeholder="Start Date">
										<i class="icon-hotel-calendar_3"></i>
									</div>
									<div class="form-group">

                                    <label><small>Event End Date:</small></label>
										<input type="text" name="dates_end" class="form-control required" placeholder="End Date">
										<i class="icon-hotel-calendar_3"></i>
									</div>
									<div class="form-group">
										<div class="styled-select clearfix">
                                        <label><small>Event Start Time:</small></label>
                                        <input type="text" name="event_start_time" class="form-control timepicker" placeholder="Event Start Time">
										</div>
									</div>
                                    <div class="form-group">
										<div class="styled-select clearfix">
                                        <label><small>Event End Time:</small></label>
                                        <input type="text" name="event_end_time" class="form-control timepicker" placeholder="Event End Time">
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

                                <div class="step">
									<h3 class="main_question"><strong>3/4</strong>Type Of Event</h3>

									<div class="creator">
										<div class="form-group -hide">
											<label class="container_check version_2">Book Launch
												<input type="checkbox" name="services[]" value="Book Launch" class="">
												<span class="checkmark"></span>
											</label>
										</div>
									</div>
									
									<div class="service-provider">
										<div class="form-group service-hide">
											<label class="container_check version_2">Book Reading
												<input type="checkbox" name="services[]" value="Book Reading" class="">
												<span class="checkmark"></span>
											</label>
										</div>
										
										<div class="form-group service-hide">
											<label class="container_check version_2">Cover Launch
												<input type="checkbox" name="services[]" value="Cover Launch" class="" >
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="form-group service-hide">
											<label class="container_check version_2">Book Signing
												<input type="checkbox" name="services[]" value="Book Signing" class="" >
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="form-group service-hide">
											<label class="container_check version_2">Book Discussion
												<input type="checkbox" name="services[]" value="Book Discussion" class="" >
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="form-group service-hide">
											<label class="container_check version_2">Book Fair/Festival
												<input type="checkbox" name="services[]" value="Book Fair/Festival" class="">
												<span class="checkmark"></span>
											</label>
										</div>

                                        <div class="form-group service-hide">
											<label class="container_check version_2">Literary Conference
												<input type="checkbox" name="services[]" value="Literary Conference" class="">
												<span class="checkmark"></span>
											</label>
										</div>

                                        <div class="form-group service-hide">
											<label class="container_check version_2">Literary Workshops
												<input type="checkbox" name="services[]" value="Literary Workshops" class="">
												<span class="checkmark"></span>
											</label>
										</div>

                                        <div class="form-group service-hide">
											<label class="container_check version_2">Literary Masterclasses
												<input type="checkbox" name="services[]" value="Literary Masterclasses" class="">
												<span class="checkmark"></span>
											</label>
										</div>

                                        <div class="form-group service-hide">
											<label class="container_check version_2">Literary Announcements
												<input type="checkbox" name="services[]" value="Literary Announcements" class="">
												<span class="checkmark"></span>
											</label>
										</div>

										<div class="form-group service-hide">
											<input type="text" name="reg_service_other" class="form-control input" placeholder="Any Other Type Event">
										</div>
									</div>

								</div>
								<!-- /step-->
								
								
								<div class="submit step">
									<h3 class="main_question"><strong>4/4</strong>Event Billing</h3>

									<div class="row no-gutters pb-1">
										<div class="col-12 pr-2">
											<div class="form-group">
                                            <label>Number of days to show event<br><small>(Min: 7 Days & Max: 30 Days)</small></label>
												<div class="qty-buttons">
												<input type="button" value="+" class="qtyplus" name="child">
													<input type="text" name="child" id="adults" value="7" min="7" max="30" class="qty form-control required" placeholder="Adults">
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
								<button type="submit" name="process" class="submit" id="reg_load">Create Event</button>
							</div>
							<!-- /bottom-wizard -->

							<div id="reg_status"></div>
						</form>
					</div>
					<!-- /Wizard container -->
				
					<div class="footer">
						<ul>
							<li><a href="../index" class="animated_link">Home</a></li>
							<li><a href="../dashboard-2" class="animated_link" target="_parent">Back to Dashboard</a></li>
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
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
						
						<p>These terms and conditions outline the rules and regulations for the use of SA Books Online's Website, located at www.sabooksonline.co.za.</p>

						<p>By accessing this website we assume you accept these terms and conditions. Do not continue to use SA Books Online if you do not agree to take all of the terms and conditions stated on this page.</p>

						<p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: "Client", "You" and "Your" refers to you, the person log on this website and compliant to the Company’s terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client’s needs in respect of provision of the Company’s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p>

						<h3><strong>Cookies</strong></h3>

						<p>We employ the use of cookies. By accessing SA Books Online, you agreed to use cookies in agreement with the SA Books Online's Privacy Policy. </p>

						<p>Most interactive websites use cookies to let us retrieve the user’s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</p>

						<h3><strong>License</strong></h3>

						<p>Unless otherwise stated, SA Books Online and/or its licensors own the intellectual property rights for all material on SA Books Online. All intellectual property rights are reserved. You may access this from SA Books Online for your own personal use subjected to restrictions set in these terms and conditions.</p>

						<p>You must not:</p>
						<ul class="container">
							<li>Republish material from SA Books Online</li>
							<li>Sell, rent or sub-license material from SA Books Online</li>
							<li>Reproduce, duplicate or copy material from SA Books Online</li>
							<li>Redistribute content from SA Books Online</li>
						</ul>


						<p>Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. SA Books Online does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of SA Books Online,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, SA Books Online shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.</p>

						<p>SA Books Online reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.</p>

						<p>You warrant and represent that:</p>

						<ul>
							<li>You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;</li>
							<li>The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;</li>
							<li>The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy</li>
							<li>The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.</li>
						</ul>

						<p>You hereby grant SA Books Online a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.</p>

						<h3><strong>Hyperlinking to our Content</strong></h3>

						<p>The following organizations may link to our Website without prior written approval:</p>

						<ul>
							<li>Government agencies;</li>
							<li>Search engines;</li>
							<li>News organizations;</li>
							<li>Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and</li>
							<li>System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.</li>
						</ul>

						<p>These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party’s site.</p>

						<p>We may consider and approve other link requests from the following types of organizations:</p>

						<ul>
							<li>commonly-known consumer and/or business information sources;</li>
							<li>dot.com community sites;</li>
							<li>associations or other groups representing charities;</li>
							<li>online directory distributors;</li>
							<li>internet portals;</li>
							<li>accounting, law and consulting firms; and</li>
							<li>educational institutions and trade associations.</li>
						</ul>

						<p>We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of SA Books Online; and (d) the link is in the context of general resource information.</p>

						<p>These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party’s site.</p>

						<p>If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to SA Books Online. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.</p>

						<p>Approved organizations may hyperlink to our Website as follows:</p>

						<ul>
							<li>By use of our corporate name; or</li>
							<li>By use of the uniform resource locator being linked to; or</li>
							<li>By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party’s site.</li>
						</ul>

						<p>No use of SA Books Online's logo or other artwork will be allowed for linking absent a trademark license agreement.</p>

						<h3><strong>iFrames</strong></h3>

						<p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p>

						<h3><strong>Content Liability</strong></h3>

						<p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</p>

						<h3><strong>Your Privacy</strong></h3>

						<p>Please read Privacy Policy</p>

						<h3><strong>Reservation of Rights</strong></h3>

						<p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it’s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</p>

						<h3><strong>Removal of links from our website</strong></h3>

						<p>If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</p>

						<p>We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</p>

						<h3><strong>Disclaimer</strong></h3>

						<p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</p>

						<ul class="list-none">
							<li>limit or exclude our or your liability for death or personal injury;</li>
							<li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
							<li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
							<li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
						</ul>

						<p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</p>

						<p>As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.</p>
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
	<script src="js/jquery-3.7.0.min.js"></script>
    <script src="js/common_scripts.min.js"></script>
	<script src="js/velocity.min.js"></script>
	<script src="js/functions.js"></script>

	<!-- Wizard script -->
	<script src="js/booking_spa_func.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
	

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

	$("#membership").on('submit',(function(e) {

		e.preventDefault();

		$("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');

			//showSwal('success-message');
		$.ajax({
				url: "../includes/backend/member-add-event.php",
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