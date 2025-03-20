
<?php 
// API SCRIPT FOR FETCHING DATA FROM SABOOKS ONLINE
$fileContent = file_get_contents('includes/api_key.txt');
include 'includes/api_fetch.php';
?>

<?php for ($i = 0; $i < count($title); $i++): ?>
<!doctype html>
<html class="no-js" lang="zxx">


<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $title[$i]?> | SA Books Online</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicons -->

    <!-- Google font (font-family: 'Roboto', sans-serif; Poppins ; Satisfy) -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,600i,700,700i,800"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/plugins.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Cusom css -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- Modernizer js -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>

    <style>
        .header__sidebar__right li a {
            color: #fff !important;
        }
    </style>
</head>

<body>
<!-- Main wrapper -->
<div class="wrapper" id="wrapper">


    <?php include 'includes/header.php';?>
   
    <!-- Start Slider area -->
    <div class="slider-area brown__nav slider--15 slide__activation slide__arrow01 owl-carousel owl-theme" >
        <!-- Start Single Slide -->

        <?php
                                  
                                  //DATABASE CONNECTIONS SCRIPT
                                  include 'includes/db.php';
                                  
                                  $sql = "SELECT * FROM products LIMIT 2";
                                

								if(!$result = mysqli_query($con, $sql)){

									echo "<div class='alert alert-info border-none'>You currently have no content uploaded.<a href='dashboaord-add-book'> Add New Book</a>.</div";

								}else{
									

									while($row = mysqli_fetch_assoc($result)) {
										
	
											echo '<div class="slide animation__style10 fullscreen align__center--left" style="background-color: #f3f3f3 !important">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-lg-7">
                                                        <div class="slider__content">
                                                            <div class="contentbox">
                                                                <h2><span>Buy </span></h2>
                                                                <h2>'.ucwords($row['product_title']).'</h2>
                                                                <h2><span>R'.ucwords($row['product_price']).' </span></h2>
                                                                <a class="shopbtn" href="book?name='.strtolower($row['product_title']).'">shop now</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                
                                                    <div class="col-lg-3">
                                                        <img src="https://sabooksonline.co.za/cms-data/book-covers/'.$row['product_image'].'" style="border-radius: 10px;box-shadow: #f3f3f3 0px 0px 30px;transform: translateY(10%) !important">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
	
									 }
								}
                                 
                 ?>
        
        <!-- End Single Slide -->
    </div>
    <!-- End Slider area -->


