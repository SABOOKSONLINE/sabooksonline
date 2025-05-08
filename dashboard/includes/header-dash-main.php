<?php 

//Set the cookie domain to ".sabooksonline.co.za" to make it available to all subdomains
$cookieDomain = ".sabooksonline.co.za";
session_set_cookie_params(0, '/', $cookieDomain);


session_start();

if(!isset($_SESSION['ADMIN_USERKEY'])){
header("Location: /login?redirectlog2");
} ?>

<style>
    @keyframes moveDown {
  from {
    transform: translateY(0);
  }
  to {
    transform: translateY(50px);
  }
}

@keyframes moveUp {
  from {
    transform: translateY(0);
  }
  to {
    transform: translateY(-20px);
  }
}

.move-down {
  animation: moveDown 2s ease-in-out;
}

.move-up {
  animation: moveUp 1s ease-in-out;
}

</style>
<a href="#logout" class="text-center text-white move-down" style="position: fixed !important;background-color: #222;width:30px;height:30px;border-radius:50%;box-shadow: rgba(0,0,0,.5);z-index: 1000;bottom:10%;left:15%;"><i class="fa fa-arrow-down"></i></a>


<header class="header-nav nav-innerpage-style menu-home4 dashboard_header main-menu">

    <!-- Ace Responsive Menu -->
    <nav class="posr"> 
      <div class="container-fluid pr30 pr15-xs pl30 posr menu_bdrt1">
        <div class="row align-items-center justify-content-between">
          <div class="col-6 col-lg-auto">
            <div class="text-center text-lg-start d-flex align-items-center">
              <div class="dashboard_header_logo position-relative me-2 me-xl-5">
                <a href="https://sabooksonline.co.za/" class="logo"><img src="https://sabooksonline.co.za/img/social.png" alt="" width="100px"></a>
              </div>
              <div class="fz20 ml90">
                <a href="#" class="dashboard_sidebar_toggle_icon vam"><img src="images/dashboard-navicon.svg" alt=""></a>
              </div> 
              <a class="login-info d-block d-xl-none ml40 vam" data-bs-toggle="modal" href="#exampleModalToggle" role="button"><span class="flaticon-loupe"></span></a>
              <div class="ml40 d-none d-xl-block">
                <!--<div class="search_area dashboard-style">
                  <input type="text" class="form-control border-0" placeholder="What service are you looking for today?">
                  <label><span class="flaticon-loupe"></span></label>
                </div>-->
              </div>
            </div>
          </div>

                <li class="user_setting">
                  <div class="dropdown">

                       <?php 
                
                    if(!isset($_SESSION['ADMIN_ID']) || !isset($_SESSION['ADMIN_PROFILE_IMAGE'])){
                        echo 'Login';
                    }else {
						
						$string = $_SESSION['ADMIN_PROFILE_IMAGE'];

						if (strpos($string, 'googleusercontent.com') !== false) {
							  echo ' <a class="btn" href="#" data-bs-toggle="dropdown">
                        <img src="'.$_SESSION['ADMIN_PROFILE_IMAGE'].'" alt="user.png" style="width: 50px !important;height: 50px !important;border-radius: 50%"> 
                      </a>';
						} else {  
							
							  echo ' <a class="btn" href="#" data-bs-toggle="dropdown">
                        <img src="https://sabooksonline.co.za/cms-data/profile-images/'.$_SESSION['ADMIN_PROFILE_IMAGE'].'" alt="user.png" style="width: 50px !important;height: 50px !important;border-radius: 50%"> 
                      </a>';
						}

                    }
					  
					  
                    
                    ?>
                    
                    
                    <div class="dropdown-menu">
                      <div class="user_setting_content">
                       
                        <p class="fz15 fw400 ff-heading mt30 pl30">Account Management</p>
                        <a href="service-plan" class="dropdown-item subscriptions"><i class="flaticon-receipt mr15"></i>Account Billing</a>

                        <a href="plan" class="dropdown-item subscriptions-1"><i class="flaticon-receipt mr15"></i>Subscription Plans</a>

                        <a class="dropdown-item" href="profile"><i class="flaticon-photo mr10"></i>My Profile</a>

                        <a class="dropdown-item" href="../includes/logout"><i class="flaticon-logout mr10"></i>Logout</a>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <!-- Search Modal -->
  <div class="search-modal">
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fal fa-xmark"></i></button>
          </div>
          <div class="modal-body">
            <div class="popup-search-field search_area">
              <input type="text" class="form-control border-0" placeholder="What service are you looking for today?">
              <label><span class="far fa-magnifying-glass"></span></label>
              <button class="ud-btn btn-thm" type="submit">Search</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Nav  -->
  <div id="page" class="mobilie_header_nav stylehome1">
    <div class="mobile-menu">
      <div class="header bdrb1">
        <div class="menu_and_widgets">
          <div class="mobile_menu_bar d-flex justify-content-between align-items-center">
            <a class="mobile_logo" href="https://sabooksonline.co.za/"><img src="https://sabooksonline.co.za/img/social.png" width="50px"></a>
            <div class="right-side text-end">
              <a class="" href="../includes/logout">Logout</a>
              <a class="menubar ml30" href="#menu"><img src="images/mobile-dark-nav-icon.svg" alt=""></a>
            </div>
          </div>
        </div>
        <div class="posr"><div class="mobile_menu_close_btn"><span class="far fa-times"></span></div></div>
      </div>
    </div>
    <!-- /.mobile-menu -->
    <nav id="menu" class="">
      <ul>
        <li><a href="https://sabooksonline.co.za/">Back to main website</a> </li>
       
        <!-- Only for Mobile View -->
      </ul>
    </nav>
  </div>