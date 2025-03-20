<?php

$page = '<?php $contentid = "'.$contentid.'";?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            
			
			
			    <meta name="author" content="<?php include "includes/publisher.php";?>">
            <meta name="keywords" content="<?php include "includes/keywords.php";?>">
            <title><?php include "includes/title.php";?> - Published By <?php include "includes/publisher.php";?> SA Books Online</title>
			
				<meta name="description" content="SA Books Online is a literary directory where all South African authors of the various genres centralise 		their literary works for accessibility to their audience consumer. The birth of SA Books Online is inspired by the current transitions of 		literature into the digital space.">
				<meta name="author" content="sabooksonline.co.za">
			
				<link rel="icon" href="favicon.png">

				<link rel="canonical" href="https://www.sabooksonline.co.za/about.php" />

				<meta property="og:locale"         content="en_US" />
				<meta property="og:type"           content="website" />
				<meta property="og:title"          content="<?php include "includes/title.php";?> - South Africas Best Book Directory" />
				<meta property="og:description"    content="SA Books Online is a literary directory where all South African authors of the various genres 		centralise their literary works for accessibility to their audience consumer. The birth of SA Books Online is inspired by the current 			transitions of literature into the digital space." />
				<meta property="og:url"            content="https://www.sabooksonline.co.za/about.php" />
				<meta name="og:image"              content="https://www.sabooksonline.co.za/favicon.png"/>
				<meta property="og:site_name"      content="SA Books Online" />
				<meta property="article:publisher" content="" />

				<meta name="twitter:card" content="summary_large_image" />
				<meta name="twitter:description" content="SA Books Online is a literary directory where all South African authors of the various genres 		centralise their literary works for accessibility to their audience consumer. The birth of SA Books Online is inspired by the current 			transitions of literature into the digital space." />
				<meta name="twitter:title" content="<?php include "includes/title.php";?> - South Africas Best Book Directory" />
				<meta name="twitter:site" content="@sabooksonline" />
				<meta name="twitter:image" content="https://www.sabooksonline.co.za/favicon.png" />
				<meta name="twitter:creator" content="@sabooksonline" />
			
			

            <!-- Favicons-->
            <link rel="shortcut icon" href="../../favicon.png" type="image/x-icon">
            <link rel="apple-touch-icon" type="image/x-icon" href="../../img/apple-touch-icon-57x57-precomposed.png">
            <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="../../img/apple-touch-icon-72x72-precomposed.png">
            <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="../../img/apple-touch-icon-114x114-precomposed.png">
            <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="../../img/apple-touch-icon-144x144-precomposed.png">

            <!-- GOOGLE WEB FONT -->
            <link rel="dns-prefetch" href="https://fonts.gstatic.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="anonymous">
            <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap" as="fetch" crossorigin="anonymous">
            <script type="text/javascript">
            !function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap",r="__3perf_googleFonts_c2536";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
            </script>

            <!-- BASE CSS -->
            <link href="../../css/bootstrap_customized.min.css" rel="stylesheet">
            <link href="../../css/style.css" rel="stylesheet">

            <!-- SPECIFIC CSS -->
            <link href="../../css/detail-page.css" rel="stylesheet">

            <!-- SPECIFIC CSS -->
            <link href="../../css/review.css" rel="stylesheet">

            <!-- ALTERNATIVE COLORS CSS -->
            <link href="#" id="colors" rel="stylesheet">

            <style type="text/css">
                header {
                    background-color: #222;
                }
            </style>

        </head>

        <body>

            <?php include "../../includes/header.php"?>
            <!-- /header -->
            <div style="height:100px !important"></div>
            <main class="mt-5">
                
                <div class="container margin_detail">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="detail_page_head clearfix">
                                <div class="breadcrumbs">
                                    <ul>
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#"><?php include "includes/type.php"?></a></li>
                                        <li><?php include "includes/title.php"?></li>
                                    </ul>
                                </div><br>
                                <div class="title">
                                    <h1><?php include "includes/title.php"?></h1>
                                    Joined - <a href="#0" target="blank">'.$current_time.'</a>
                                    <!--<ul class="tags">
                                        <li><a href="#0">Adult Fiction</a></li>
                                        <li><a href="#0">True Story</a></li>
                                        <li><a href="#0">Romance</a></li>
                                    </ul>-->
                                </div>
                                <div class="rating">
                                    <div class="score"><strong>Verified <?php include "includes/type.php"?></strong></div>
                                </div>
                            </div>
                            <!-- /detail_page_head -->


                            <div class="tabs_detail">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab">Information</a>
                                    </li>
                                    <li class="nav-item">
                                        <a id="tab-B" href="#books" class="nav-link" data-toggle="tab" role="tab">Books</a>
                                    </li>
                                    
                                </ul>

                                <div class="tab-content" role="tablist">
                                    <div id="pane-A" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
                                        <div class="card-header" role="tab" id="heading-A">
                                            <h5>
                                                <a class="collapsed" data-toggle="collapse" href="#collapse-A" aria-expanded="true" aria-controls="collapse-A">
                                                    Information
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapse-A" class="collapse" role="tabpanel" aria-labelledby="heading-A">
                                            <div class="card-body info_content">
                                            <?php include "includes/overview.php"?>
                                            

                                            
                                                <div class="other_info">
                                                
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <b>CONTACT NUMBER:</b>
                                                        <p><?php include "includes/number.php"?></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <b>ADDRESS:</b>
                                                        <p><?php include "includes/address.php"?></p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <b>WEBSITE ADDRESS</b>
                                                        <p><?php include "includes/website.php"?></p>
                                                    </div>
                                                </div>
                                                <!-- /row -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /tab -->

                                </div>
                                <!-- /tab-content -->
                            </div>
                            <!-- /tabs_detail -->
                        </div>
                        <!-- /col -->

                        <div class="col-lg-4" id="sidebar_fixed">
                            <div class="box_booking">
                            <!-- / <div class="head">
                                    <h3>Buy Now!</h3>
                                    <div class="offer">Retail Price R299,00</div>
                                </div>
                                head -->
                            <img src="<?php include "includes/cover.php"?>" width="100%">
                            </div>
                            <!-- /box_booking -->
                            <ul class="share-buttons">
                                <li><a class="fb-share" href="<?php include "includes/facebook.php"?>"><i class="social_facebook"></i> Follow</a></li>
                                <li><a class="twitter-share" href="<?php include "includes/twitter.php"?>"><i class="social_twitter"></i> Follow</a></li>
                                <li><a class="gplus-share" href="<?php include "includes/instagram.php"?>"><i class="social_instagram"></i> Follow</a></li>
                            </ul>
                        </div>

                    </div>
                    <!-- /row -->
                </div>
                <!-- /container -->

                <?php include "../../includes/publisher-other-books.php"?>
                <!-- /container -->
                
            </main>
            <!-- /main -->
            <?php include "../../includes/footer.php"?>
            
            <!-- COMMON SCRIPTS -->
            <script src="../../js/common_scripts.min.js"></script>
            <script src="../../js/common_func.js"></script>
            <script src="../../assets/validate.js"></script>
            
            <!-- SPECIFIC SCRIPTS -->
            <script src="../../js/sticky_sidebar.min.js"></script>
            <script src="../../js/specific_detail.js"></script>
            <script src="../../js/datepicker.min.js"></script>
            <script src="../../js/datepicker_func_1.js"></script>
            <script src="../../includes/register.js"></script>
        </body>
        </html>
                ';
