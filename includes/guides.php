<div class="row p-3" style="background: #f3f3f3;border-radius:10px;">
						<!--<?php include 'includes/backend/progress-bar.php';?> -->
                        <h5>How would you like to begin?</h5>

                        <hr>

                        	
									<?php 

                                    $typed = $_SESSION['ADMIN_TYPE'];
									
									if($typed === 'service-provider' OR $_SESSION['ADMIN_TYPE'] === 'book-store' ){ 
										
										echo '<div class="col-lg-3 mb-1"><a href="dashboard-photos" class="btn_1 full-width">Add Photos <small class="icon_plus_alt2"></small></a></div>';

									} else if($typed === 'author' OR $typed === 'publisher' ) { 

										echo '<div class="col-lg-3 mb-1"><a href="dashboard-add-book" class="btn_1 full-width">Add Book <small class="icon_plus_alt2"></small></a></div>';

                                        echo '<div class="col-lg-3 mb-1"><a href="register/update?id=" class="btn_1 full-width"> Add Services <small class="icon_plus_alt2"></small></a></div>';

										echo '<div class="col-lg-3 mb-1"><a href="dashboard-photos" class="btn_1 full-width">Add Photos <small class="icon_plus_alt2"></small></a></div>';
									
									} else { 
                                        echo '<div class="col-lg-3 mb-1"><a href="event" class="btn_1 full-width">Switch Account <small class="icon_plus_alt2"></small></a></div>';
									}
									
									?>

<div class="col-lg-3 mb-1"><a href="dashboard-profile" class="btn_1 outline full-width bg-white">Update Profile</a></div>

                        <div class="col-lg-3 mb-1"><a href="event" class="btn_1 outline full-width bg-white">Add Event</a></div>
                    	
                    	<div class="col-lg-3 mb-1"><a href="dashboard-invoices" class="btn_1 outline full-width bg-white">My Invoices</a></div>
                    	<div class="col-lg-3 mb-1"><a href="dashboard-reviews" class="btn_1 outline full-width bg-white">My Reviews</a></div>
					</div> 
