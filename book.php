<?php
                    //DATABASE CONNECTIONS SCRIPT

                    session_start();

                    
					include 'includes/database_connections/sabooks.php';
					$contentid = $_GET['q'];
					

					if(!isset($_GET['q'])){
						header("Location: 404");
					} else {
						
					$contentid = str_replace('-',' ', $_GET['q']);

					$sql = "SELECT * FROM posts WHERE TITLE = '$contentid' OR CONTENTID = '$contentid'";
					$result = mysqli_query($conn, $sql);

					if(!mysqli_num_rows($result)){
						header("Location: 404");
					} else {
						while($row = mysqli_fetch_assoc($result)) {
							$id = ucwords($row['ID']);
							$title = ucwords($row['TITLE']);
							$title_right = ucwords($row['TITLE']);
							$category = ucwords($row['CATEGORY']);
							$website = $row['WEBSITE'];
							$price = $row['RETAILPRICE'];
							$isbn = $row['ISBN'];
							$contentid = $row['CONTENTID'];
							$desc = str_replace('`', "'", $row['DESCRIPTION']);
							$cover = $row['COVER'];
							$userid = $row['USERID'];
							$publisher = ucwords($row['PUBLISHER']);
							$author = ucwords($row['AUTHORS']);

						}
					}
					

					}

                    if(empty($author)){
                        $author = '';
                    } else if(!empty($author)) {
                        $author = 'Author - <a href="#">'.$author.'</a>'; 
                    }
					
				?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title_right;?> | SA Books Online</title>
    <meta name="description" content="<?php echo $desc;?>">
    <meta name="author" content="SA Books Online">
    <meta name="keywords" content="<?php echo $title_right.','?> sa books online, sa books, sabo, south african author, south african publisher">
    <link rel="icon" href="<?php 
								
								if(empty($cover)){
									$cover = 'https://sabooksonline.co.za/img/sabo-avatar-2.jpeg';
								
								} else { $cover = 'https://sabooksonline.co.za/cms-data/book-covers/'.$cover; }
								
								echo $cover;?>" disabled media="all">

    <link rel="canonical" href="https://www.sabooksonline.co.za/book?q=<?php echo $contentid;?>"/>

    <meta property="og:locale"         content="en_US" />
    <meta property="og:type"           content="website" />
    <meta property="og:title"          content="<?php echo $title_right;?> | SA Books Online" />
    <meta property="og:description"    content="<?php echo $desc;?>." />
    <meta name="og:image"              content="<?php echo $cover;?>"/>
    <meta property="og:url"            content="https://www.sabooksonline.co.za/book?q=<?php echo $contentid;?>" />
    <meta property="og:site_name"      content="<?php echo $name_right;?> | SA Books Online" />
    <meta property="article:publisher" content="<?php echo $name_right;?>" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="<?php echo $desc;?>" />
    <meta name="twitter:title" content="<?php echo $title_right;?> | SA Books Online" />
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

     <!-- SPECIFIC CSS -->
	 <link href="css/review.css" rel="stylesheet">
     <script src="https://accounts.google.com/gsi/client" async defer></script>

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

	   <!-- SPECIFIC CSS -->
	   <link href="css/home.css" rel="stylesheet">
    <link href="css/vegas.min.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/listing.css" rel="stylesheet"> <!-- SPECIFIC CSS -->
    <link href="css/detail-page.css" rel="stylesheet">

	<style>
        .owl-item {
            width: 15rem !important;
        }


		.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
  
        .owl-item {
            width: fit-content !important;
        }
	


