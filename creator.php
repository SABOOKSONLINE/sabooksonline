<?php
                    //DATABASE CONNECTIONS SCRIPT

                    session_start();
                    
					include 'includes/database_connections/sabooks.php';

					if(!isset($_GET['q'])){
						header("Location: 404");
					} else {

						$contentid = str_replace('-',' ', $_GET['q']);

					$sql = "SELECT * FROM users WHERE ADMIN_NAME = '$contentid' OR  ADMIN_USERKEY = '$contentid';";
					$result = mysqli_query($conn, $sql);

					if(!mysqli_num_rows($result)){
						header("Location: 404?LINK-NOT-FOUND");
					} else {
						while($row = mysqli_fetch_assoc($result)) {
							$name = ucwords($row['ADMIN_NAME']);
							$name_right = ucwords($row['ADMIN_NAME']);
							$date = ucwords($row['ADMIN_DATE']);
							$email = ucwords($row['ADMIN_EMAIL']);
							$website = ucwords($row['ADMIN_WEBSITE']);
							$contentid = $row['ADMIN_USERKEY'];
							$desc = ucwords($row['ADMIN_BIO']);
							$cover = $row['ADMIN_PROFILE_IMAGE'];
							$facebook = strtolower($row['ADMIN_FACEBOOK']);
							$instagram = strtolower($row['ADMIN_INSTAGRAM']);
							$twitter = strtolower($row['ADMIN_TWITTER']);
							$type = ucwords($row['ADMIN_TYPE']);
							$contentdata = ucwords($row['ADMIN_USERKEY']);


						}
					}
						
							$name = str_replace(' ','-', $name);
							$name = strtolower($name);
							$name = rtrim($name, "-");
						
					

					}
			
						

					

                    ?>



<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $name_right;?> | SA Books Online | books online | best place to buy books online south africa</title>
    <meta name="description" content="<?php echo $desc;?> | best place to buy books online south africa">
    <meta name="author" content="SA Books Online">
    <meta name="keywords" content="<?php echo $name.','?> sa books online, sa books, sabo, south african author, south african publisher">
    <link rel="icon" href="<?php 
								
	if(empty($cover)){
		$cover = 'https://sabooksonline.co.za/img/sabo-avatar-2.jpeg';
	
	}else if(strpos($cover, 'googleusercontent.com') !== false){
	$cover = $cover;
	} else { $cover = 'https://sabooksonline.co.za/cms-data/profile-images/'.$cover; } 
	echo $cover;?>" disabled media="all">
    <link rel="canonical" href="https://www.sabooksonline.co.za/creator?q=<?php echo $contentid;?>"/>

    <meta property="og:locale"         content="en_US" />
    <meta property="og:type"           content="website" />
    <meta property="og:title"          content="<?php echo $name_right;?> | SA Books Online" />
    <meta property="og:description"    content="<?php echo $desc;?>." />
    <meta name="og:image"              content="<?php echo $cover;?>"/>
    <meta property="og:url"            content="https://www.sabooksonline.co.za/creator?q=<?php echo $contentid;?>" />
    <meta property="og:site_name"      content="<?php echo $name_right;?> | SA Books Online" />
    <meta property="article:publisher" content="<?php echo $name_right;?>" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="<?php echo $desc;?>" />
    <meta name="twitter:title" content="<?php echo $name_right;?> | SA Books Online" />
    <meta name="twitter:site" content="@<?php echo str_replace('https://twitter.com/', '', $twitter);?>" />
    <meta name="twitter:image" content="<?php echo $cover;?>" />
    <meta name="twitter:creator" content="@sabooksonline" />
	
	<meta name=application-name content="SABooksOnline">
	<meta name=msapplication-tooltip content="SABooksOnline">
	<meta name=msapplication-starturl content=/ >   
	<script type=application/ld+json> {
                    "@context": "http://schema.org",
                    "@type": "Organization",
                    "name": "SA Books Online",
                    "url": "https://www.sabooksonline.co.za",
                    "logo": "<?php echo $cover;?>",
                    "sameAs": ["https://www.facebook.com/sabooksonline", "https://twitter.com/sabooksonline"]
                } </script> 
	
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
    <link href="css/detail-page.css" rel="stylesheet">

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
        .owl-item {
            width: fit-content !important;
        }

    </style>
</head>

<body data-spy="scroll" data-bs-target="#secondary_nav" data-offset="75">
	
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0&appId=685468028675340&autoLogAppEvents=1" nonce="8MpmhsYq"></script>			
<?php include 'includes/header-internal.php'?>

<main class="breaker creator-mobile">
	
	<!-- /page_header -->
