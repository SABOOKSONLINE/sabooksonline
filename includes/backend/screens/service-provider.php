
<?php
                            //DATABASE CONNECTIONS SCRIPT
                            include '../../database_connections/sabooks.php';

                            

							if(empty($_GET['sort'])){
								$sort = '';
							}else {
								$sort = str_replace(' ', '-', $_GET['sort']);

                                $sort = " AND ADMIN_PROVINCE = '$sort'";
							}


							$category_keywords = "";

							if(empty($_GET['key'])){
								$category_keywords = '';
							} else {
								$keywords = $_GET['key'];

                                $keywords = str_replace(',', '|', $keywords);
								$keywords = str_replace('-', ' ', $keywords);
								$keywords = str_replace('and', '&', $keywords);
								$keywords = str_replace('_', ',', $keywords);

								//$keywords = str_replace('-','|', $keywords);

								$category_keywords = " AND ADMIN_SERVICES REGEXP '$keywords'";

							}


							
                            $sql = "SELECT * FROM users WHERE ADMIN_SERVICES $category_keywords $sort  AND USER_STATUS = 'Verified';";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result) == false){

                                echo '<div class="alert alert-warning">No service providers with <b><i>'.str_replace('|', ',',$keywords).'</i></b> from where not found.</div>';

                            }else{

                                echo '<hr>';
                            while($row = mysqli_fetch_assoc($result)) {
								 echo ' <div class="card info-div col-lg-4 col-sm-12">
                                 <div class="infos">
                                     <div class="image" id="image"><img src="cms-data/profile-images/'.$row['ADMIN_PROFILE_IMAGE'].'"> 
                                     </div>
                                     <div class="info">
                                         <div>
                                            <h5 class="name text-dark p-0 m-0">'.$row['ADMIN_NAME'].'</h5>
                                            <small class="location"><i class="icon_pin_alt"></i> '.$row['ADMIN_GOOGLE'].'</small>
                                         </div>

                                         <div class="d-flex justify-content-start flex-wrap">
                                                     <span class="badge badge-dark text-white b-services mr-2 text-dark" style="margin: 1%;background-color: #e54750 !important;">'.str_replace(',','</span><span class="badge badge-dark text-white " style="margin: 1%;background-color: #444 !important;">', $row['ADMIN_SERVICES']).'</span>
                                         </div>
        
                                     </div>
                                 </div>
                                 <a href="provider.php?provider='.str_replace(' ','-', strtolower($row['ADMIN_NAME'])).'"><button class="request" type="button" id="'.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).'_event" style="background-color: #e54750;color: #fff;">
                                        More Details <i class="fa fa-arrow-right"></i>
                                     </button></a>
                             </div>

                             <style>
                                #map img[src="cms-data/profile-images/'.$row['ADMIN_PROFILE_IMAGE'].'"]{
                                    border: 2px solid rgba(229, 71, 80, .9) !important;
                                    width: 40px !important;
                                    height: 40px !important;
                                    border-radius: 50% !important;
                                    background: #fff !important;
                                }
                             </style>
                             
                             ';
                                }
                            }
                            ?>
						