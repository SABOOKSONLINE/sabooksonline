<?php 
                
	if(!isset($_SESSION['ADMIN_ID'])){
		echo '<script>window.location.href="login.php";</script>';
	}
				
?>



<aside class="col-lg-3" id="sidebar_fixed">
					
					<a href="#0" class="open_filters btn_filters"><i class="icon_adjust-vert"></i><span>Filters</span></a>
				
					<div class="filter_col">
						<div class="inner_bt clearfix">Filters<a href="#" class="open_filters"><i class="icon_close"></i></a></div>
						<div class="filter_type">
							<h4><a href="#filter_1" data-bs-toggle="collapse" class="opened">Dashboard</a></h4>
							<div class="collapse show" id="filter_1">
								<ul class="text-dark side-links">
								    <li id="tc"><a href="dashboard-2">Overview Dashboard</a> <i class="arrow_right"></i></li><hr>
								    
										
									<?php 
									
									if($_SESSION['ADMIN_TYPE'] = 'service-provider' OR $_SESSION['ADMIN_TYPE'] ='book-store' ){ 
										
										echo '<li id="pop"><a href="dashboard-photos"> My Photos<span class="badge rounded-pill bg-dark text-white"></span></a> <i class="arrow_right"></i></li>';

									} else if($_SESSION['ADMIN_TYPE'] = 'author' OR $_SESSION['ADMIN_TYPE'] ='publisher' ) { 

										echo '<li id="pop"><a href="dashboard-book-listing"> My Book Listings<span class="badge rounded-pill bg-dark text-white"></span></a> <i class="arrow_right"></i></li>';
									
									} else if($_SESSION['ADMIN_TYPE'] = 'standard') { 

									}
									
									?>
									
									<hr>
								    <li id="pp"><a href="dashboard-invoices">My Invoices</a> <i class="arrow_right"></i></li><hr>
								    <li id="cp" class="d-none"><a href="dashboard-analytics">Data Analytics</a> <i class="arrow_right"></i></li><hr>
								    <li id="tp"><a href="dashboard-reviews">Reviews</a> <i class="arrow_right"></i></li><hr>
								    <li id="rp"><a href="dashboard-profile">My Profile Settings</a> <i class="arrow_right"></i></li>
								</ul>
							</div>
						</div>
						<!-- /filter_type -->
						
						<p><a href="dashboard-add-book" class="btn_1 full-width">Add Book Listing <small class="icon_plus_alt2"></small></a></p>

						<!--<p><a href="#0" class="btn_1 outline full-width">Password Reset</a></p>-->
						<!--<p><a href="#0" class="btn_1 outline full-width">Open A Ticket</a></p>-->

					</div>
				</aside>