<?php session_start()?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Gateway To South African Literature">
    <meta name="author" content="SA Books Online">
    <title>Verify Account</title>

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
    <link href="css/order-sign_up.css" rel="stylesheet">

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

</head>

<body>
				
	<?php include 'includes/header-internal.php'?>
	
	<main class="bg_gray">

	<div class="page_header pt-5">
			
		    <div class="container">
			<hr>
		    	<div class="row">
		    		<div class="col-xl-6 col-lg-7 col-md-7 d-none d-md-block">
		        		<h1>Confirmation</h1>
		        		
						<p><small><a href="index"><i class="icon_house_alt"></i> Home</a> - Confirmation</small></p>
		    		</div>
		    		<div class="col-xl-6 col-lg-5 col-md-5">
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

				   
		
		<div class="container margin_60_40">
		    <div class="row justify-content-center">
		        <div class="col-lg-4">
		        	<div class="box_order_form">
					<?php 
								if (!isset($_GET['verifyid'])) {
									
									echo "<h5>You are not permited to be on this page!</h5>";
									
								}else {
									
									$id = $_GET['verifyid'];
									
									include 'includes/database_connections/sabooks.php';
									
									$sql = "SELECT * FROM users WHERE RESETLINK = '$id';";
									
									$result = mysqli_query($conn, $sql);
									
									
									if(mysqli_num_rows($result) == false){
										
										echo "<h5>Mmh Looks like your confirmation key was not found.</h5>";
										
									} else {
										
										while ($row = mysqli_fetch_assoc($result)) {
											$reg_name = $row['ADMIN_NAME'];
										$reg_email = $row['ADMIN_EMAIL'];


                                        $_SESSION['ADMIN_ID'] = $row['ADMIN_ID'];
                                        $_SESSION['ADMIN_SUBSCRIPTION'] = $row['ADMIN_SUBSCRIPTION'];
                                        $_SESSION['ADMIN_NAME'] = $row['ADMIN_NAME'];
                                        $_SESSION['ADMIN_EMAIL'] = $row['ADMIN_EMAIL'];
                                        $_SESSION['ADMIN_NUMBER'] = $row['ADMIN_NUMBER'];
                                        $_SESSION['ADMIN_WEBSITE'] = $row['ADMIN_WEBSITE'];
                                        $_SESSION['ADMIN_BIO'] = $row['ADMIN_BIO'];
                                        $_SESSION['ADMIN_TYPE'] = $row['ADMIN_TYPE'];
                                        $_SESSION['ADMIN_FACEBOOK'] = $row['ADMIN_FACEBOOK'];
                                        $_SESSION['ADMIN_TWITTER'] = $row['ADMIN_TWITTER'];
                                        $_SESSION['ADMIN_LINKEDIN'] = $row['ADMIN_LINKEDIN'];
                                        $_SESSION['ADMIN_GOOGLE'] = $row['ADMIN_GOOGLE'];
                                        $_SESSION['ADMIN_INSTAGRAM'] = $row['ADMIN_INSTAGRAM'];
                                        $_SESSION['ADMIN_CUSTOMER_PLESK'] = $row['ADMIN_PINTEREST'];
                                        $_SESSION['ADMIN_PASSWORD'] = $row['ADMIN_PASSWORD'];
                                        $_SESSION['ADMIN_DATE'] = $row['ADMIN_DATE'];
                                        $_SESSION['ADMIN_VERIFICATION_LINK'] = $row['ADMIN_VERIFICATION_LINK'];
                                        $_SESSION['ADMIN_PROFILE_IMAGE'] = $row['ADMIN_PROFILE_IMAGE'];
                                        $_SESSION['ADMIN_USERKEY'] = $row['ADMIN_USERKEY'];
                                        $_SESSION['ADMIN_USER_STATUS'] = $row['ADMIN_USER_STATUS'];
                                        $_SESSION['ADMIN_SERVICES'] = $row['ADMIN_SERVICES'];
                                        $_SESSION['ADMIN_IMAGE01'] = $row['ADMIN_IMAGE01'];
                                        $_SESSION['ADMIN_IMAGE02'] = $row['ADMIN_IMAGE02'];
                                        $_SESSION['ADMIN_IMAGE03'] = $row['ADMIN_IMAGE03'];
                                        $_SESSION['ADMIN_IMAGE04'] = $row['ADMIN_IMAGE04'];
                                        
                        
                                        $name = $_SESSION['ADMIN_NAME'];
                        
                        
                                        echo '<script>window.location.href="https://my.sabooksonline.co.za/sabo/page-dashboard-plan?status=message";</script>';
										}
										
										
										$sql = "UPDATE users SET USER_STATUS = 'Verified'  WHERE RESETLINK='$id'";
											
											if(!mysqli_query($conn, $sql)){
												
												echo '<div id="confirm">
												<div class="icon icon--order-danger svg add_bottom_15">
													<!--<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
														<g fill="none" stroke="#8EC343" stroke-width="2">
															<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
															<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
														</g>
													</svg>-->
												</div>
												<h3>Confirmation Failed!</h3>
												<p>Please send us an email <a href="mailto:admin@sabooksonline.co.za">admin@sabooksonline.co.za</a></p>
											</div>';
												
											}else{

												$message = "Your account has been successfully been confirmed, you may now login to account.";
												$button_link = "https://my.sabooksonline.co.za/sabo/page-dashboard-plans";
												$link_text = "Login To Account";
														
												include '../templates/email.php';
							
												$subject = 'Account email ownership confirmation';
								
													echo ' <div class="main">
													<div id="confirm">
														<div class="icon icon--order-success svg add_bottom_15">
															<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
																<g fill="none" stroke="#8EC343" stroke-width="2">
																	<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
																	<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
																</g>
															</svg>
														</div>
														<h3>Account Confirmed!</h3>
														<p>Your account has been successfully confirmed. You may login here <a href="https://my.sabooksonline.co.za/login">Login To Account</a></p>
													</div>
												</div>';
													
											}
									}
									//excluding image and password
										
									
								}
							?>
		
		               
		            </div>
		            <!-- /box_booking -->
		        </div>
		        <!-- /col -->
		    </div>
		    <!-- /row -->
		</div>
		<!-- /container -->
		
	</main>
	<!-- /main -->

	<?php include 'includes/footer.php'?>
	
	<!-- COMMON SCRIPTS -->
    <script src="js/common_scripts.min.js"></script>
<script src="js/custom.js"></script>
    <script src="js/common_func.js"></script>
    <script src="assets/validate.js"></script>

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