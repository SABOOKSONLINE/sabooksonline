<?php 

//Set the cookie domain to ".sabooksonline.co.za" to make it available to all subdomains
$cookieDomain = ".sabooksonline.co.za";
session_set_cookie_params(0, '/', $cookieDomain);

// if(!isset($_SESSION['ADMIN_USERKEY'])){

session_start();

// // for testung - using ujpress
// $_SESSION['ADMIN_ID'] = '388';
// $_SESSION['ADMIN_SUBSCRIPTION'] = 'Deluxe';
// $_SESSION['ADMIN_NAME'] = 'UJ Press';
// $_SESSION['ADMIN_EMAIL'] = 'wikusvz@uj.ac.za';
// // $_SESSION['ADMIN_NUMBER'] = $row['ADMIN_NUMBER'];
// // $_SESSION['ADMIN_WEBSITE'] = $row['ADMIN_WEBSITE'];
// // $_SESSION['ADMIN_BIO'] = $row['ADMIN_BIO'];
// // $_SESSION['ADMIN_TYPE'] = $row['ADMIN_TYPE'];
// // $_SESSION['ADMIN_FACEBOOK'] = $row['ADMIN_FACEBOOK'];
// // $_SESSION['ADMIN_TWITTER'] = $row['ADMIN_TWITTER'];
// // $_SESSION['ADMIN_LINKEDIN'] = $row['ADMIN_LINKEDIN'];
// // $_SESSION['ADMIN_GOOGLE'] = $row['ADMIN_GOOGLE'];
// // $_SESSION['ADMIN_INSTAGRAM'] = $row['ADMIN_INSTAGRAM'];
// // $_SESSION['ADMIN_CUSTOMER_PLESK'] = $row['ADMIN_PINTEREST'];
// // $_SESSION['ADMIN_PASSWORD'] = $row['ADMIN_PASSWORD'];
// // $_SESSION['ADMIN_DATE'] = $row['ADMIN_DATE'];
// // $_SESSION['ADMIN_VERIFICATION_LINK'] = $row['ADMIN_VERIFICATION_LINK'];
// $_SESSION['ADMIN_PROFILE_IMAGE'] = '62309008e164734976862309008e.png';
// $_SESSION['ADMIN_USERKEY'] = '62309008e164734976862309008e';
// // $_SESSION['ADMIN_USER_STATUS'] = $row['ADMIN_USER_STATUS'];
// // $_SESSION['ADMIN_SERVICES'] = $row['ADMIN_SERVICES'];
// // $_SESSION['ADMIN_IMAGE01'] = $row['ADMIN_IMAGE01'];
// // $_SESSION['ADMIN_IMAGE02'] = $row['ADMIN_IMAGE02'];
// // $_SESSION['ADMIN_IMAGE03'] = $row['ADMIN_IMAGE03'];
// // $_SESSION['ADMIN_IMAGE04'] = $row['ADMIN_IMAGE04'];
// }

// ?>

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
          <div class="col-6 col-lg-auto">
            <div class="text-center text-lg-end header_right_widgets">
              <ul class="dashboard_dd_menu_list d-flex align-items-center justify-content-center justify-content-sm-end mb-0 p-0">
                <li class="d-none d-sm-block d-none">
                  <a class="text-center mr5 text-thm2 dropdown-toggle fz20" type="button" data-bs-toggle="dropdown" href="#"><span class="flaticon-notification"></span></a>
                  <div class="dropdown-menu d-none">
                    <div class="dboard_notific_dd px30 pt10 pb15">
                      <div class="notif_list d-flex align-items-center bdrb1 pb15 mb10">
                        <img src="images/resource/notif-1.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">Your resume</p>
                          <p class="text mb-0">updated!</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-center bdrb1 pb15 mb10">
                        <img src="images/resource/notif-2.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">You changed</p>
                          <p class="text mb-0">password</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-center bdrb1 pb15 mb10">
                        <img src="images/resource/notif-3.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">Your account has been</p>
                          <p class="text mb-0">created successfully</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-center bdrb1 pb15 mb10">
                        <img src="images/resource/notif-4.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">You applied for a job </p>
                          <p class="text mb-0">Front-end Developer</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-center">
                        <img src="images/resource/notif-5.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">Your course uploaded</p>
                          <p class="text mb-0">successfully</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>


                <li class="d-none d-sm-bloc">
                  <a class="text-center mr5 text-thm2 dropdown-toggle fz20" type="button" data-bs-toggle="dropdown" href="#"><span class="flaticon-mail"></span></a>
                  <div class="dropdown-menu">
                    <div class="dboard_notific_dd px30 pt20 pb15">
                      <div class="notif_list d-flex align-items-start bdrb1 pb25 mb10">
                        <img class="img-2" src="images/testimonials/testi-1.png" alt="">
                        <div class="details ml15">
                          <p class="dark-color fw500 mb-2">Ali Tufan</p>
                          <p class="text mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing.</p>
                          <p class="mb-0 text-thm">4 hours ago</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-start mb25">
                        <img class="img-2" src="images/testimonials/testi-2.png" alt="">
                        <div class="details ml15">
                          <p class="dark-color fw500 mb-2">Ali Tufan</p>
                          <p class="text mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing.</p>
                          <p class="mb-0 text-thm">4 hours ago</p>
                        </div>
                      </div>
                      <div class="d-grid">
                        <a href="message.html" class="ud-btn btn-thm w-100">View All Messages<i class="fal fa-arrow-right-long"></i></a>
                      </div>
                    </div>
                  </div>
                </li>


                <li class="d-none d-sm-bloc d-none">
                  <a class="text-center mr5 text-thm2 dropdown-toggle fz20" type="button" data-bs-toggle="dropdown" href="#"><span class="flaticon-like"></span></a>
                  <div class="dropdown-menu">
                    <div class="dboard_notific_dd px30 pt10 pb15">
                      <div class="notif_list d-flex align-items-center bdrb1 pb15 mb10">
                        <img src="images/resource/notif-1.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">Your resume</p>
                          <p class="text mb-0">updated!</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-center bdrb1 pb15 mb10">
                        <img src="images/resource/notif-2.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">You changed</p>
                          <p class="text mb-0">password</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-center bdrb1 pb15 mb10">
                        <img src="images/resource/notif-3.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">Your account has been</p>
                          <p class="text mb-0">created successfully</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-center bdrb1 pb15 mb10">
                        <img src="images/resource/notif-4.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">You applied for a job </p>
                          <p class="text mb-0">Front-end Developer</p>
                        </div>
                      </div>
                      <div class="notif_list d-flex align-items-center">
                        <img src="images/resource/notif-5.png" alt="">
                        <div class="details ml10">
                          <p class="text mb-0">Your course uploaded</p>
                          <p class="text mb-0">successfully</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>


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