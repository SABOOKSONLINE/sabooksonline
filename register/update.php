<?php session_start();?>	
	
	<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="SA Books Online">
    <title>Registration | SA Books Online</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="https://sabooksonline.co.za/img/favicon.png" type="image/x-icon">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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
 #modal-content{
    -webkit-box-shadow: 0 5px 15px rgba(0,0,0,0) !important;
    -moz-box-shadow: 0 5px 15px rgba(0,0,0,0) !important;
    -o-box-shadow: 0 5px 15px rgba(0,0,0,0) !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0) !important;

    
}

.modal-backdrop.fade.show{
   display:none !important;
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

                                            echo ' <img src="../cms-data/book-covers/'.$row['COVER'].'" width="110px" style="overflow: hidden !important;border-radius:4%;">';
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
						<p>SA Books Online is South Africa's first book repository that is positioning itself as the primary platform for everything that is and connects to South African literature. </p>
						<a href="login" class="btn_1 rounded" target="_parent">Login Instead</a>
						<a href="#membership" class="btn_1 rounded mobile_btn">Start Registration!</a>
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
								<h3 class="main_question"><strong>1/5</strong>Which account would you like to switch to?</h3>
								
								<div class="form-group">
									<label class="container_radio version_2">Service Provider
										<input type="radio" name="reg_type" value="service-provider" class="required">
										<span class="checkmark"></span>
									</label>
								</div>
								


							</div>
							<!-- /step-->

							<!-- /step-->
							<div class="step">
                  
								<h3 class="main_question"><strong>2/5</strong>Tell us a little about yourself or organisation</h3>
								<div class="form-group add_top_30">
									<textarea name="reg_bio" class="form-control review_message required" placeholder="Write your review here..." onkeyup="getVals(this, 'review_message');" id="myInput"><?php echo $_SESSION['ADMIN_BIO'];?></textarea>
								</div>

							

								<div class="form-group hide">
                                    <small class="text-danger"><span class="icon_info_alt"></span> Please select the suggested address</small>
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
											<label class="container_check version_2">Writing
												<input type="checkbox" name="services[]" value="Writer" class="required">
												<span class="checkmark"></span>
											</label>
										</div>

                                        <div class="form-group service-hide">
											<label class="container_check version_2">Reviewers
												<input type="checkbox" name="services[]" value="Reviewer" class="required">
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
										<input type="text" name="reg_name" class="form-control required input" placeholder="Name or Organisation" value="<?php echo $_SESSION['ADMIN_NAME'];?>">
									</div>
									<div class="form-group">
										<input type="email" name="reg_email" class="form-control required input" placeholder="Your Email" value="<?php echo $_SESSION['ADMIN_EMAIL'];?>">
									</div>
									<div class="form-group">
										<input type="text" name="reg_number" class="form-control required input" placeholder="Cellphone Number" value="<?php echo $_SESSION['ADMIN_NUMBER'];?>">
									</div>
								</div>

								

								<div class="submit step">
							

									
									<div id="pass-info" class="clearfix"></div>
									
									
									<div class="form-group terms bg_gray" style="background: #f3f3f3 !important;border-radius:5%;padding:1%">
										<label class="container_check">Please accept our <a href="#"  data-bs-toggle="modal" data-bs-target="#terms-txt">Terms and conditions</a>
											<input type="checkbox" name="terms" value="Yes" class="required" checked>
											<span class="checkmark"></span>
										</label>
									</div>
								</div>

                                
								
							</div>
							<!-- /middle-wizard -->
							<div id="bottom-wizard">
								<button type="button" name="backward" class="backward">Prev</button>
								<button type="button" name="forward" class="forward">Next</button>
								<button type="submit" name="process" class="submit" id="reg_load">Complete Upgrade</button>
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


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:1000 !important;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">POPIA Consent Prompt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        The information provided by you is protected and will not be used inappropriately or without consent.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="close">Continue</button>
      </div>
    </div>
  </div>
</div>

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


	

	<a href="#0" class="cd-nav-trigger">Menu<span class="cd-icon"></span></a>
	<!-- /menu button -->
	
	
	<!-- COMMON SCRIPTS -->
	<script src="js/jquery-3.6.4.min.js"></script>
    <script src="js/common_scripts.min.js"></script>
	<script src="js/velocity.min.js"></script>
	<script src="js/functions.js"></script>
	<script src="js/pw_strenght.js"></script>

	<!-- Wizard script -->
	<script src="js/registration_func.js"></script>

<script type="text/javascript">
    $(window).on('load', function() {
        $('#exampleModal').modal('show');
       
    });

    $('#close').click(function(){
        $('#exampleModal').modal('hide');

    });

</script>

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
		const input = document.getElementById('myInpu');

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
		const personal = document.getElementByClass('myInpu');

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
				url: "../includes/backend/update-services.php",
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
	
<script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
<div class="elfsight-app-302bdabb-e2a7-4b4c-8daa-541e801b20c5"></div>



</body>

</html>