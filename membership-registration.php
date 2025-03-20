<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Gateway To South African Literature">
    <meta name="author" content="SA Books Online">
    <title>Membership Registration</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/favicon.png">

    <!-- GOOGLE WEB FONT -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap" as="fetch" crossorigin="anonymous">
    <script type="text/javascript">
    !function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap",r="__3perf_googleFonts_c2536";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
    </script>

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/contacts.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/review.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">

<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-V7MRDHEHSZ"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-V7MRDHEHSZ');
		gtag('config', 'AW-11379832900');
	</script>

	<style>
	
	#register .copy{text-align:center;position:absolute;padding-top:10px;height:30px;left:0;bottom:30px;width:100%;color:#999}.access_social{margin-top:40px}@media (max-width: 767px){.access_social{margin-top:30px}}.divider{text-align:center;height:1px;margin:30px 0 15px 0;background-color:#ededed}.divider span{position:relative;top:-20px;background-color:#fff;display:inline-block;padding:10px;font-style:italic}a.social_bt{-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px;text-align:center;color:#fff;min-width:200px;margin-bottom:5px;display:block;padding:12px;line-height:1;position:relative;-moz-transition:all 0.3s ease-in-out;-o-transition:all 0.3s ease-in-out;-webkit-transition:all 0.3s ease-in-out;-ms-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out;cursor:pointer}a.social_bt:hover{-webkit-filter:brightness(115%);filter:brightness(115%)}#pass-info{width:100%;margin-bottom:15px;color:#555;text-align:center;font-size:12px;font-size:0.75rem;padding:5px 3px 3px 3px;-webkit-border-radius:3px;-moz-border-radius:3px;-ms-border-radius:3px;border-radius:3px}#pass-info.weakpass{border:1px solid #FF9191;background:#FFC7C7;color:#94546E}#pass-info.stillweakpass{border:1px solid #FBB;background:#FDD;color:#945870}#pass-info.goodpass{border:1px solid #C4EEC8;background:#E4FFE4;color:#51926E}#pass-info.strongpass{border:1px solid #6ED66E;background:#79F079;color:#348F34}#pass-info.vrystrongpass{border:1px solid #379137;background:#48B448;color:#CDFFCD}
	
	</style>

</head>

