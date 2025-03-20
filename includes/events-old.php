
<?php
                            //DATABASE CONNECTIONS SCRIPT
                            include 'database_connections/sabooks.php';
                            $sql = "SELECT * FROM events WHERE CURRENT = 'Active';";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result) == false){
                            }else{
                            while($row = mysqli_fetch_assoc($result)) {
								 echo ' <div class="card info-div">
                                 <div class="infos">
                                     <div class="image" id="image"><img src="cms-data/event-covers/'.$row['COVER'].'"> 
                                     </div>
                                     <div class="info">
                                         <div>
                                            <h5 class="name text-dark">'.$row['TITLE'].'</h5>
                                            <p class="location"><i class="fa fa-map-marker"></i> '.$row['VENUE'].'</p>
                                            <div class="reviews d-flex justify-content-between">
                                                <p><b>Based on 6 Reviews</b></p>
                                                 <ul class="d-flex justify-content-start">
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                 </ul>
                                         </div>
                                         </div>
                                         <div class="stats pt-2">
                                                 <p class="flex flex-col">
                                                     <span class="state-value">
                                                     '.str_replace(',', '<i class="fa fa-circle"></i>', $row['VENUE']).'
                                                     </span>
                                                 </p>
                                                 
                                         </div>
        
                                     </div>
                                 </div>
                                 <button class="request" type="button" id="'.str_replace(' ', '_', strtolower($row['TITLE'])).'_event">
                                        Provider Details
                                     </button>
                             </div>

                             <style>
                                #map img[src="cms-data/event-covers/'.$row['COVER'].'"]{
                                    border: 2px solid #e54750 !important;
                                    width: 40px !important;
                                    height: 40px !important;
                                    border-radius: 50% !important;
                                }
                             </style>
                             
                             
                             <div class="card info-tab" id="'.str_replace(' ', '_', strtolower($row['TITLE'])).'_tab">
                             
                            <div class="scrollable"><div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                
                                  <div class="carousel-item active" id="active">
                                    <img class="d-block w-100" src="cms-data/user-images/'.$rows['COVER'].'" alt="Second slide">
                                  </div>
                                    
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
                                <h5 class="card-title"><img src="cms-data/event-covers/'.$row['COVER'].'" class="provider-image"> '.$row['TITLE'].'</h5>

                                <p class="location"><i class="fa fa-map-marker"></i> '.$row['VENUE'].'</p>

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

                                <p class="card-text pt-3">'.$row['DESCRIPTION'].'</p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Editing</li>
                                <li class="list-group-item">Proof reading</li>
                                <li class="list-group-item">Printing</li>
                            </ul>
                            <div class="card-body">
                                <a href="#" class="card-link">011 568 4339</a>
                                <a href="#" class="card-link">Another link</a>
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
