<?php

        //The registartion code begins
        
        //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $reg_name = mysqli_real_escape_string($conn, $_POST['reg_name']);
        $reg_email = mysqli_real_escape_string($conn, $_POST['reg_email']);
        $reg_type = mysqli_real_escape_string($conn, $_POST['reg_type']);
        $reg_password = mysqli_real_escape_string($conn, $_POST['reg_password']);

        $userkey = str_replace(" ", "", $reg_name);
        $userkey = strtolower(substr($userkey,0,12));

        $combined = $userkey.time();
        $time = substr(uniqid(),'0', '6');
        $userkey = $userkey.$time;

        //TIME VARIABLE
        $d=strtotime("10:30pm April 15 2021");
        $current_time = date('l jS \of F Y');

        $page_overview = "";
        $page_profile_picture = "../../default.jpg";

        $page = '
        
        <!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="description" content="">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


<title>SA Books Online</title>
<meta name="description" content=" ">
    <meta name="author" content="SA Books Online">
    <meta name="keywords" content=" ">
    <link rel="icon" href="favicon.png">

    <meta property="og:locale"         content="en_US" />
    <meta property="og:type"           content="website" />
    <meta property="og:title"          content="SA Books Online" />
    <meta property="og:description"    content=" " />
    <meta property="og:url"            content="https://www.sabooksonline.co.za/" />
    <meta name="og:image"              content="https://www.sabooksonline.co.za/img/logo.png"/>
    <meta property="og:site_name"      content="SA Books Online" />
    <meta property="article:publisher" content="" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:description" content=" " />
    <meta name="twitter:site" content="@" />
    <meta name="twitter:image" content="https://www.sabooksonline.co.za/img/logo.png" />
    <meta name="twitter:creator" content="@" />

<link rel="icon" href="../../img/core-img/xfavicon.ico.pagespeed.ic._ok9cZs0MV.png">

<link href="../../A.style.css%2bcss%2c%2c_responsive%2c%2c_responsive.css%2cMcc.6AgbFnO4WK.css.pagespeed.cf.fHkCu-ESTF.css" rel="stylesheet" />

</head>
<body>
<?php include "../../includes/header.php"?>




<section class="dorne-single-listing-area section-padding-100 mt-5">
<div class="container mt-5">
<div class="row justify-content-center">

<div class="col-12 col-lg-8">
<div class="single-listing-content">
<div class="listing-title">
<h4>Inyani Books </h4>
<h6>Publisher</h6>
</div>
<div class="single-listing-nav">
<nav>
<ul id="listingNav">
<li class="active"><a href="#overview">Overview</a></li>
<li class=""><a href="#books">Books</a></li>

</ul>
</nav>
</div>
<div class="overview-content mt-50" id="overview">
<p><?php include "includes/overview.php"?>
</p>


</div>



</div>
</div>

<div class="col-12 col-md-8 col-lg-4">
<div class="listing-sidebar">

<div class="listing-verify">
<a href="#" class="btn dorne-btn w-100"><i class="fa fa-check pr-3"></i> Verified Listing</a>
</div>

<div class="mt-50">
<img src="<?php include "includes/profile.php"?>">
</div>

</div>
</div>
</div>
</div>
</section>


<section class="dorne-features-destinations-area">
<div class="container">
<div class="row">
<div class="col-12">
<div class="section-heading dark text-left">

<h4>Books By Inyani Books</h4>
<p>Editors pick</p>
<span></span>
</div>
</div>
</div>
<div class="row">
<div class="col-12">
<div class="features-slides owl-carousel">

<div class="single-features-area">
<img src="../../img/book.jpg" alt="">

<div class="price-start">
<p>NEW</p>
</div>
<div class="feature-content d-flex align-items-center justify-content-between">
<div class="feature-title">
<p>I Think Im Depressed</p>
</div>
<div class="feature-favourite">
<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
</div>
</div>
</div>

<div class="single-features-area">
<img src="../../img/book.jpg" alt="">

<div class="price-start">
<p>NEW</p>
</div>
<div class="feature-content d-flex align-items-center justify-content-between">
<div class="feature-title">
<p>I Think Im Depressed</p>
</div>
<div class="feature-favourite">
<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
</div>
</div>
</div>

<div class="single-features-area">
<img src="../../img/book.jpg" alt="">

<div class="price-start">
<p>NEW</p>
</div>
<div class="feature-content d-flex align-items-center justify-content-between">
<div class="feature-title">
<p>I Think Im Depressed</p>
</div>
<div class="feature-favourite">
<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
</div>
</div>
</div>

