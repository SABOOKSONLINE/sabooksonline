
<?php
                            $servername = "localhost";
                            /*$username = "root";
                            $password = "";
                            $dbh = "sabooksonline";*/
                            
                            $username = "sabooks_library";
                            $password = "1m0g7mR3$";
                            $dbh = "Sibusisomanqa_update_2";
                            
                            /*$username = "root";
                            $password = "";
                            $dbh = "sabooksonline";*/
                            
                            $dbh_2 = "Sibusisomanqa_website_plesk";
                            
                            // Create connection
                            $mysqli = new mysqli($servername, $username, $password, $dbh_2);
                            // Create connection
                            $conn = new mysqli($servername, $username, $password, $dbh);
                            
                            // Check connection
                            if ($conn->connect_error) {
                              die("Connection failed: " . $conn->connect_error);
                            }
                            
                            // Check connection
                            if ($mysqli->connect_error) {
                                die("Connection failed: " . $mysqli->connect_error);
                              }
                            
                            $sql = "SELECT * FROM users WHERE ADMIN_TYPE = 'service-provider' $load";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result) == false){
                            }else{
                            while($row = mysqli_fetch_assoc($result)) {
								 echo ' <div class="card info-div">
                                 <div class="infos">
                                     <div class="image" id="image"><img src="cms-data/profile-images/'.$row['ADMIN_PROFILE_IMAGE'].'"> 
                                     
                                     <div class="reviews p-0 d-none">
                                                 <ul class="d-flex justify-content-start">
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                 </ul>
                                         </div>
                                     </div>
                                     <div class="info">
                                         <div>
                                            <h5 class="name text-dark p-0 m-0">'.$row['ADMIN_NAME'].'</h5>
                                            <small class="location"><i class="fa fa-map-marker"></i> '.$row['ADMIN_GOOGLE'].'</small>
                                         </div>

                                         <div class="d-flex justify-content-start flex-wrap">
                                                     <span class="badge badge-dark text-white b-services mr-2 text-dark" style="margin: 1%;">'.str_replace(',','</span><span class="badge  badge-dark text-white " style="margin: 1%;">', $row['ADMIN_SERVICES']).'</span>
                                         </div>
        
                                     </div>
                                 </div>
                                 <button class="request" type="button" id="'.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).'_event" style="background-color: #e54750;color: #fff;">
                                        More Details <i class="fa fa-arrow-right"></i>
                                     </button>
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
                             
                             
                             <div class="card info-tab" id="'.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).'_tab">
                             
                            <div class="scrollable"><div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                
                                    ';
                                    $userkey = $row['ADMIN_USERKEY'];

                                    $sqls = "SELECT * FROM provider_images WHERE PROVIDER_KEY = '$userkey'";
                                
                                    $results = mysqli_query($conn, $sqls);
                                        if(mysqli_num_rows($results) == false){

                                        }else{
                                        while($rows = mysqli_fetch_assoc($results)) {

                                            echo '<div class="carousel-item active" id="active">
                                                    <img class="d-block w-100" src="cms-data/user-images/'.$rows['PROVIDER_IMAGE'].'" alt="Second slide">
                                                </div>';
                                            }
                                    }

                                    echo '
                                    
                                    
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><img src="cms-data/profile-images/'.$row['ADMIN_PROFILE_IMAGE'].'" class="provider-image"> '.$row['ADMIN_NAME'].'</h5>

                                <p class="location"><i class="fa fa-map-marker" style="color: #e54750"></i> '.$row['ADMIN_GOOGLE'].'</p>

                                <div class="reviews d-flex justify-content-between">
                                                <p>Based on 6 Reviews</p>
                                                 <ul class="d-flex justify-content-start">
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                 </ul>
                                         </div>

                                <p class="card-text pt-3"><small>'.$row['ADMIN_BIO'].'</small></p>
                            </div>
                            <ul class="card-body list-group ml-3">
                                <li class="list-group-item">'.str_replace(',','</li><li class="list-group-item">', $row['ADMIN_SERVICES']).'</li>
                            </ul>
                            <div class="card-body">
                            <a href="mailto:'.$row['ADMIN_EMAIL'].'"><button class="request" type="button" style="background-color: #e54750;color: #fff;">
                                '.$row['ADMIN_EMAIL'].' <i class="fa fa-envelope"></i>
                            </button></a>

                            <a href="tel:'.$row['ADMIN_NUMBER'].'"><button class="request" style="background-color: #e54750;color: #fff;">
                            '.$row['ADMIN_NUMBER'].' <i class="fa fa-phone"></i>
                            </button></a>
                            </div></div>

                            

                            <div class=""><i class="fa fa-times close-icon"></i></div>
                        </div>';
                                }
                            }
?>


<script>

$(".info-tab").hide();

$(".close-icon").click(function(){
    $(".info-tab").hide(300);
});

<?php
                      
                        $sql = "SELECT * FROM users WHERE ADMIN_TYPE = 'service-provider';";
                        $result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result) == false){
                        }else{
                        while($row = mysqli_fetch_assoc($result)) {
                             echo '
                             $("#'.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).'_event").click(function(){
                                $(".info-tab").hide();
                                  $("#'.str_replace(' ', '_', strtolower($row['ADMIN_NAME'])).'_tab").toggle(300);
                              });';

                        }
                    }

                        ?>


$('.request').click(function(){
    $(".carousel-inner:first-child").addClass("active");
})

</script>