<body>
				
	<?php include 'includes/header-internal.php';?>
	
	<main class="bg_gray" id="register_bg">

	<div class="page_header pt-5">
			
		    <div class="container">
			<hr>
		    	<div class="row">
		    		<div class="col-xl-6 col-lg-6 col-md-7 d-none d-md-block">
					<h1>Membership Registration</h1>
		        		<a href="#0"><small><i class="icon_house_alt"></i> Home / Registartion</small></a>
		    		</div>
		    		<div class="col-xl-6 col-lg-6 col-md-5">
		    			<form class="search_bar_list" action="library">
							<input type="text" class="form-control" id="main-search" placeholder="Books, Authors or Publisher" name="k" value="<?php if(isset($_GET['k'])){
							echo $_GET['k'];
							}?>">
							<button type="submit" class="submit"><i class="icon_search"></i></button>
						</form>
		    		</div>
		    	</div>
		    	<!-- /row -->		       
		    </div>
		</div>
		<!-- /page_header -->
		
		<div class="margin_60_20" style="background: linear-gradient(to left, rgba(0,0,0,.5),rgba(0,0,0,.5)),url(img/bg_2.jpg);background-size:cover;">
		    <div class="container">
			
		        <div class="col-lg-12" id="register">
		            <div class="box_general write_review">

					<form id="membership">
		                <h1 class="add_bottom_15">Create Your Membership Account</h1>
		                <small>This section is for creating an Author or Publisher account, Please fill in all the required fields to list your books.</small>

		                <hr>

						<div class="row">
							<div class="form-group col-lg-4">
								<small>Author/ Publisher Name <small class="icon_info_alt" data-bs-toggle="tooltip" data-bs-placement="top" title="Name of the Author or Publisher, will be used to identify the content creator."></small></small>
								<input class="form-control" type="text" name="reg_name" placeholder="e.g Jane Doe" required>
							</div>
							<div class="form-group col-lg-4">
								<small>Email Address</small>
								<input class="form-control" type="text" name="reg_email" placeholder="e.g info@youremail.co.za" required>
							</div>
							<div class="form-group col-lg-4">
								<small>Contact Number</small>
								<input class="form-control" type="text" name="reg_number" placeholder="e.g 011 568 4339" required>
							</div>
							
						</div>

						
						<div class="form-group col-lg-12">
							<small>Physical Address</small>
							<input class="form-control" type="text" name="reg_address" placeholder="Tembisa Gauteng" id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" required>
						</div>
					
		                
		                <div class="form-group">
		                    <small>Profile Description (optional) <small class="icon_info_alt" data-bs-toggle="tooltip" data-bs-placement="top" title="A brief description of the Author or Publisher."></small></small>
		                    <textarea class="form-control" style="height: 180px;" placeholder="Write a brief description about your profile" name="reg_bio"></textarea>
		                </div>
						<div class="col-lg-12 bg_gray pt-4 px-4">
							<small>Choose Account Type:</small>
							<div class="form-group radio_input col-lg-12 row">
								<label class="container_radio mr-3 col-lg-4">Publisher Account
									<input type="radio" name="reg_type" value="Publisher" class="required" required>
									<span class="checkmark"></span>
								</label>
								<label class="container_radio col-lg-4">Author Account
									<input type="radio" name="reg_type" value="Author" class="required" required>
									<span class="checkmark"></span>
								</label>

								<label class="container_radio col-lg-4">Service Provider
									<input type="radio" name="reg_type" value="Service Provider" class="required" required>
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
		                <!--<div class="form-group mt-3">
		                    <small>Add your photo (optional)</small>
		                    <div class="fileupload"><input type="file" name="fileupload" accept="image/*"></div>
		                </div>-->

						<hr>

						<div class="row">
							<div class="form-group col-lg-6">
								<small>Create Password</small>
								<input class="form-control" type="password" id="password1"  name="reg_password" placeholder="********" required>
							</div>
							<div class="form-group col-lg-6">
								<small>Confirm Password</small>
								<input class="form-control" type="password" id="password2" name="reg_confirm_password" placeholder="*********" required>
							</div>
							
						</div>
						
						<div id="pass-info" class="clearfix my-4 alert-"></div>
						
		                <div class="form-group">
		                    <div class="checkboxes float-start add_bottom_15 add_top_15">
		                        <label class="container_check">Accept the terms and conditions, in accordance with the POPI Act of South Africa.
		                            <input type="checkbox" required>
		                            <span class="checkmark"></span>
		                        </small>
		                    </div>
		                </div>

						<br>

		                <button class="btn_1 w-100" id="reg_load">Submit Application </button>

						<div id="reg_status"></div>

					</form>
		            </div>
		        </div>
		    </div>
		    <!-- /row -->
		</div>
		<!-- /container -->
		
	</main>
	<!-- /main -->

	<?php include 'includes/footer.php';?>
	
	<!-- COMMON SCRIPTS -->
    <script src="js/common_scripts.min.js"></script>
	<script src="js/custom.js"></script>
    <script src="js/common_func.js"></script>
    <script src="assets/validate.js"></script>

    <!-- SPECIFIC SCRIPTS -->
    <script src="js/specific_review.js"></script>
	
	<!-- SPECIFIC SCRIPTS -->
	<script src="js/pw_strenght.js"></script>	

	<script>
		 //publiish story upload code
		 $("#membership").on('submit',(function(e) {
        e.preventDefault();

            $("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-center" style="width: 100%;height:100%;position:relative;"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            //showSwal('success-message');
        $.ajax({
                url: "includes/backend/member-registration.php",
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