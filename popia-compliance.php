<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SA Books Online | The Gateway To South African Literature">
    <meta name="author" content="SA Books Online">
    <title>Popia Compliance | SA Books Online</title>

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
    <link href="css/listing.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">
<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-V7MRDHEHSZ"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-V7MRDHEHSZ');
	</script>

	<style>#pop a, #pop i {color: #e54750 !important;}</style>

</head>

<body>
				
	<?php include 'includes/header-internal.php';?>

	<main>
		<div class="page_header pt-5">
			
		    <div class="container">
			<hr>
		    	<div class="row">
		    		<div class="col-xl-6 col-lg-6 col-md-7 d-none d-md-block">
					<h1>Content Removal</h1>
						<p><small><a href="index"><i class="icon_house_alt"></i> Home</a> - <a href="#">Documentation</a> - Content Removal</small></p>
		    		</div>
		    		<div class="col-xl-6 col-lg-6 col-md-5">
		    			<div class="search_bar_list">
                            <form action="library">
							<input type="text" class="form-control" id="main-search" placeholder="Search by Title, Authors or Publisher" name="k" value="<?php if(isset($_GET['k'])){
							echo $_GET['k'];
							
							}?>">
							<button type="submit" class="submit"><i class="icon_search"></i></button>
						</form>
						</div>
		    		</div>
		    	</div>
		    	<!-- /row -->		       
		    </div>
		</div>
		<!-- /page_header -->

		<div class="container margin_30_20">			
			<div class="row">
				<?php include 'includes/aside.php';?>

				<div class="col-lg-9">
					<!--<div class="row">
						<div class="col-12">
							<h2 class="title_small">Top Categories</h2>
							<div class="owl-carousel owl-theme categories_carousel_in listing">
								<div class="item">
									<figure>
										<img src="img/cat_listing_placeholder.png" data-src="img/cat_listing_1.jpg" alt="" class="owl-lazy"></a>
										<a href="#0"><h3>Pizza</h3></a>
									</figure>
								</div>
								<div class="item">
									<figure>
										<img src="img/cat_listing_placeholder.png" data-src="img/cat_listing_2.jpg" alt="" class="owl-lazy"></a>
										<a href="#0"><h3>Sushi</h3></a>
									</figure>
								</div>
								<div class="item">
									<figure>
										<img src="img/cat_listing_placeholder.png" data-src="img/cat_listing_3.jpg" alt="" class="owl-lazy"></a>
										<a href="#0"><h3>Dessert</h3></a>
									</figure>
								</div>
								<div class="item">
									<figure>
										<img src="img/cat_listing_placeholder.png" data-src="img/cat_listing_4.jpg" alt="" class="owl-lazy"></a>
										<a href="#0"><h3>Hamburgher</h3></a>
									</figure>
								</div>
								<div class="item">
									<figure>
										<img src="img/cat_listing_placeholder.png" data-src="img/cat_listing_5.jpg" alt="" class="owl-lazy"></a>
										<a href="#0"><h3>Ice Cream</h3></a>
									</figure>
								</div>
								<div class="item">
									<figure>
										<img src="img/cat_listing_placeholder.png" data-src="img/cat_listing_6.jpg" alt="" class="owl-lazy"></a>
										<a href="#0"><h3>Kebab</h3></a>
									</figure>
								</div>
								<div class="item">
									<figure>
										<img src="img/cat_listing_placeholder.png" data-src="img/cat_listing_7.jpg" alt="" class="owl-lazy"></a>
										<a href="#0"><h3>Italian</h3></a>
									</figure>
								</div>
								<div class="item">
									<figure>
										<img src="img/cat_listing_placeholder.png" data-src="img/cat_listing_8.jpg" alt="" class="owl-lazy"></a>
										<a href="#0"><h3>Chinese</h3></a>
									</figure>
								</div>	
							</div>
						</div>
					</div>
					row -->

					
					<div class="row shift-dots">
						<div class="col-12"><h2 class="">POPIA Compliances</h2></div>
						<div class="container"><hr></div>

						
						
						
							<p>In compliance with the Protection of Personal Information Act (POPIA) of South Africa, we are committed to safeguarding your privacy and ensuring the confidentiality of your personal information. This disclaimer outlines our practices regarding the collection, use, and protection of your data.</p>
						

						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>1.</b>Collection of Personal Information </h6>
							<p><small>We may collect personal information from you when you use our website. This includes but is not limited to, information provided through forms, account registration, and user interactions.</small></p>
						</div>

						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>2.</b>Use of Personal Information</h6>
							<p><small>We use your personal information for various purposes, including providing you with the requested services, personalizing your experience on our website, and improving our offerings. Your information may also be used for communication and marketing purposes, but only with your explicit consent.</small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>3.</b>Information Sharing </h6>
							<p><small>We do not sell, trade, or otherwise transfer your personal information to external parties without your consent. However, we may share your information with trusted third parties who assist us in operating our website, conducting our business, or servicing you.</small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>4.</b>Security Measures </h6>
							<p><small>We implement a variety of security measures to protect your personal information. These measures include encryption, access controls, and regular security assessments to ensure the confidentiality and integrity of your data.  </small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>5.</b>Your Rights </h6>
							<p><small>You have the right to access, correct, or delete your personal information held by us. You may also withdraw your consent for certain processing activities. To exercise these rights or for any queries regarding your data, please contact us using the information provided below.</small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>6.</b>Changes to the Privacy Policy</h6>
							<p><small>We reserve the right to modify our privacy policy at any time. Any changes will be communicated through our website, and it is your responsibility to review this policy periodically.</small></p>
						</div>


						
							
							<h6 style="padding-top: 50px;"><b>Contact Information</b></h6>
							<p>If you have any questions or concerns regarding this POPIA disclaimer or our privacy practices, please contact us at admin@sabooksonline.co.za or 067 852 3593].</p>

							<p>By using our website, you acknowledge that you have read, understood, and agree to the terms outlined in this POPIA Act Disclaimer.</p>

							<p><b>Thank you for choosing SA Books Online.</b></p>
						


						


						
						

					</div>
					<!-- /row -->
				</div>
				<!-- /col -->
			</div>		
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
    <script src="js/sticky_sidebar.min.js"></script>
    <script src="js/specific_listing.js"></script>

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