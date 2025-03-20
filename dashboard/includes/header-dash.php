<div class="dashboard__sidebar d-none d-lg-block">
    
        <div class="dashboard_sidebar_list">
        
          <div class="sidebar_list_item" style="border: none !important;">
            <button class="ud-btn btn-success w-100" id="startTourButton">Start Tour Guide <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" style="border: none !important;"></i></button>
          </div>
          <hr>

          <p class="fz15 fw400 ff-heading pl30">Start Here <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" title="In this section you can Add books, Events, Services and manage your reviews."></i></p> 

          <div class="sidebar_list_item">
            <a href="index" class="items-center dashboard"><i class="flaticon-home mr15"></i>Dashboard</a>
          </div>
          <div class="sidebar_list_item">
            <a href="listings" class="items-center listings booklistings" id="element1"><i class="flaticon-document mr15"></i>Book Listings</a>
          </div>
          
         
          <div class="sidebar_list_item ">
            <a href="events" class="items-center events" id="element2"><i class="flaticon-content mr15"></i>Manage Events</a>
          </div>
  
          <div class="sidebar_list_item ">   
            <a href="services" class="items-center services" id="element3"><i class="flaticon-web mr15"></i>Manage Services</a>
          </div>

          <div class="sidebar_list_item ">
            <a href="reviews" class="items-center reviews" id="element4"><i class="flaticon-review-1 mr15"></i>Reviews</a>
          </div>

          <?php if($_SESSION['ADMIN_SUBSCRIPTION'] == 'Deluxe'){echo '<div class="sidebar_list_item ">
            <a href="bookstore" class="items-center store" id="element11"><i class="flaticon-receipt mr15"></i>Book Store</a>
          </div>';}?>

          
        
         
          <p class="fz15 fw400 ff-heading pl30 mt30">Organize and Manage <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" title="In this section you can Create Your website, View customers and track your orders."></i></p>
          <div class="sidebar_list_item ">
            <a href="websites" class="items-center web" id="element5"><i class="flaticon-presentation mr15"></i>Manage Website</a>
          </div>

          <div class="sidebar_list_item ">
            <a href="customers" class="items-center customers" id="element6"><i class="flaticon-briefcase mr15"></i>Manage Customers</a>
          </div>
          
          <div class="sidebar_list_item ">
            <a href="orders" class="items-center orders"  id="element7"><i class="flaticon-dollar mr15"></i>Manage Orders</a>
          </div>
          

          <p class="fz15 fw400 ff-heading pl30 mt30">My Account <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" title="In this section you can Edit your profile & update your subscription."></i></p>
          <div class="sidebar_list_item">
            <a href="service-plan" class="items-center subscriptions" id="element8"><i class="flaticon-receipt mr15"></i>Account Billing</a>
          </div>

          <div class="sidebar_list_item">
            <a href="plan" class="items-center subscriptions-1"  id="element9"><i class="flaticon-receipt mr15"></i>Subscription Plans</a>
          </div>

          <div class="sidebar_list_item ">
            <a href="profile" class="items-center profile" id="element10"><i class="flaticon-photo mr15"></i>My Profile</a>
          </div>
          <div class="sidebar_list_item " id="logout">
            <a href="../includes/logout" class="items-center"><i class="flaticon-logout mr15"></i>Logout</a>
          </div> 

        </div>
      </div>