<!-- Start About Area -->
    <div class="page-about about_area bg--white section-padding--lg" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section__title--3 text-center pb--30">
                        <h2>About <?php echo $title[$i];?></h2>
                       <!-- <p>the right people for your project</p>-->
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-12 col-sm-12 col-12">
                    <div class="content">
                        <p class="mt--20 mb--20 text-center" id="originalText"><?php echo str_replace('\"','"', str_replace("\'", "'", $desc[$i]));?></p>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End About Area -->


    <!-- Start BEst Seller Area -->
    <section class="wn__product__area brown--color pt--80  pb--30" style="background-color: #f3f3f3 !important" id="shop">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section__title text-center">
                        <h2 class="title__be--2">Book <span class="color--theme">Collection</span></h2>
                        <p>Check out our amazing collection to shop.</p>
                    </div>
                </div>
            </div>
   <!-- Start Single Tab Content -->
   
                <!-- Start Single Product -->

            <?php
                                  
                                  //DATABASE CONNECTIONS SCRIPT
                                  include 'includes/db.php';
                                  
                                  $sql = "SELECT * FROM products";
                                

								if(!$result = mysqli_query($con, $sql)){

									echo "<div class='alert alert-info border-none'>You currently have no content uploaded.<a href='dashboaord-add-book'> Add New Book</a>.</div";

								}else{
									

									while($row = mysqli_fetch_assoc($result)) {
										
	
											echo '<div class="list__view pt-5 pb-5">
                                            <div class="thumb">
                                                <a class="first__img" href="book?name='.strtolower($row['product_title']).'"><img
                                                        src="https://sabooksonline.co.za/cms-data/book-covers/'.$row['product_image'].'" alt="product images" style="height: 300px;width:220px"></a>
                                               
                                            </div>
                                            <div class="content">
                                                <h2><a href="book?name='.strtolower($row['product_title']).'">'.ucwords($row['product_title']).'</a></h2>
                                                <ul class="rating d-flex">
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                    <li><i class="fa fa-star-o"></i></li>
                                                </ul>
                                                <ul class="price__box">
                                                    <li>R'.ucwords($row['product_price']).'</li>
                                                </ul>
                                                <p>'.substr(strtolower($row['product_desc']), 0, 405).'...</p>
                                                <ul class="cart__action d-flex">
                                                    <li class="cart"><a href="book?name='.strtolower($row['product_title']).'">View Info</a></li>
                                                </ul>
        
                                            </div>
                                        </div><hr>';
	
									 }
								}
                                 
                 ?>

         
             
                <!-- Start Single Product -->
                                <!-- End Single Product -->
        </div>
    </section>
    <!-- Start BEst Seller Area -->
  
    <!-- Start Contact Area -->
    <section class="wn_contact_area bg--white pt--80 pb--80"  id="contact">
       <!--<div class="google__map pb--80">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mapouter">
                            <div class="gmap_canvas">
                                <iframe id="gmap_canvas" src="https://maps.google.com/maps?q=121%20King%20St%2C%20Melbourne%20VIC%203000%2C%20Australia&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed"></iframe>
                                <a href="https://sites.google.com/view/maps-api-v2/mapv2"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="contact-form-wrap">
                        <h2 class="contact__title">Get in touch</h2>
                        <form id="messaging">
                            <div class="single-contact-form space-between">
                                <input type="text" name="name" placeholder="Name*">
                                <input type="email" name="email" placeholder="Email*">
                                <input type="hidden" name="emailto" placeholder="Email*" value="<?php echo $email[$i];?>">
                            </div>
                            <div class="single-contact-form">
                                <input type="text" name="subject" placeholder="Subject*">
                            </div>
                            <div class="single-contact-form message">
                                <textarea name="message" placeholder="Type your message here.."></textarea>
                            </div>
                            <div class="contact-btn">
                                <button type="submit" id="reg_load">Send Email</button>
                            </div>
                        </form>
                    </div>
                    <div class="form-output" id="status">
                        
                    </div>
                </div>
                <div class="col-lg-4 col-12 md-mt-40 sm-mt-40">
                    <div class="wn__address">
                        <h2 class="contact__title">Get office info.</h2>
                        <p> </p>
                        <div class="wn__addres__wreapper">

                            <div class="single__address">
                                <i class="icon-location-pin icons"></i>
                                <div class="content">
                                    <span>address:</span>
                                    <p><?php echo $address[$i];?></p>
                                </div>
                            </div>

                            <div class="single__address">
                                <i class="icon-phone icons"></i>
                                <div class="content">
                                    <span>Phone Number:</span>
                                    <p><?php echo $number[$i];?></p>
                                </div>
                            </div>

                            <div class="single__address">
                                <i class="icon-envelope icons"></i>
                                <div class="content">
                                    <span>Email address:</span>
                                    <p><?php echo $email[$i];?></p>
                                </div>
                            </div>

                            <!--<div class="single__address">
                                <i class="icon-globe icons"></i>
                                <div class="content">
                                    <span>website address:</span>
                                    <p>716-298-1822</p>
                                </div>
                            </div>-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Contact Area -->
  
</div>
<!-- //Main wrapper -->

<?php include 'includes/footer.php';?>

<!-- JS Files -->
<script src="js/vendor/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/active.js"></script>


<script>
     $('#reload').load('includes/cart/reload.php');

    setInterval(function(){
    $('#reload').load('includes/cart/reload.php');
    },5000);


</script>


<script type="text/javascript">
  $(document).ready(function(e) {


    $("#messaging").on('submit',(function(e) {
        $("#status").html('<p class="alert alert-info">Processing your message...</p>');
		 e.preventDefault();
        $.ajax({
              url: "message.php",
          type: "POST",
          data:  new FormData(this),
          contentType: false,
              cache: false,
          processData:false,
          success: function(data)
            {
          $("#status").html(data);
            },
            error: function(){}
         });


       

      }));

  });
 

</script>
	


</body>

</html>

<?php endfor; ?>