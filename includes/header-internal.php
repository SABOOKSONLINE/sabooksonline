<style>
    /* Style for the floating buttons */
    .download-button {
      position: fixed;
      bottom: 50%;
      right: -3%;   
      cursor: pointer;
      z-index: 1000;
      transition: transform 0.5s ease-in-out;
      transform: rotate(90deg);
    }

    .download-button-2 {
      position: fixed;
      bottom: 25%;  
      right: -3%;   
      cursor: pointer;
      z-index: 1000;
      transition: transform 0.5s ease-in-out;
      transform: rotate(90deg);
    }      

     /* Mobile responsiveness */
     @media only screen and (max-width: 600px) {
      .download-button,
      .download-button-2 {
        right: -9%;
            
      }

      .download-button img,
      .download-button-2 img {
        width: 100px !important;
            
      }
    }
    
  </style>


<?php  ini_set('session.cookie_domain', '.sabooksonline.co.za');
session_start();

include 'includes/database_connections/sabooks.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

//TRACK USER VIA IP AND STORE
include 'analytics/track_user.php';


?>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
<header style="background: #f3f3f3;">
<div style="width: 100%;height: 20px;background: url(img/brand/02.jpg);background-size:contain;"></div>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container d-flex justify-content-between">
                <div class="col-lg-2  col-md-6">
                    
                    <a class="navbar-brand" href="#">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div id="logo">
                    <a href="index">
                        <img src="https://sabooksonline.co.za/img/social.png" width="100" alt="sa books online logo" class="logo">
                    </a>
                </div></a>
                </div>

        <div class="col-lg-6 search-header">
            <div class="search_bar_list autocomplete">
                <form action="library.php" id="myForm">
                    <input type="text" class="form-control input" id="main-search" placeholder="Search by Title, Authors or Publisher" name="k" value="<?php if(isset($_GET['k'])){
                                        echo $_GET['k'];
                                        
                                        }?>">
                    <button type="submit" class="submit"><i class="icon_search"></i></button>
                </form>
            </div>
	    </div>

        <div class="col-lg-2 col-md-6">
        <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarSupportedContent">

        <?php   
                
                if(!isset($_SESSION['ADMIN_ID']) || !isset($_SESSION['ADMIN_PROFILE_IMAGE'])){
                    echo ' <a href="https://sabooksonline.co.za/pricing-plans" target="_parent" class="register-header">Register</a>
        
                    <a href="https://sabooksonline.co.za/dashboard" class="btn_1">Login <small class="icon_lock_alt"></small></a>';
                }else {

                    $color = 'red';

                    echo ' <a href="includes/logout" target="_parent" class="d- d-xl-block pr-1" style="font-size: .7rem !important;transform:translateX(-.5rem)">logout</a>
        
                    <a href="https://sabooksonline.co.za/dashboard/" class="btn_1 btn-sm">Dashboard <small class="icon_lock_alt"></small></a>';
                }
                
                ?>
                                    
        </div>
        </div>
  </div>

  
</nav>
<hr>

<div class="container">
    <div class="main-menu m-0 p-0">
            <ul class="d-flex justify-content-between">
                <li class="">
                    <a href="index" class="">Home</a>
                </li>
                <li><a href="about" target="_parent">About Us</a></li>
                <li><a href="events" target="_parent">Explore Events</a></li>
                <li><a href="library" target="_parent">Book Catalogue</a></li>
                <li><a href="bookstores" target="_parent">Bookstores</a></li>
                <li><a href="services" target="_parent"  class="show-submenu">Service Providers</a>
            </li>


                <li class="submenu d-non">
                    <a href="#0" class="show-submenu">Register For Membership</a>
                    <ul>
                    <!--<li><a href="register/account" target="_blank">Register For Membership</a></li>-->
                    <li><a href="pricing-plans">Membership Pricing</a></li>
                </ul>
                </li>
                <li><a href="contact" target="_parent">Contact Us</a></li>
            </ul>
    </div>
</div>

<br>

<div class="collapse" id="navbarToggleExternalContent">
  <div class="bg-light p-4">
    <h5 class="text-white h4 d-none">Menu</h5>
                <a href="index" class="">Home</a><hr>
                <a href="about" target="_parent">About Us</a><hr>
                <a href="pricing-plans" target="_parent">Membership Pricing</a><hr>
                <a href="events" target="_parent">Explore Events</a><hr> 
                <a href="library" target="_parent">Book Catalogue</a><hr>
                <a href="bookstores" target="_parent">Bookstores</a><hr>
                <a href="services" target="_parent"  class="show-submenu">Service Providers</a><hr>

                <?php   
                 
                if(!isset($_SESSION['ADMIN_ID']) || !isset($_SESSION['ADMIN_PROFILE_IMAGE'])){
                    echo ' <a href="https://sabooksonline.co.za/pricing-plans" target="_parent" class="register-header">Register</a>
        
                    <a href="https://sabooksonline.co.za/dashboard" class="btn_1">Login <small class="icon_lock_alt"></small></a>';
                }else {

                    $color = 'red';

                    echo '
        
                    <a href="includes/logout" class="btn_1 w-100" style="z-index: 100000 !important;">logout <small class="icon_lock_alt"></small></a>';
                }
                
                ?>
  </div>
</div>
   

</header>



     