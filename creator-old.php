<?php
                    //DATABASE CONNECTIONS SCRIPT
					include 'includes/database_connections/sabooks.php';

					if(!isset($_GET['q'])){
						header("Location: 404");
					} else {

						$contentid = $_GET['q'];

					$sql = "SELECT * FROM users WHERE ADMIN_NAME = '$contentid';";
					$result = mysqli_query($conn, $sql);

					if(!mysqli_num_rows($result)){
						header("Location: 404?NOT-FOUND");
					} else {
						while($row = mysqli_fetch_assoc($result)) {
							$name = ucwords($row['ADMIN_NAME']);
							$date = ucwords($row['ADMIN_DATE']);
							$email = ucwords($row['ADMIN_EMAIL']);
							$website = ucwords($row['ADMIN_WEBSITE']);
							$contentid = $row['ADMIN_USERKEY'];
							$desc = ucwords($row['ADMIN_BIO']);
							$cover = $row['ADMIN_PROFILE_IMAGE'];
							$facebook = ucwords($row['ADMIN_FACEBOOK']);
							$instagram = ucwords($row['ADMIN_INSTAGRAM']);
							$twitter = ucwords($row['ADMIN_TWITTER']);
							$type = ucwords($row['ADMIN_TYPE']);
							$contentdata = ucwords($row['ADMIN_USERKEY']);


						}
					}
					

					}
					
				?>



<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SA Books Online">
    
	<title><?php echo $name;?> | SA Books Online</title>
    <meta name="description" content="<?php echo $desc;?>">
    <meta name="author" content="SA Books Online">
    <meta name="keywords" content="<?php echo $name.','?> sa books online, sa books, sabo, south african author, south african publisher">
    <link rel="icon" href="<?php 
								
								if(empty($cover)){
									$cover = 'https://my.sabooksonline.co.za/img/sabo-avatar-2.jpeg';
								
								} else { $cover = 'https://my.sabooksonline.co.za/cms-data/profile-images/'.$cover; }
								
								echo $cover;?>" disabled media="all">

    <link rel="canonical" href="https://www.sabooksonline.co.za/creator-2?q=<?php echo $name;?>"/>

    <meta property="og:locale"         content="en_US" />
    <meta property="og:type"           content="website" />
    <meta property="og:title"          content="<?php echo $name;?> | SA Books Online" />
    <meta property="og:description"    content="<?php echo $desc;?>." />
    <meta name="og:image"              content="<?php echo $cover;?>"/>
    <meta property="og:url"            content="https://www.sabooksonline.co.za/creator-2?q=<?php echo $name;?>" />
    <meta property="og:site_name"      content="<?php echo $name;?> | SA Books Online" />
    <meta property="article:publisher" content="<?php echo $name;?>" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content="<?php echo $desc;?>" />
    <meta name="twitter:title" content="<?php echo $name;?> | SA Books Online" />
    <meta name="twitter:site" content="@<?php echo $twitter;?>" />
    <meta name="twitter:image" content="<?php echo $cover;?>" />
    <meta name="twitter:creator" content="@sabooksonline" />
	
	
   <!-- <link rel="apple-touch-icon" type="image/x-icon" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/favicon.png">-->

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
	</script>
	
	 <style>
        .owl-item {
            width: fit-content !important;
        }
		
		
		* { box-sizing: border-box; }

.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
		

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9;
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important;
  color: #ffffff;
}
		 
		 .share-list {
  display: flex;
  flex-direction: row;
}

.share-list a {
  border-radius: 100px;
  width: 50px;
  height: 50px;
  padding: 7px;
  margin: 10px;
  cursor: pointer;
  overflow: hidden;
  
  img {
    width: 100%;
    height: 100%;
    filter: invert(100%);
  }
}

