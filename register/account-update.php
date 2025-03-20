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
			<div class="col-lg-6 content-left" style="background: rgba(0,0,0,.8) !important;overflow: hidden !important;height:100vh !important;position:inherit;">

								<div class="col-lg-9">
								


								</div>
				<div class="content-left-wrapper">
					<a href="https://sabooksonline.co.za/" id="logo"><img src="../img/logo-high.png" alt="" width="100"></a>
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
						<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" >
        
								<div class="carousel-inner container w-100" style="height: 100% !important;">

									<?php
																		//DATABASE CONNECTIONS SCRIPT
																		include '../includes/database_connections/sabooks.php';
																		$sql = "SELECT * FROM banners WHERE TYPE = 'Registration' ORDER BY ID ASC LIMIT 1;";
																		//$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
																		$result = mysqli_query($conn, $sql);
																			if(mysqli_num_rows($result) == false){   
																			}else{
																			while($row = mysqli_fetch_assoc($result)) {

																				echo '<div class="carousel-item active">
																				<a href="' . ucwords($row['UPLOADED']) . '" target="_blank"><img src="https://admin-dashboard.sabooksonline.co.za/banners/' . ucwords($row['IMAGE']) . '" class="d-block w-100" alt="' . ucwords($row['SLIDE']) . '" style="border-radius: 10px;height: 100% !important;width: 100% !important;">
																				</div></a>';
																			}
																		}
																	?>

																	<?php
																	include 'includes/database_connections/sabooks.php';
																	$sql = "SELECT * FROM banners WHERE TYPE = 'Registration' ORDER BY ID ASC;";
																	$result = mysqli_query($conn, $sql);

																	$counter = 0; // Initialize a counter to track results

																	if (mysqli_num_rows($result) == false) {   
																		// No results to display
																	} else {
																		while ($row = mysqli_fetch_assoc($result)) {
																			if ($counter > 0) { // Skip the first result
																				echo '<div class="carousel-item">
																					<a href="' . ucwords($row['UPLOADED']) . '" target="_blank"><img src="https://admin-dashboard.sabooksonline.co.za/banners/' . ucwords($row['IMAGE']) . '" class="d-block w-100" alt="' . ucwords($row['SLIDE']) . '" style="border-radius: 10px;height: 100% !important;width: 100% !important;">
																					</div></a>';    
																			}
																			$counter++; // Increment the counter
																		}
																	}
																	?>  
								</div>
									<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
										<span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: #222;border-radius:50%;"></span>
										<span class="visually-hidden">Previous</span>
									</button>
									<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
										<span class="carousel-control-next-icon" aria-hidden="true" style="background-color: #222;border-radius:50%;"></span>
										<span class="visually-hidden">Next</span>
									</button>
								</div>
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

                                <?php 

                                if($_GET['plan'] == 'Deluxe'){

                                echo '<div class="form-group">
                                <label class="container_radio version_2">Book Store
                                    <input type="radio" name="reg_type" value="book-store">
                                    <span class="checkmark"></span>
                                </label>
                                </div>';

                                }
                                
                                ?>
								


							</div>
							<!-- /step-->

							<!-- /step-->
							<div class="step">
								<h3 class="main_question"><strong>2/5</strong>Tell us a little about yourself or organisation</h3>
								<div class="form-group add_top_30">
									<textarea name="reg_bio" class="form-control review_message required" placeholder="Write your review here..." onkeyup="getVals(this, 'review_message');" id="myInput"></textarea>
								</div>

								<div class="form-group add_top_30">
									<label>Logo or Profile Image (Required)<br><small>(Files accepted: gif,jpg,jpeg,.png - Max file size: 150k</small></label>
									<div class="fileupload">
										<input type="file" name="reg_profile" accept="image/*" required>
									</div>
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
										<input type="text" name="reg_name" class="form-control required input" placeholder="Name or Organisation">
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
        The information provided by you is protected and will not be used inappropriately or without consent.  <a href="https://sabooksonline.co.za/popia-compliance">View Our POPI Compliance</a>  
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

<p>By accessing this website, we assume you accept these terms and conditions. Do not continue to use SA Books Online if you do not agree to take all the terms and conditions stated on this page.</p>

<p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: "Client", "You" and "“Your" refer to you, the person log on this website and compliant to the Company’s terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves. All terms refer to the offer, acceptance, and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client’s needs in respect of the provision of the Company’s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same. </p>

<h3><strong>Cookies</strong></h3>

<p>We employ the use of cookies. By accessing SA Books Online, you agree to use cookies in agreement with the SA Books Online's Privacy Policy.</p>

<p>Most interactive websites use cookies to let us retrieve the user’s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</p>

<p><b>Whereas,</b> SA Books Online has developed an end-to-end product and service to widen publishing services offering a platform that provides online billboard, marketing, conduit, and hosting services.</p>

<p><b>Whereas,</b> the platform provides for onboarding through web profiling creation.</p>

<p><b>Whereas,</b> the platform provides for an accurate and reliable online listing of literary works including eBooks.</p>

<p><b>Whereas,</b> the platform provides services for the global marketing of literary works.</p>

<p><b>Whereas,</b> the marketing services are provided through various packages.</p>

<p><b>Whereas,</b> the platform offers conduit services regarding the value-added global literary works.</p>

<p><b>Whereas,</b> SA books online have further vertically integrated into the provision of hosting services through a hosting application.</p>

<p><b>Whereas,</b> SA books online have further vertically integrated into the provision of hosting services through a hosting application.<br><br>
Where SA Books Online has developed a cutting-edge revenue generation subscription services base application for the hosting of literary works.</p>


<p><b>Whereas,</b> the hosting services will guarantee compliance with the general data privacy regulations as well as data privacy provisions</p>


<p><b>Now therefore</b> the rules of engagement are herein provided to inform:</p>

<ol type="a">
	<li>The onboarding processes of the literary works,</li>
	<li>TContent due diligence processes to ensure quality control,</li>
	<li>Remedial action to enable the developmental process to meet set criteria,</li>
	<li>To provide for application revenue generation detail,</li>
	<li>Terms and conditions guiding the deposit, storage, and marketing of the literary works,</li>
	<li>Revenue generation as well as sharing of such subject to specifications</li>
	
</ol>

<div class="col-12"><h2 class="">PRODUCTS AND SERVICES OFFERED</h2></div>

<ol type="1">

<li><b>Web profile creation that:</b>

	<ol type="a">
		<li>Provides membership registration according to self-loaded personal credentials.</li>
		<li>The registration accords segmented forms of registration profiles that range from:
			<ol type="i">
				<li>Free membership with basic access</li>
				<li>Paid membership with added-value services</li>
			</ol>
		</li>

		<li>Measures to ensure compliance with lawful data principles have been instituted and will comply with the relevant applicable data domestic, regional and global privacy and AI policies and Legislative frameworks.</li>
	</ol>

</li>


<li><b> Literary services that include Book listing & collateral services:</b>

	<ol type="a">
		<li>Whose quality standards shall be maintained in line with the determined criteria,</li>
		<li>Whose delivery through a web platform,</li>
		<li>Provides gateway services which include but are not limited to:
			<ol type="i">
				<li>Strict compliance with identified platform quality content criteria for listing</li>
				<li>Provision of a gateway that links content with client preferences</li>
				<li>Redirect content to the platform to provide clients with product choices; BUT</li>
				<li>does not assume responsibility for the ensuring safety of the transactions and product delivery.</li>
			</ol>
		</li>

		<li> Policies governing operations, termination and refund have been determined.</li>
	</ol>

</li>


<h6><b>2.1</b> Financial Implications on Access</h6>
<p> The services are subject to a recurring monthly or annual subscription subject to the subscriber’s cancellation.</p>

<li><b> Mobile application submission portal:</b>

		<ol type="(1)">
			<li>The portal provides for the hosting service:
				<ol type="(a)">
					<li>Whose premium content shall be protected at all material times,</li>
					<li>Whose criteria for loading have been determined are applicable.</li>
				</ol>
			</li>

			<li>Lawful processing fundamentals shall apply to all data that has met the due diligence platform criteria.</li>

			<li><b>Content Quality Control</b>
				<ol type="(a)">
					<li>All content will be subjected to a quality verification process in line with determined guidelines,</li>
					<li>For each submission a complete due diligence report will be generated with the outcome,</li>
					<li>Only Submissions that meet the set eligibility and quality criteria will be hosted on the platform,</li>
					<li>Submissions that have failed to meet the standards will be subjected to optional referral services,</li>
					<li>The platform will provide links to reputable service providers but does not guarantee the quality of service</li>
				</ol>
			</li>
		</ol>

<li><b> Revenue Collection and Royalty Analytics</b>
		<ol type="(1)">
			<li>Mobile app revenue generation: </li>
			<li>SA Books Online provides reader subscription services.</li>
			<li>These reader subscription services provide the revenue source.</li>
		</ol>
	</li>
	<li><b> Generative AI Analytics and Royalty Computation</b>
	  <p>For all literary works purchased the applicable royalty computation will be in terms of the following:</p>
		<ol type="(a)">
			<li>Data analytics on consumption will be computed on a monthly basis on or before the end of each month, </li>
			<li>Should the date be on the weekend /holiday, such computation will take effect on the earliest day,</li>
			<li>The data will be available online for all clients to access, </li>
			<li>Royalty calculations will be computed based on applicable monthly content consumption analytics.</li>
		</ol>
	</li>
	<li><b> Revenue Sharing</b>
		<p>The revenue collection will contain relevant Generative AI to provide data for the recognition of Copyright obligations through determined formulae.</p>
		<ol type="(1)">
			<li>The subscription services shall be shared according to the following determined market-related protocols and standards:
				<ol type="(a)">
					<li>Content Owner - 60%,</li>
					<li>SA Books Online distribution fee 25%,</li>
					<li>App Store in-app purchase fee - 15%.</li>
				</ol>
			</li>
		</ol>
	</li>

	<li><b> Payout Schedule</b></li>
		<p>The payout will be paid out automatically on a quarterly basis, or on request once a month. A requested payout will be subject to a payout fee</p>

	<li><b> Intellectual Property Protection</b></li>
	<p>All submissions are expected to comply with the applicable domestic or regional IP laws. Subject to approved legal prescripts regarding automated services, all forms of infringement are discouraged.</p>

	<li><b> Authentication of Products and Services</b></li>
	<p>To ensure the quality of services, all submissions will have to be subjected to clickwrap agreements. </p>

	<li><b> Non-Disclosure Mandate</b></li>
		<p>This instrument serves as a nondisclosure instrument that informs the rules of engagement in the delivery and consumption of the services.</p>

	<li><b>Amendments and Configuration Management </b></li>
		<p>Any change to the above shall be provided through an addendum to the protocol. The configuration management will reflect the “C for CONFIDENTIAL” as a classification.</p>

	<li><b> Liability</b>
		<p>Liability will be determined by the type of service offering as well as applicable legal requirements in relation to:</p>
		<ol type ="(a)">
			<li>Mere conduit provisioning that entails transmission, routing, as well as automated service, will trigger protection from liability, </li>
			<li>Hosting will trigger liability</li>
		</ol>

	</li>
	<li><b> Dispute Resolution</b>
		<ol type="(1)">
			<li>SA Books Online has opted for a transformative relationship-building online mediation mechanism to deal with all disputes that may arise. </li>
			<li>As provided for by the relevant Electronic Communications Legislation, Disputes that arise as part of the service consumptions will be referred to organs of state that are statutory mandated to deal with such:  
				<ol type="a">
					<li>Domain name disputes</li>
					<li>Cybersquatting</li>
					<li>Online – Cybercrime</li>
					<li>Dis and misinformation.</li>
				</ol>
			</li>
		</ol>
	</li>

	<li><b> Time and Place of Communication, Dispatch and Receipt </b>
	   <p>For purposes of implementation of this protocol</p>
			 <ol type="(1)">
			<li>The data message:</li>
			</ol>
		<ol type="(a)">
			<li>Used for the conclusion or performance of this protocol and instructions flowing from it must be regarded as having been sent by the originator when it enters the information system of the originator,</li>
			<li>Shall be regarded as having been received by the addressee when the complete data message enters the information system designated by the addressee for such purposes</li>
			<li>Shall be regarded as having been sent from the originator’s usual place of business or residence.</li>
		</ol>
		
	</li>

	<li><b> Variation of Agreement Between Parties</b></li>
		<p>The parties agree that the generation, sending, receiving, and storing will be as provided in this protocol. No variation will be allowed.</p>

	<li><b> Acknowledgement of Receipt</b></li>
		<p>No acknowledgement of receipt of the data message is required to give effect to the provisions of the protocol.</p>
		<p>Where such acknowledgement is deemed as necessary, an automated response will be sent.</p>

	<li><b> Commencement of the Applicability of the Provisions </b></li>
		<p>The provisions apply immediately upon authentication of this protocol and delivery.</p>

	<li><b> Cooling Off</b></li>
		<p>Subject to section 44(1) of the Electronic Communications and Transaction Act and any applicable provision, the consumer will be entitled to cancel without reason and without penalty any transaction and any related agreement: -<br> 
		Services within seven days of the after the conclusion of the agreement</p>

</ol>





<h3><strong>Disclaimer</strong></h3>

<p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</p>


<ul>
	<li>limit or exclude our or your liability for death or personal injury;</li>
	<li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
	<li>limit any of our or your liabilities in any way that is not permitted under applicable law; <br>
	or</li>
	<li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
</ul>


<p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty</p>

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