a.fb-h { background: #E54750; }
a.tw-h { background: #E54750; }
a.li-h { background: #E54750; }
a.re-h { background: #E54750; }
a.pi-h { background: #E54750; }



header input {
    width: 100% !important;
}
		
		
    </style>



</head>

<body data-spy="scroll" data-bs-target="#secondary_nav" data-offset="75">
	
		
				
	<?php include 'includes/header-internal.php'?>
	
	<main class="breaker creator-mobile">
		<!-- /page_header -->
	    <div class="container margin_detail_2 mt-5">
	        <div class="row">
			<div class="col-lg-3">
	                <div class="clearfix">
						<img src="<?php echo $cover;?>" data-src="<?php echo $cover;?>" class="lazy w-80" alt="" style="width:100%;border-radius:3%;">
						<div class="mt-2">
	                                <a href="<?php echo $website;?>" id="leaveLink" class="btn_1 gradient full-width mb_5" target="_blank">Purchase This Book <i class="icon_cart_alt"></i></a>
	                                <div class="text-center"><small>This is an external link.</small></div>
	                            </div>
	                </div>
	            </div>
	            <div class="col-lg-9">
	                <div class="detail_page_head clearfix">
	                    <div class="rating">
						
                           <!-- <ul>
                                <li><a href="#0" class="social_facebook_circle text-dark" style="font-size: 25px"></a></li>
                                <li><a href="#0" class="social_twitter_circle text-dark" style="font-size: 25px"></a></li>
                                <li><a href="#0" class="social_instagram_circle text-dark" style="font-size: 25px"></a></li>
                            </ul>-->
							
	                    </div>
	                    <div class="title d-flex justify-content-start">
							<div class="col-lg-8" style="width:100% !important;">
								<h1><?php echo $title;?></h1>
								Published By - <a href="creator?q=<?php echo $userid;?>"><?php echo $publisher;?> <small class="icon_check_alt text-success" style="font-size:12px"></small></a><br>
                                <?php echo $author;?> 
								<ul class="tags">
									<li><a href="#0"><?php echo $category;?></a></li>
								</ul>
							</div>
	                       
	                    </div>
	                </div>
	                <!-- /detail_page_head -->
	                <hr>
					<h4>book Synopsis</h4>
					<br>
	                <p><?php 
						
						//$desc;
						
						//$desc = str_replace("rn", "\n", $desc);
						
						echo str_replace("rnrn", "<br><br>", $desc);
						
						?></p>

					<hr>

					<div class="row ">
					<div class="col-md-6">
		                            <a class="menu_item">
		                                <h3>ISBN NUMBER:</h3>
		                                <strong><?php echo $isbn;?></strong>
		                            </a>
		                        </div>
								<div class="col-md-6">
		                            <a class="menu_item">
		                                <h3>RETAIL PRICE</h3>
		                                <strong>R<?php echo $price;?></strong>
		                            </a>
		                        </div>
					</div>
	            </div>
	            
	        </div>
	        <!-- /row -->
	    </div>
	    <!-- /container -->

        

	    <div class="secondary_nav">
	        <div class="container">
	            <ul id="secondary_nav">
	                <li><a href="#section-0" class="btn_1 gradient text-white">Information</a></li>
	                <li><a href="#section-1">Similar Books</a></li>
	                <li><a href="<?php echo $website;?>" target="_blank">Publisher Website</a></li>
	                <!--<li><a href="#"onclick="myFunction()">Share Link</a></li>-->
					<input value="book?q=<?php echo $contentid;?>" id="myInput" type="hidden">
	                <li><a href="creator?q=<?php echo $publisher;?>"><i class="icon_chat_alt"></i>About <?php echo $publisher;?></a>      					<li><a href="mailto:<?php 
					

					$sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$userid'";
					$result = mysqli_query($conn, $sql);

						if(!mysqli_num_rows($result)){
							header("Location: 404");
						} else {
							while($row = mysqli_fetch_assoc($result)) {
								$email = ucwords($row['ADMIN_EMAIL']);
								
								echo $email;
							}
						}
				
					?>"><i class="icon_mail_alt"></i><b>Email:</b><?php echo $email;?> </a></li>
                    <li><a class="menu_item modal_dialog" href="#modal-dialog"><i class="icon_chat_alt"></i>Add A Review</a></li>
	            </ul>
	        </div>
	        <span></span>
						</div>
	    <!-- /secondary_nav -->


		//to be reviewed shows no use - will be deleted - MK
        <div class="bg_gray">
	        <div class="container margin_detail">
	            <div class="row">
	                <div class="col-lg-8 list_menu">
					<h4>Reviews</h4>
                    <hr>
	                    <div class="row add_bottom_30 d-flex align-items-center reviews d-none">
	                        <div class="col-md-3 ">
	                            <div id="review_summary">
	                                <strong>8.5</strong>
	                                <em>Superb</em>
	                                <small>Based on 4 reviews</small>
	                            </div>
	                        </div>
	                        <div class="col-md-9 reviews_sum_details">
	                            <div class="row">
	                                <div class="col-md-6">
	                                    <h6>Food Quality</h6>
	                                    <div class="row">
	                                        <div class="col-xl-10 col-lg-9 col-9">
	                                            <div class="progress">
	                                                <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
	                                            </div>
	                                        </div>
	                                        <div class="col-xl-2 col-lg-3 col-3"><strong>9.0</strong></div>
	                                    </div>
	                                    <!-- /row -->
	                                    <h6>Service</h6>
	                                    <div class="row">
	                                        <div class="col-xl-10 col-lg-9 col-9">
	                                            <div class="progress">
	                                                <div class="progress-bar" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
	                                            </div>
	                                        </div>
	                                        <div class="col-xl-2 col-lg-3 col-3"><strong>9.5</strong></div>
	                                    </div>
	                                    <!-- /row -->
	                                </div>
	                                <div class="col-md-6">
	                                    <h6>Punctuality</h6>
	                                    <div class="row">
	                                        <div class="col-xl-10 col-lg-9 col-9">
	                                            <div class="progress">
	                                                <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
	                                            </div>
	                                        </div>
	                                        <div class="col-xl-2 col-lg-3 col-3"><strong>6.0</strong></div>
	                                    </div>
	                                    <!-- /row -->
	                                    <h6>Price</h6>
	                                    <div class="row">
	                                        <div class="col-xl-10 col-lg-9 col-9">
	                                            <div class="progress">
	                                                <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
	                                            </div>
	                                        </div>
	                                        <div class="col-xl-2 col-lg-3 col-3"><strong>6.0</strong></div>
	                                    </div>
	                                    <!-- /row -->
	                                </div>
	                            </div>
	                            <!-- /row -->
	                        </div>
	                    </div>
	                    <!-- /row -->
	                    <div id="reviews">

						<?php
							//DATABASE CONNECTIONS SCRIPT

                            

							include 'includes/database_connections/sabooks.php';
							$sqls = "SELECT * FROM reviews WHERE STATUS = 'active' AND CONTENTID = '$contentid' ORDER BY ID DESC";
							$results = mysqli_query($conn, $sqls);
							if(mysqli_num_rows($results) == false){

                                echo '<div>There are no reviews at the moment! Be the first to review <a class="modal_dialog" href="#modal-dialog">Add Review</a></div>';

							}else{
								while($rows = mysqli_fetch_assoc($results)) {

                                    $review_btn = '<div><a href="#modal-dialog" class="btn_1 gradient text-end modal_dialog">Add a review</a></div><br>';

									$review_title = ucfirst($rows['TITLE']);
									$review_review = ucfirst($rows['REVIEW']);
									$review_date = ucfirst($rows['DATEPOSTED']);
									$review_rating = ucfirst($rows['RATING']);
									$review_username = ucfirst($rows['USERNAME']);

									echo '<div class="review_card">
											<div class="row">
												<div class="col-md-2 user_info">
													<figure><img src="img/avatar.jpg" alt=""></figure>
													<h5>'.$review_username.'</h5>
												</div>
												<div class="col-md-10 review_content">
													<div class="clearfix add_bottom_15">
														<span class="rating">'.$review_rating.'<small>/10</small> <strong>Rating average</strong></span>
														<em>'.$review_date.'</em>
													</div>
													<h4>"'.$review_title.'"</h4>
													<p>'.$review_review.'</p>
													<ul class="d-none">
														<li><a href="#0"><i class="icon_like"></i><span>Useful</span></a></li>
														<li><a href="#0"><i class="icon_dislike"></i><span>Not useful</span></a></li>
														<li><a href="#0"><i class="arrow_back"></i> <span>Reply</span></a></li>
													</ul>
												</div>
											</div>  <!-- /row -->
										</div>
									';
									
									}
								}

						?>
	                   
	                    </div>
	                    <!-- /reviews -->
	                    
                        <?php echo $review_btn;?>

	                </div>
	                <!-- /col -->

	                <div class="col-lg-4" id="sidebar_fixed">

					<!--<div id="g_id_onload"
						data-client_id="948933006509-viqkmkurcqd4rvn070qo3dsnf2sk7fpt.apps.googleusercontent.com"
						data-callback="handleCredentialResponse">
					</div>-->
					
					<div class="g_id_signin" data-type="standard"></div>
					

					<div class="access_social mt-2 bg-white">
						<a href="login?provider=<?php echo $id?>" class="social_bt google">Login with Email</a>
					</div>

					<div class="divider"><span>Or</span></div>

					<div class="access_social mt-2">
						<a href="register.php"  class="btn_1 gradient full-width">Create A New Account</a>
					</div>

					<div class="box_order mobile_fixed mt-3 d-none">
		                    <div class="head">
		                        <h3>Contact Us</h3>
		                    	<small>Or Call us at <?php echo $number?></small>
		                        <a href="#0" class="close_panel_mobile"><i class="icon_close"></i></a>
		                    </div>
		                    <!-- /head -->
		                    <div class="main">
		                         <div id="message-detail-contact"></div>
				                    <form method="post" action="assets/detail_contact.php" id="detail_contact" autocomplete="off">
				                    	<input type="text" name="restaurant_name" id="restaurant_name" value="Pizzeria Da Aldredo" hidden="hidden">
				                    	<div class="form-group">
				                    		<input type="text" name="name_detail_contact" id="name_detail_contact" class="form-control" placeholder="Name and Last Name">
					                    </div>
					                    <div class="form-group">
					                    	<input type="email" name="email_detail_contact" id="email_detail_contact" class="form-control" placeholder="Email address">
					                    </div>
					                    <div class="form-group">
					                    	<input type="text" name="telephone_detail_contact" id="telephone_detail_contact" class="form-control" placeholder="Telephone">
					                    </div>
					                    <div class="form-group add_bottom_15">
					                    	<textarea class="form-control" name="message_detail" id="message_detail" placeholder="Your message"></textarea>
					                    </div>
					                     <div class="btn_1_mobile" style="position: relative;">
					                    	<input class="btn_1 gradient full-width" type="submit" value="Send message" id="submit-message">
						               </div>
				                    </form>
				                </div>
		                </div>
	                    
	                    <div class="btn_reserve_fixed"><a href="#0" class="btn_1 gradient full-width">Contact Us</a></div>
	                </div>
	            </div>
	            <!-- /row -->
	        </div>
	        <!-- /container -->
	    </div>
	    <!-- /bg_gray -->

		<div class="zoom-anim-dialog mfp-hide" id="modal-dialog">
		    <div class="row justify-content-center">
		        <div class="col-lg-12">
		            <form class="box_general write_review" id="membership">
		                <h1 class="add_bottom_15">Write a review for "<?php echo $author;?>"</h1>
		                <label class="d-block add_bottom_15 d-none">Overall rating</label>
		                <div class="row">
		                    <div class="col-lg-12 add_bottom_25">
		                        <div class="add_bottom_15">Overall rating <strong class="food_quality_val"></strong> of 10</div>
		                        <input type="range" min="0" max="10" step="1" value="0" data-orientation="horizontal" id="food_quality" name="reviews_rating" required>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label>Title of your review</label>
		                    <input class="form-control" type="text" placeholder="If you could say it in one sentence, what would you say?" name="reviews_title"  maxlength="60" required>
                            <input class="form-control" type="hidden" placeholder="If you could say it in one sentence, what would you say?" name="reviews_userkey"  maxlength="60" value="<?php echo $userkey;?>" required>
                            <input class="form-control" type="hidden" placeholder="If you could say it in one sentence, what would you say?" name="reviews_id"  maxlength="60" value="<?php echo $_GET['q'];?>" required>
                            <input class="form-control" type="hidden" placeholder="If you could say it in one sentence, what would you say?" name="reviews_email"  maxlength="60" value="<?php echo $email;?>" required>
		                </div>
		                <div class="form-group">
		                    <label>Your review</label>
		                    <textarea class="form-control" style="height: 180px;" placeholder="Write your review to help others learn about this online business"  name="reviews_review" maxlength="200" required></textarea>
		                </div>
		                <div class="form-group">
		                    
		                </div>
		                
		                <button class="btn_1" id="reg_load" type="submit">Submit review</button>

						<div id="reg_status"></div>
		            </form>
		        </div>
		    </div>
		    <!-- /row -->
		</div>
		<!-- /container -->


		<div class="bg_gray" id="books">
            <div class="container margin_60">
                <div class="main_title">
                    <h5>Similar Books</h5><br>
                    <span><em></em></span>

                    <a href="library">View All Books &rarr;</a>
                </div>
                <div class="owl-carousel owl-theme carousel_4" id="section-1">

                <?php
                                    //DATABASE CONNECTIONS SCRIPT
                                    include 'includes/database_connections/sabooks.php';
									$sql = "SELECT * FROM posts WHERE STATUS = 'active' AND CATEGORY = '$category' ORDER BY RAND() LIMIT 14;";
                                    //$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
                                    $result = mysqli_query($conn, $sql);
                                    $result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($result) == false){
                                        }else{
                                        while($row = mysqli_fetch_assoc($result)) {

                                            $username = ucwords(substr($row['PUBLISHER'], '0', '20'));

                                            echo ' <div class="item">
                                            <div class="strip">
                                                <a href="book?q='.strtolower($row['CONTENTID']).'"><figure>
                                                    <img src="https://sabooksonline.co.za/cms-data/book-covers/'.$row['COVER'].'" class="owl-lazy" alt="" width="460" height="310">
                                                </figure></a>
                                                <div class="bottom-text">
                                                    <a href="creator?q='.ucfirst($row['PUBLISHER']).'" class="text-dark">'.ucwords($row['PUBLISHER']).' <small class="icon_check_alt text-success" style="font-size:12px"></small></a>
                                                    <p class="mt-1"><a href="book?q='.strtolower($row['CONTENTID']).'">'.substr($row['TITLE'], 0, 20).'</a></p>
                                                </div>
                                            </div>
                                        </div>';
                                        }
                                    }
                                ?>
                   
                 
                </div>
                <!-- /carousel -->
            </div>
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
	<script src="js/custom.js"></script>

    <script>

$(document).ready(function() {

	$("#membership").on('submit',(function(e) {

		e.preventDefault();

		$("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-centerc"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
		
		$.ajax({
				url: "includes/backend/reviews.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
				cache: false,
			processData:false,
			success: function(data)
		{
			$("#reg_load").html('Submit Review');
			$("#reg_status").html(data);
			},
		error: function(){}
		});

	}));
});

</script>

<script src="https://accounts.google.com/gsi/client" async defer></script>


	<script>function myFunction() {
	// Get the text field
	var copyText = document.getElementById("myInput");

	// Select the text field
	copyText.select();
	copyText.setSelectionRange(0, 99999); // For mobile devices

	// Copy the text inside the text field
	navigator.clipboard.writeText(copyText.value);

	// Alert the copied text
	alert("Copied the Link: " + copyText.value);
	}
	</script>
	
    <script>
	var countries = [ <?php
                                    //DATABASE CONNECTIONS SCRIPT
    include 'includes/database_connections/sabooks.php';
    $sql = "SELECT * FROM posts WHERE STATUS = 'active';";
    //$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == false){
    }
	else{
        while($row = mysqli_fetch_assoc($result)) {

        echo '"'.$row["TITLE"].'",';
        }
    }
    ?> " "];
	</script>

	<script src="js/custom.js"></script>

<!-- SPECIFIC SCRIPTS -->
<script src="js/specific_review.js"></script>

<script>
    $(document).ready(function () {
      // Attach a click event to the specific link
      $('#leaveLink').on('click', function (e) {
        // Display a confirmation message
        var confirmation = confirm('You are about to leave our website. Are you sure you want to proceed?');
        
        // If the user clicks Cancel, prevent the link from being followed
        if (!confirmation) {
          e.preventDefault();
        }
      });
    });
  </script>
</body>

</html>