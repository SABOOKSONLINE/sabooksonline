<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Gateway To South African Literature">
    <meta name="author" content="SA Books Online">
    <title>Refund Policy</title>

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

	<style>#rp a, #rp i {color: #e54750 !important;}</style>

</head>

<body>
				
	<?php include 'includes/header-internal.php';?>

	<main>
		<div class="page_header pt-5">
			
		    <div class="container">
			<hr>
		    	<div class="row">
		    		<div class="col-xl-6 col-lg-6 col-md-7 d-none d-md-block">
					<h1>Refund Policy</h1>
						<p><small><a href="index"><i class="icon_house_alt"></i> Home</a> - <a href="#">Documentation</a> - Refund Policy</small></p>
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
						<div class="col-12"><h2 class="">Refund Policy</h2></div>
						<div class="container"><hr></div>
						
						<p>These terms and conditions outline the rules and regulations for the use of SA Books Online's Website, located at www.sabooksonline.co.za.</p>

						<p>By accessing this website we assume you accept these terms and conditions. Do not continue to use SA Books Online if you do not agree to take all of the terms and conditions stated on this page.</p>

						<p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: "Client", "You" and "Your" refers to you, the person log on this website and compliant to the Company’s terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client’s needs in respect of provision of the Company’s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p>

						<h3><strong>Cookies</strong></h3>

						<p>We employ the use of cookies. By accessing SA Books Online, you agreed to use cookies in agreement with the SA Books Online's Privacy Policy. </p>

						<p>Most interactive websites use cookies to let us retrieve the user’s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</p>

						<h3><strong>License</strong></h3>

						<p>Unless otherwise stated, SA Books Online and/or its licensors own the intellectual property rights for all material on SA Books Online. All intellectual property rights are reserved. You may access this from SA Books Online for your own personal use subjected to restrictions set in these terms and conditions.</p>

						<p>You must not:</p>
						<ul class="container">
							<li>Republish material from SA Books Online</li>
							<li>Sell, rent or sub-license material from SA Books Online</li>
							<li>Reproduce, duplicate or copy material from SA Books Online</li>
							<li>Redistribute content from SA Books Online</li>
						</ul>


						<p>Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. SA Books Online does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of SA Books Online,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, SA Books Online shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.</p>

						<p>SA Books Online reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.</p>

						<p>You warrant and represent that:</p>

						<ul>
							<li>You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;</li>
							<li>The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;</li>
							<li>The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy</li>
							<li>The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.</li>
						</ul>

						<p>You hereby grant SA Books Online a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.</p>

						<h3><strong>Hyperlinking to our Content</strong></h3>

						<p>The following organizations may link to our Website without prior written approval:</p>

						<ul>
							<li>Government agencies;</li>
							<li>Search engines;</li>
							<li>News organizations;</li>
							<li>Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and</li>
							<li>System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.</li>
						</ul>

						<p>These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party’s site.</p>

						<p>We may consider and approve other link requests from the following types of organizations:</p>

						<ul>
							<li>commonly-known consumer and/or business information sources;</li>
							<li>dot.com community sites;</li>
							<li>associations or other groups representing charities;</li>
							<li>online directory distributors;</li>
							<li>internet portals;</li>
							<li>accounting, law and consulting firms; and</li>
							<li>educational institutions and trade associations.</li>
						</ul>

						<p>We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of SA Books Online; and (d) the link is in the context of general resource information.</p>

						<p>These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party’s site.</p>

						<p>If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to SA Books Online. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.</p>

						<p>Approved organizations may hyperlink to our Website as follows:</p>

						<ul>
							<li>By use of our corporate name; or</li>
							<li>By use of the uniform resource locator being linked to; or</li>
							<li>By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party’s site.</li>
						</ul>

						<p>No use of SA Books Online's logo or other artwork will be allowed for linking absent a trademark license agreement.</p>

						<h3><strong>iFrames</strong></h3>

						<p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p>

						<h3><strong>Content Liability</strong></h3>

						<p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</p>

						<h3><strong>Your Privacy</strong></h3>

						<p>Please read Privacy Policy</p>

						<h3><strong>Reservation of Rights</strong></h3>

						<p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it’s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</p>

						<h3><strong>Removal of links from our website</strong></h3>

						<p>If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</p>

						<p>We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</p>

						<h3><strong>Disclaimer</strong></h3>

						<p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</p>

						<ul class="list-none">
							<li>limit or exclude our or your liability for death or personal injury;</li>
							<li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
							<li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
							<li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
						</ul>

						<p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</p>

						<p>As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.</p>
					
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