<div class="container margin_detail_2 mt-3">
	<div class="row">
		<div class="col-lg-12">
			<div class="detail_page_head clearfix">
				<div class="rating">
					<div class="follow_us mt-0 pt-0">
						<!--<div class="fb-share-button" data-href="https://www.sabooksonline.co.za/creator-2?q=<?php echo $name;?>" data-layout="" data-size=""><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div>-->
					</div>
					</div>
					<div class="title d-flex justify-content-start">
						
						<div class="col-lg-4">
							<img src="https://sabooksonline.co.za/img/sabo-png.jpeg" data-src="<?php echo $cover;?>" class="lazy" alt="<?php echo $name_right;?>" width="100px" height="100px" style="border-radius: 5px;margin-right:5%;object-fit: cover;"></div>
						<br>

						<div class="col-lg-8" style="width:100% !important;">
							<h1><?php echo $name_right;?> <small class="icon_check_alt text-success" style="font-size:20px"></small></h1>
							<?php echo $type;?> Joined - <a href="" target="blank"><?php echo $date;?> </a>
							<ul class="tags d-none">
								<li><a href="#0">True Story</a></li>
								<li><a href="#0">Non-Fiction</a></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /detail_page_head -->
				<hr>
				<h4><?php echo $type;?> Information</h4>
				<br>
				<p><?php 	
					
		$desc = str_replace("'", replace: "`", subject: $desc);
		$desc = str_replace("rn", "\n", $desc);
		echo str_replace("rnrn", "\n\n", $desc);
?></p>
	        <!-- /row -->
	    </div>
	    <!-- /container -->

//circular columns - e.g info		
<nav class="secondary_nav">
	<div class="container">
		<ul id="secondary_nav">
			<li><a href="#section-0" class="btn_1 gradient text-white">Information</a></li>
			<li><a href="#section-1">Book Collections</a></li>
			<li><a href="<?php echo $website; ?>" target="_blank"><?php echo $type; ?> Website</a></li>
			<!--<li><a href="#"><i class="social_share"></i>Share</a></li>-->
			<li><a href="mailto:<?php echo $email;?>"><i class="icon_mail_alt"></i><b>Email</b> <?php echo $email;?></a></li>
		</ul>
	</div>
	<span></span>
</nav>
<!-- /secondary_nav -->


		

<div class="bg_gray" id="creator">
	<div class="container margin_detail" id="section-1">
	        <div class="row">
				<div class="col-lg-8 list_menu">
					<section id="section-1">
					<h4>Book Colletions by <b><?php echo str_replace('-', ' ',ucfirst($name)); ?></b></h4><hr>
					<div class="row">
					<?php
						//DATABASE CONNECTIONS SCRIPT
						include 'includes/database_connections/sabooks.php';
						//no authors data found
						if(!isset($_GET['q'])){
							echo 'No Data Found';
						} else {

						$sql = "SELECT * FROM posts WHERE USERID= '$contentdata'";
						$result = mysqli_query($conn, $sql);

						if(!mysqli_num_rows($result)){
							echo 'No Data Found';
						} else {
							while($row = mysqli_fetch_assoc($result)) {
								echo '<div class="col-md-12"> 
								<a class="menu_item d-flex justify-content-start" href="book?q='.strtolower($row['CONTENTID']).'" style="padding: 5px;">
									<div class="">
									<img src="img/place-holder.jpg" data-src="https://sabooksonline.co.za/cms-data/book-covers/'.$row['COVER'].'" alt="thumb" class="lazy" style="width: 6rem;height: 9rem;margin-right:3%;border-radius:5px;border: solid 3px #f3f3f3;">
									</div>

									<div class="p-2">
									<h3>'.$row['TITLE'].'</h3>
									<small class="badge bg-success text-white">'.$row['CATEGORY'].'</small>
										<hr class="p-0 m-0 m-2">
									<p>'.substr($row['DESCRIPTION'], offset: 0, 250).'...</p>
									</div>
								</a>
							</div>';
							}
						}

						}
						
					?>
								
		</div>
	</section>
</div>
	<div class="col-lg-4" id="sidebar_fixed">
	     <div class="box_order mobile_fixed">
			<div class="main">
				<!-- /dropdown -->
				<div class="btn_1_mobile">
					<a href="<?php echo $facebook;?>" class="btn_1 gradient full-width mb_5" target="_blank"><i class="social_facebook"></i> Follow On Facebook</a>
					<a href="<?php echo $instagram;?>" class="btn_1 gradient full-width mb_5" target="_blank"><i class="social_instagram"></i> Follow On Instagram</a>
					<a href="<?php echo $twitter;?>" class="btn_1 gradient full-width mb_5" target="_blank"><i class="social_twitter"></i> Follow  On Twitter</a>
					<div class="text-center"><small>These links are external links</small></div>
				</div>
			</div>
		</div>
	                    <!-- /box_order -->					<div class="btn_reserve_fixed"><a href="#0" class="btn_1 gradient full-width">View Contact Information</a></div>
				</div>
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /bg_gray -->
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
<script src="js/sticky-kit.min.js"></script>
<script src="js/specific_detail.js"></script>
	
		
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