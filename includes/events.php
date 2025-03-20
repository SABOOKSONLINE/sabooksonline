
<?php
                            //DATABASE CONNECTIONS SCRIPT
                            include 'database_connections/sabooks.php';
                            $sql = "SELECT * FROM events WHERE STATUS = 'Active';";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result) == false){
                            }else{
                            while($row = mysqli_fetch_assoc($result)) {
								 echo ' <div class="card info-div">
                                 <div class="infos">
                                     <div class="image" id="image"><img src="https://my.sabooksonline.co.za/cms-data/event-covers/'.$row['COVER'].'"> 
                                     
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
                                            <h5 class="name text-dark p-0 m-0">'.$row['TITLE'].'</h5>
                                            <small class="location"><i class="fa fa-map-marker"></i> '.$row['VENUE'].'</small>
                                         </div>

                                         <div class="d-flex justify-content-start flex-wrap">
                                                     <span class="badge badge-dark text-white b-services mr-2 text-dark" style="margin: 1%;">'.$row['EVENTDATE'].'</span>

                                                     <span class="badge badge-dark text-white b-services mr-2 text-dark" style="margin: 1%;">'.$row['EVENTTIME'].'</span>
                                         </div>
        
                                     </div>
                                 </div>
                                 <button class="request" type="button" id="'.str_replace(' ', '_', strtolower($row['Ç'])).'_event" style="background-color: #e54750;color: #fff;">
                                        More Details <i class="fa fa-arrow-right"></i>
                                     </button>
                             </div>

                             <style>
                                #map img[src="https://my.sabooksonline.co.za/cms-data/event-covers/'.$row['COVER'].'"]{
                                    border: 2px solid rgba(229, 71, 80, .9) !important;
                                    width: 40px !important;
                                    height: 40px !important;
                                    border-radius: 50% !important;
                                    background: #fff !important;
                                }
                             </style>
                             
                             
                             <div class="card info-tab" id="'.str_replace(' ', '_', strtolower($row['TITLE'])).'_tab">
                             
                            <div class="scrollable"><div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                <div class="carousel-item active" id="active">
                                                    <img class="d-block w-100" src="https://my.sabooksonline.co.za/cms-data/event-covers/'.$rows['COVER'].'" alt="Second slide">
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
                                <h5 class="card-title"><img src="https://my.sabooksonline.co.za/cms-data/event-covers/'.$row['COVER'].'" class="provider-image"> '.$row['ADMIN_NAME'].'</h5>

                                <p class="location"><i class="fa fa-map-marker" style="color: #e54750"></i> '.$row['VENUE'].'</p>

                                <hr>

                                <div class="reviews d-flex justify-content-between d-none">
                                                <p>Based on 6 Reviews</p>
                                                 <ul class="d-flex justify-content-start">
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                    <i class="fa fa-star text-dark"></i>
                                                 </ul>
                                         </div>

                                <p class="card-text pt-3"><small>'.$row['DESCRIPTION'].'</small></p>
                            </div>
                           
                            <div class="card-body">
                            <a href="register/rsvp.php?event='.$row['TITLE'].'"><button class="request" type="button" style="background-color: #e54750;color: #fff;">
                                '.$row['ADMIN_EMAIL'].' <i class="fa fa-envelope"></i>
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
                      
                        $sql = "SELECT * FROM events WHERE CURRENT = 'Active';";
                        $result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result) == false){
                        }else{
                        while($row = mysqli_fetch_assoc($result)) {
                             echo '
                             $("#'.str_replace(' ', '_', strtolower($row['TITLE'])).'_event").click(function(){
                                $(".info-tab").hide();
                                  $("#'.str_replace(' ', '_', strtolower($row['TITLE'])).'_tab").toggle(300);
                              });';

                        }
                    }

                        ?>


$('.request').click(function(){
    $(".carousel-inner:first-child").addClass("active");
})

</script>
