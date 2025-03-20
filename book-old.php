<?php
                    //DATABASE CONNECTIONS SCRIPT
					include 'includes/database_connections/sabooks.php';
					$contentid = $_GET['q'];
					

					if(!isset($_GET['q'])){
						header("Location: 404");
					} else {

					$sql = "SELECT * FROM posts WHERE TITLE = '$contentid'";
					$result = mysqli_query($conn, $sql);

					if(!mysqli_num_rows($result)){
						header("Location: 404");
					} else {
						while($row = mysqli_fetch_assoc($result)) {
							$title = ucwords($row['TITLE']);
							$category = ucwords($row['CATEGORY']);
							$website = $row['WEBSITE'];
							$price = $row['RETAILPRICE'];
							$isbn = $row['ISBN'];
							$contentid = $row['CONTENTID'];
							$desc = $row['DESCRIPTION'];
							$cover = $row['COVER'];
							$userid = $row['USERID'];
							$publisher = ucwords($row['PUBLISHER']);


						}
					}
					

					}
					
				?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Gateway To South African Literature">
    <meta name="author" content="SA Books Online">
    <title><?php echo $title;?></title>

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
						<h1><?php echo $title;?></h1>
		        		<p><small><a href="index"><i class="icon_house_alt"></i> Home</a> - <a href="library">Library</a> - <?php echo ucwords($title);?></small></p>
		    		</div>
		    		<div class="col-xl-6 col-lg-5 col-md-5 autocomplete">
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
	    <div class="container margin_detail_2">
	        <div class="row">
			<div class="col-lg-3">
	                <div class="clearfix">
						<img src="cms-data/book-cover/<?php echo $cover;?>" data-src="cms-data/book-covers/<?php echo $cover;?>" class="lazy w-80" alt="" style="width:100%;border-radius:3%;">
						<div class="mt-2">
	                                <a href="<?php echo $website;?>" class="btn_1 gradient full-width mb_5" target="_blank">Purchase This Book <i class="icon_cart_alt"></i></a>
	                                <div class="text-center"><small>This is an external link.</small></div>
	                            </div>
	                </div>
	            </div>
	            <div class="col-lg-9">
	                <div class="detail_page_head clearfix">
	                    <div class="rating">
						<!--<div class="follow_us">
                            <h5>Follow Us</h5>

							<?php 
							
							
							?>
                            <ul>
                                <li><a href="#0" class="social_facebook_circle text-dark" style="font-size: 25px"></a></li>
                                <li><a href="#0" class="social_twitter_circle text-dark" style="font-size: 25px"></a></li>
                                <li><a href="#0" class="social_instagram_circle text-dark" style="font-size: 25px"></a></li>
                                
                            </ul>
                        </div>-->
	                    </div>
	                    <div class="title d-flex justify-content-start">
							<div class="col-lg-8" style="width:100% !important;">
								<h1><?php echo $title;?></h1>
								Published By - <a href="creator?q=<?php echo $publisher;?>"><?php echo $publisher;?> <small class="icon_check_alt text-success" style="font-size:12px"></small></a>
								<ul class="tags">
									<li><a href="#0"><?php echo $category;?></a></li>
									
								</ul>
							</div>
	                       
	                    </div>
	                </div>
	                <!-- /detail_page_head -->
	                <hr>
					<h4>Book Description</h4>
					<br>
	                <p><?php 
						
						$desc = str_replace("'", "`", $desc);
						
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

	    <div class="secondary_nav sticky_horizontal">
	        <div class="container">
	            <ul id="secondary_nav">
	                <li><a href="#section-0" class="btn_1 gradient text-white">Information</a></li>
	                <li><a href="#section-1">Similar Books</a></li>
	                <li><a href="<?php echo $website;?>" target="_blank">Publisher Website</a></li>
	                <!--<li><a href="#"onclick="myFunction()">Share Link</a></li>-->
					<input value="book?q=<?php echo $contentid;?>" id="myInput" type="hidden" >
	                <li><a href="creator?q=<?php echo $userid;?>"><i class="icon_chat_alt"></i>About <?php echo $publisher;?></a>      					<li><a href="mailto:<?php 
					

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
	            </ul>
	        </div>
	        <span></span>
						</div>
	    <!-- /secondary_nav -->


		<div class="bg_gray">
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
                                                            <figure>
                                                                <span class="ribbon off">'.$row['CATEGORY'].'</span>
                                                                <img src="cms-data/book-covers/'.$row['COVER'].'" class="owl-lazy" alt="" width="460" height="310">
                                                                <a href="book?q='.strtolower($row['TITLE']).'" class="strip_info">
                                                                
                                                                </a>
                                                            </figure>
                                                            <div>
                                                                <a href="creator?q='.strtolower($row['PUBLISHER']).'" class="text-dark">'.ucwords($row['PUBLISHER']).' <small class="icon_check_alt text-success" style="font-size:12px"></small></a>
                                                                <p class="mt-1"><a href="book?q='.strtolower($row['TITLE']).'"><i class="icon_link" style="font-size:10px"></i> '.substr($row['TITLE'], 0, 30).'</a></p>
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


</body>

</html>