a.fb-h { background: #3B5998; }
a.tw-h { background: #00acee; }
a.li-h { background: #0077B5; }
a.re-h { background: #FF5700; }
a.pi-h { background: #c8232c; }



// Page Styling
* {
  margin: 0;
  pading: 0;
  box-sizing: border-box;
}

.share-buttons-container {
  display: flex;
  justify-content: center;
  align-items: center;
  background: #bcbcf2;
  width: 100%;
  height: 100vh;
}
		
		
    </style>

</head>

<body data-spy="scroll" data-bs-target="#secondary_nav" data-offset="75">
				
	<?php include 'includes/header-internal.php'?>
	
	<main>
	<div class="page_header pt-5">
			
		    <div class="container">
			<hr>
		    	<div class="row">
		    		<div class="col-xl-6 col-lg-7 col-md-7 d-none d-md-block">
						<h1><?php echo $name;?></h1>
		        		<a href="#0"><small><i class="icon_house_alt"></i> Home - <?php echo $type;?> - <?php echo $name;?></small></a>
		    		</div>
		    		<div class="col-xl-6 col-lg-5 col-md-5 autocomplete">
		    			<form class="search_bar_list " action="library">
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
	    <div class="container margin_detail_2">
	        <div class="row">
	            <div class="col-lg-12">
	                <div class="detail_page_head clearfix">
					<div class="rating">
						<div class="follow_us">
                            <h5>Follow Us</h5>

							<?php 
							
							
							?>
                            <ul>
                                <li><a href="#0" class="social_facebook_circle text-dark" style="font-size: 25px"></a></li>
                                <li><a href="#0" class="social_twitter_circle text-dark" style="font-size: 25px"></a></li>
                                <li><a href="#0" class="social_instagram_circle text-dark" style="font-size: 25px"></a></li>
                                
                            </ul>
                        </div>
	                    </div>
	                    <div class="title d-flex justify-content-start">
							
							<div class="col-lg-4"><img src="https://my.sabooksonline.co.za/img/sabo-png.jpeg" data-src="<?php echo $cover;?>" class="lazy" alt="<?php echo $name;?>" width="100px" height="100px" style="border-radius: 5px;margin-right:5%;object-fit: cover;"></div>
	
							<br>

							<div class="col-lg-8" style="width:100% !important;">
								<h1><?php echo $name;?> <small class="icon_check_alt text-success" style="font-size:20px"></small></h1>
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
						
						$desc = str_replace("'", "`", $desc);
						
						$desc = str_replace("rn", "\n", $desc);
						
						echo str_replace("rnrn", "\n\n", $desc);
						?></p>
	            </div>
	            <!--<div class="col-lg-4">
	                <div class="pictures magnific-gallery clearfix">
	                   <figure>
						    <a href="img/detail_gallery/detail_1.jpg" title="Photo title" data-effect="mfp-zoom-in">
						        <img src="img/thumb_detail_1.jpg" data-src="img/thumb_detail_1.jpg" class="lazy" alt="">
						    </a>
						</figure>
						<figure>
							<a href="img/detail_gallery/detail_2.jpg" title="Photo title" data-effect="mfp-zoom-in">
								<img src="img/thumb_detail_2.jpg" data-src="img/thumb_detail_2.jpg" class="lazy" alt="">
							</a>
						</figure>
						<figure>
							<a href="img/detail_gallery/detail_3.jpg" title="Photo title" data-effect="mfp-zoom-in">
							<img src="img/thumb_detail_3.jpg" data-src="img/thumb_detail_3.jpg" class="lazy" alt="">
						</a>
						</figure>
						<figure>
							<a href="img/detail_gallery/detail_4.jpg" title="Photo title" data-effect="mfp-zoom-in">
								<img src="img/thumb_detail_4.jpg" data-src="img/thumb_detail_4.jpg" class="lazy" alt="">
							</a>
						</figure>
						<figure>
							<a href="img/detail_gallery/detail_4.jpg" title="Photo title" data-effect="mfp-zoom-in">
								<img src="img/thumb_detail_6.jpg" data-src="img/thumb_detail_6.jpg" class="lazy" alt="">
							</a>
						</figure>
						<figure>
							<a href="img/detail_gallery/detail_5.jpg" title="Photo title" data-effect="mfp-zoom-in">
								<span class="d-flex align-items-center justify-content-center">+10</span>
								<img src="img/thumb_detail_5.jpg" data-src="img/thumb_detail_5.jpg" class="lazy" alt="">
							</a>
						</figure>
	                </div>
	            </div>-->
	        </div>
	        <!-- /row -->
	    </div>
	    <!-- /container -->

	    <nav class="secondary_nav sticky_horizontal">
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
					<h4>Book Colletions by <b><?php echo $name; ?></b></h4><hr>
		                    <div class="row">
		                        

								<?php
											//DATABASE CONNECTIONS SCRIPT
											include 'includes/database_connections/sabooks.php';
											
											

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
													<a class="menu_item d-flex justify-content-start" href="book?q='.strtolower($row['TITLE']).'" style="padding: 5px;">
														<div class="">
														<img src="img/place-holder.jpg" data-src="cms-data/book-covers/'.$row['COVER'].'" alt="thumb" class="lazy" style="width: 6rem;height: 9rem;margin-right:3%;border-radius:5px;border: solid 3px #f3f3f3;">
														</div>

														<div class="p-2">
														<h3>'.$row['TITLE'].'</h3>
														<small class="badge bg-success text-white">'.$row['CATEGORY'].'</small>
															<hr class="p-0 m-0 m-2">
														<p>'.substr($row['DESCRIPTION'], 0, 250).'...</p>
														</div>
													</a>
												</div>';
												}
											}
											

											}
											
										?>
		                        
		                    </div>
		                    <!-- /row -->
		                </section>

	                </div>
	                <!-- /col -->

	                <div class="col-lg-4" id="sidebar_fixed">
	                    <div class="box_order mobile_fixed">
	                        <!--<div class="head">
	                            <h3>Contact Information</h3>
	                            <a href="#0" class="close_panel_mobile"><i class="icon_close"></i></a>
	                        </div>-->
	                        <!-- /head -->
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
	                    <!-- /box_order -->
	                    
	                    <div class="btn_reserve_fixed"><a href="#0" class="btn_1 gradient full-width">View Contact Information</a></div>
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
	
	<script>
		
	function autocomplete(inp, arr) {
	  /*the autocomplete function takes two arguments,
	  the text field element and an array of possible autocompleted values:*/
	  var currentFocus;
	  /*execute a function when someone writes in the text field:*/
	  inp.addEventListener("input", function(e) {
		  var a, b, i, val = this.value;
		  /*close any already open lists of autocompleted values*/
		  closeAllLists();
		  if (!val) { return false;}
		  currentFocus = -1;
		  /*create a DIV element that will contain the items (values):*/
		  a = document.createElement("DIV");
		  a.setAttribute("id", this.id + "autocomplete-list");
		  a.setAttribute("class", "autocomplete-items");
		  /*append the DIV element as a child of the autocomplete container:*/
		  this.parentNode.appendChild(a);
		  /*for each item in the array...*/
		  for (i = 0; i < arr.length; i++) {
			/*check if the item starts with the same letters as the text field value:*/
			if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
			  /*create a DIV element for each matching element:*/
			  b = document.createElement("DIV");
			  /*make the matching letters bold:*/
			  b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
			  b.innerHTML += arr[i].substr(val.length);
			  /*insert a input field that will hold the current array item's value:*/
			  b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
			  /*execute a function when someone clicks on the item value (DIV element):*/
				  b.addEventListener("click", function(e) {
				  /*insert the value for the autocomplete text field:*/
				  inp.value = this.getElementsByTagName("input")[0].value;
				  /*close the list of autocompleted values,
				  (or any other open lists of autocompleted values:*/
				  closeAllLists();
			  });
			  a.appendChild(b);
			}
		  }
	  });
	  /*execute a function presses a key on the keyboard:*/
	  inp.addEventListener("keydown", function(e) {
		  var x = document.getElementById(this.id + "autocomplete-list");
		  if (x) x = x.getElementsByTagName("div");
		  if (e.keyCode == 40) {
			/*If the arrow DOWN key is pressed,
			increase the currentFocus variable:*/
			currentFocus++;
			/*and and make the current item more visible:*/
			addActive(x);
		  } else if (e.keyCode == 38) { //up
			/*If the arrow UP key is pressed,
			decrease the currentFocus variable:*/
			currentFocus--;
			/*and and make the current item more visible:*/
			addActive(x);
		  } else if (e.keyCode == 13) {
			/*If the ENTER key is pressed, prevent the form from being submitted,*/
			e.preventDefault();
			if (currentFocus > -1) {
			  /*and simulate a click on the "active" item:*/
			  if (x) x[currentFocus].click();
			}
		  }
	  });
	  function addActive(x) {
		/*a function to classify an item as "active":*/
		if (!x) return false;
		/*start by removing the "active" class on all items:*/
		removeActive(x);
		if (currentFocus >= x.length) currentFocus = 0;
		if (currentFocus < 0) currentFocus = (x.length - 1);
		/*add class "autocomplete-active":*/
		x[currentFocus].classList.add("autocomplete-active");
	  }
	  function removeActive(x) {
		/*a function to remove the "active" class from all autocomplete items:*/
		for (var i = 0; i < x.length; i++) {
		  x[i].classList.remove("autocomplete-active");
		}
	  }
	  function closeAllLists(elmnt) {
		/*close all autocomplete lists in the document,
		except the one passed as an argument:*/
		var x = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < x.length; i++) {
		  if (elmnt != x[i] && elmnt != inp) {
		  x[i].parentNode.removeChild(x[i]);
		}
	  }
	}
	/*execute a function when someone clicks in the document:*/
	document.addEventListener("click", function (e) {
		closeAllLists(e.target);
	});
	}

		
		autocomplete(document.getElementById("main-search"), countries);
	
	</script>
	
	<script>
	var pageLink = window.location.href;
	var pageTitle = String(document.title).replace(/\&/g, '%26');

	function fbs_click() { window.open(`http://www.facebook.com/sharer.php?u=${pageLink}&quote=${pageTitle}`,'sharer','toolbar=0,status=0,width=626,height=436');return false; }

	function tbs_click() { window.open(`https://twitter.com/intent/tweet?text=${pageTitle}&url=${pageLink}`,'sharer','toolbar=0,status=0,width=626,height=436');return false; }

	function lbs_click() { window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${pageLink}`,'sharer','toolbar=0,status=0,width=626,height=436');return false; }

	function rbs_click() { window.open(`https://www.reddit.com/submit?url=${pageLink}`,'sharer','toolbar=0,status=0,width=626,height=436');return false; }

	function pbs_click() { window.open(`https://www.pinterest.com/pin/create/button/?&text=${pageTitle}&url=${pageLink}&description=${pageTitle}`,'sharer','toolbar=0,status=0,width=626,height=436');return false; }

	</script>

</body>

</html>