<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SA Books Online | The Gateway To South African Literature">
    <meta name="author" content="SA Books Online">
    <title>Content Removal | SA Books Online</title>

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
						<div class="col-12"><h2 class="">Content Removal Disclaimer</h2></div>
						<div class="container"><hr></div>

						
						
						
							<p>This Content Removal Disclaimer ("Disclaimer") is applicable to SA Books Online (the "Website"), owned and operated by SA Books Online, registered in South Africa. This Disclaimer outlines the terms and conditions governing the removal of content from the Website.</p>
						

						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>1.</b>Content Ownership</h6>
							<p><small>The content displayed on the Website, including but not limited to text, images, videos, and any other material, is the property of SA Books Online unless otherwise stated. Users are encouraged to respect the intellectual property rights of the Website. </small></p>
						</div>

						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>2.</b>User-Generated Content </h6>
							<p><small>The Website may allow users to submit content, comments, or other materials ("UserGenerated Content"). SA Books Online is not responsible for the accuracy, completeness, or legality of User-Generated Content. The opinions expressed in such content are solely those of the contributors and do not necessarily represent the views of SA Books Online. </small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>3.</b>Removal of Content </h6>
							<p><small>SA Books Online reserves the right, in its sole discretion, to remove any content from the Website without prior notice. This may include, but is not limited to, content that violates applicable laws, infringes on intellectual property rights, is defamatory, or goes against the Website's policies. </small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>4.</b>User Responsibility </h6>
							<p><small>Users are responsible for the content they submit to the Website. By submitting content, users warrant that they have the legal right to do so and that the content complies with all applicable laws and regulations. Users understand that SA Books Online may remove content at its discretion.  </small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>5.</b>Notification of Violations </h6>
							<p><small>If you believe that any content on the Website violates your rights or applicable laws, you may contact SA Books Online with a written request for content removal. The request should include details of the alleged violation and supporting evidence.  </small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>6.</b>No Guarantee of Removal </h6>
							<p><small>While SA Books will make reasonable efforts to review and respond to content removal requests, there is no guarantee that the requested content will be removed. SA Books Online reserves the right to make the final determination regarding content removal. </small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>7.</b>Changes to Disclaimer </h6>
							<p><small>SA Books Online may update or modify this Disclaimer at any time without prior notice. Users are encouraged to review this Disclaimer periodically to stay informed of any changes.</small></p>
						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

							<h6><b>8.</b>Governing Law </h6>
							<p><small>This Disclaimer is governed by the laws of South Africa. Any disputes arising from or related to this Disclaimer will be subject to the exclusive jurisdiction of the courts in South Africa.</small></p> 
								
							<p><small>By accessing and using the Website, users agree to be bound by this Content Removal Disclaimer. If you do not agree with any part of this Disclaimer, please refrain from using the Website</small></p>
						</div>


						
						

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