<div class="single-features-area">
<img src="../../img/book.jpg" alt="">

<div class="price-start">
<p>NEW</p>
</div>
<div class="feature-content d-flex align-items-center justify-content-between">
<div class="feature-title">
<p>I Think Im Depressed</p>
</div>
<div class="feature-favourite">
<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
</div>
</div>
</div>

<div class="single-features-area">
<img src="../../img/book.jpg" alt="">

<div class="price-start">
<p>NEW</p>
</div>
<div class="feature-content d-flex align-items-center justify-content-between">
<div class="feature-title">
<p>I Think Im Depressed</p>
</div>
<div class="feature-favourite">
<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>



<?php include "../../includes/footer.php"?>


<script src="../../js/jquery/jquery-2.2.4.min.js"></script>

<script src="../../js/bootstrap/popper.min.js%2bbootstrap.min.js.pagespeed.jc.XfQ1IyzZZZ.js"></script><script>eval(mod_pagespeed_TW7ywO6P9I);</script>

<script>eval(mod_pagespeed_GQkW5C73xg);</script>

<script src="../../js/others/plugins.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDk9KNSL1jTv4MY9Pza6w8DJkpI_nHyCnk"></script>
<script src="../../js/google-map%2c_location-map-active.js%2bactive.js.pagespeed.jc.Of5h-n90pT.js"></script><script>eval(mod_pagespeed_I9ZaG8jcSW);</script>


</body>

</html>
        
        ';
        
        //VERIFICATION LINK FOR USER

        $veri_link = "https://tnc.edu.za/verify-account.php?verifyid=$combined";

        //INSERT REGISTRATION DATA INTO DATABASE
          $reg_password = password_hash($reg_password, PASSWORD_DEFAULT);

          $sql = "INSERT INTO users (ADMIN_NAME, ADMIN_EMAIL, ADMIN_NUMBER, ADMIN_WEBSITE, ADMIN_BIO, ADMIN_TYPE, ADMIN_FACEBOOK, ADMIN_TWITTER, ADMIN_LINKEDIN, ADMIN_GOOGLE, ADMIN_INSTAGRAM, ADMIN_PINTEREST, ADMIN_PASSWORD, ADMIN_DATE, ADMIN_VERIFICATION_LINK, ADMIN_PROFILE_IMAGE, ADMIN_USERKEY, ADMIN_USER_STATUS) VALUES ('$reg_name','$reg_email','','','','$reg_type','','','','','','','$reg_password','$current_time','$veri_link','','$userkey','pending');";
          
          if(mysqli_query($conn, $sql)){
            
                            //create user profiles

                            if($reg_type == "Publisher"){

                              if(mkdir("../../../publishers/".$userkey)){

                                mkdir("../../../publishers/".$userkey."/includes");
                                fopen("../../../publishers/".$userkey."/index.php", "w");

                              }

                            }else if($reg_type == "Author"){
                              if(mkdir("../../../authors/".$userkey)){

                                mkdir("../../../authors/".$userkey."/includes");

                                $file = "../../../authors/".$userkey."/index.php";
                                $myfile = fopen($file , "w");
                                fwrite($myfile, $page);

                                if(fclose($myfile)){
                                  $page_overview = "../../../authors/".$userkey."/includes/overview.php";
                                  $myfile = fopen($page_overview , "w");
                                  fwrite($myfile, "Hey! My name is".$reg_name.".");

                                  if(fclose($myfile)){
                                    $page_profile = "../../../authors/".$userkey."/includes/profile.php";
                                    $myfile = fopen($page_profile , "w");
                                    fwrite($myfile, $page_profile_picture);
                                  }

                                 
                                //fopen("../../authors/".$userkey."/includes/profile.php", $page_profile);
                                }

                                

                              }
                            }else {
                              echo "<center>Good</center>";
                            }

                            

                
          }else {
                echo "Bad";
          }


         /* $createTable = "CREATE TABLE $userkey (
            ID int(225) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            PRODUCTIMAGE text NOT NULL,
            TITLE text NOT NULL,
            PRICE text NOT NULL,
            COLOR text NOT NULL,
            QUANTITY text NOT NULL,
            CURRENT text NOT NULL,
            PRODUCTID text NOT NULL,
            SIZE text NOT NULL
          );";

          mysqli_query($conn, $createTable);*/
                      
          
          
            
            
          /*if(!mail($reg_email,$subject,$message,$headers)){

            echo "<script>showSwal('warning-email-unsent');</script>";

          }else{
             echo "<script>showSwal('warning-email-sent');</script>";
          }*/


?>