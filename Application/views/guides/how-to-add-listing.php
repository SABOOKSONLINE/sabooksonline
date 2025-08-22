<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Gateway To South African Literature">
    <meta name="author" content="SA Books Online">
    <title>How to add a listing</title>

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
		gtag('config', 'AW-11379832900');
	</script>

	<style>#tl a, #tl i {color: #e54750 !important;}</style>

</head>

<body>
				
	<?php include 'includes/header-internal.php';?>

	<main>
		<div class="page_header pt-5">
			
		    <div class="container">
			<hr>
		    	<div class="row">
		    		<div class="col-xl-6 col-lg-6 col-md-7 d-none d-md-block">
					<h1>How to add a listing</h1>
						<p><small><a href="index"><i class="icon_house_alt"></i> Home</a> - <a href="#">Guides</a> - How to add a listing </small></p>
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
				<?php include 'includes/aside-guides.php';?>

				<div class="col-lg-9">
				<div class="row shift-dots">
						<div class="col-12"><h2 class="">Guide - How to add a new book</h2></div>
						<div class="container"><hr></div>

						
						<div class="mt-5" style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">
						<h6><b>1.</b> Login to your account</h6>
						<p><small>You need to navigate to <a href="login?guide">Login Page</a> and fill in your registered <i><b>Email Address</b></i> and your <i><b>Password</b></i>. Once completed click on login now, this will navigate you to your dashboard once logged in.</small></p>

						<img src="img/guides/6.jpg" width="100%" style="border-radius: 10px !important;border: #fff solid 3px">

						</div>

						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">
						
						<h6><b>2.</b> Navigate to book listings</h6>
						<p><small>Once you have looged in and in your dashboard you might wanna complete your profile if its on 50% or less. However you need to navigate to <i><b>Book Listings</i></b> on your left navigation.</small></p>

						<img src="img/guides/7.jpg" width="100%" style="border-radius: 10px !important;border: #fff solid 3px">

						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">
						
						<h6><b>3.</b> Click add book listing</h6>
						<p><small>Under Book Listings is where all your content will be shown an can be edited or removed. To add a Book Listing click on the button <i><b>"Add Book Listing"</i></b> as indicated below.</small></p>
						<img src="img/guides/10.jpg" width="100%" style="border-radius: 10px !important;border: #fff solid 3px">

						</div>


						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

						<h6><b>4.</b> Add your book listing</h6>
						<p><small>On this page you need to <i><b>Fill in your book information</i></b> once completed click on add book, NB your book will not show up on the website if your profile is not atleast at 80% complete.</small></p>

						<img src="img/guides/11.jpg" width="100%" style="border-radius: 10px !important;border: #fff solid 3px">

						</div>

						<div class="mt-5"  style="background: #f3f3f3;padding:5%;border-radius: 10px !important;">

						<h6><b>5.</b> View your books</h6>
						<p><small>Once completed you will be redirected back to Book Listings where your content will show up, as soon as you make a payment your book will have a status of Active. To make payment you need to goto <a href="dashboard-invoices?guide">Invoices</a></small></p>

						<img src="img/guides/12.PNG" width="100%" style="border-radius: 10px !important;border: #fff solid 3px">

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