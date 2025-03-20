
<?php
                            //DATABASE CONNECTIONS SCRIPT
                            include '../../database_connections/sabooks.php';

                            

							if(empty($_GET['sort'])){
								$sort = '';
							}else {
								$sort = str_replace(' ', '-', $_GET['sort']);

                                $sort = "AND PROVINCE = '$sort'";
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

								$category_keywords = " AND TITLE REGEXP '$keywords'";

							}


							
                            $sql = "SELECT * FROM events WHERE STATUS = 'Active' AND DURATION > 0 $sort ORDER BY ID DESC";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result) == false){

                                echo '<div class="alert alert-warning">There are Events In <b>this province</b> province.</div>';

                            }else{

                                echo '<hr>';
                            while($row = mysqli_fetch_assoc($result)) {
								 echo ' <div class="card info-div col-lg-5 col-md-12 col-sm-12 m-2">
                                 <div class="image" id="image" style="width: 100% !important;height: 200px !important;"><img src="cms-data/event-covers/'.$row['COVER'].'"> 
                                 </div>
                                 <div class="infos pt-3">
                                    
                                     <div class="info">
                                         <div>
                                         <h5 class="name text-dark p-0 m-0">'.$row['TITLE'].'</h5>

                                            <small class="location"><i class="icon_pin_alt"></i> '.$row['VENUE'].'</small>

                                         </div>

                                         <div class="d-flex justify-content-start flex-wrap">
                                            <small class="location"><i class="icon_clock_alt"></i> '.$row['EVENTTIME'].'</small>
                                            <small class="location" style="margin-left: 4%;"><i class="icon_calendar"></i> '.$row['EVENTDATE'].'</small>
                                         </div>
        
                                     </div>

                                     
                                 </div>

                                 <!--<p>'.strip_tags(substr($row['DESCRIPTION'], 0, 100)).'...</p>-->
                                 <a href="event-details?event='.str_replace(' ','-', strtolower($row['ID'])).'"><button class="request" type="button" id="'.str_replace(' ', '_', strtolower($row['TITLE'])).'_event" style="background-color: #e54750;color: #fff;">
                                        Event Details<i class="fa fa-arrow-right"></i>
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
						