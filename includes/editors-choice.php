
                <?php
                                    //DATABASE CONNECTIONS SCRIPT
                                    include 'database_connections/sabooks.php';
                                    $sql = "SELECT * FROM posts WHERE STATUS = 'active' ORDER BY RAND() LIMIT 20;";
                                    //$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
                                    $result = mysqli_query($conn, $sql);
                                    $result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($result) == false){
                                        }else{
                                        while($row = mysqli_fetch_assoc($result)) {

                                            $username = ucwords(substr($row['PUBLISHER'], '0', '20'));

                                            echo ' <div class="item">
                                                        <div class="strip">
                                                            <figure>
                                                                <span class="ribbon off">'.$row['CATEGORY'].'</span>
                                                                <img src="cms-data/book-covers/'.$row['COVER'].'" class="owl-lazy" alt="" width="460" height="310">
                                                                <a href="creator?q='.ucfirst($row['USERID']).'" class="strip_info">
                                                                
                                                                </a>
                                                            </figure>
                                                            <div>
                                                                <a href="creator?q='.ucfirst($row['USERID']).'" class="text-dark">'.ucwords($row['PUBLISHER']).' <small class="icon_check_alt text-success" style="font-size:12px"></small></a>
                                                                <p class="mt-1"><a href="book?q='.ucfirst($row['CONTENTID']).'"><i class="icon_link" style="font-size:10px"></i> '.substr($row['TITLE'], 0, 20).'</a></p>
                                                            </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                ?>
