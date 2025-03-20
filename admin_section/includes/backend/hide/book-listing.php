<?php
                   
             //DATABASE CONNECTIONS SCRIPT
            include '../../../includes/database_connections/sabooks.php';

            //VARIABLES DECLARED

                                     $contentid = mysqli_real_escape_string($conn, $_GET['contentid']);

                                    $page = $_GET['page'];

                                    $veri_link = $contentid.time();

									//TIME VARIABLE
        							$current_time = date('l jS \of F Y');
									
									include '../../database_connections/sabooks.php';
									
									$sql = "SELECT * FROM posts WHERE CONTENTID = '$contentid';";
									
									$result = mysqli_query($conn, $sql);
									
									
									if(mysqli_num_rows($result) == false){
										
										echo "<h5>Mmh something went completely wrong!</h5>";
										
									} else {
									
										while ($row = mysqli_fetch_assoc($result)) {
											$publisher = $row['PUBLISHER'];
											$cover = $row['COVER'];
											$category = $row['CATEGORY'];
											$title = $row['TITLE'];
											$dateposted = $row['DATEPOSTED'];
											$userid = $row['USERID'];
										}

                                        $sqlS = "SELECT * FROM users WHERE ADMIN_USERKEY = '$userid';";
									
									    $resultS = mysqli_query($conn, $sqlS);

                                        if(mysqli_num_rows($resultS) == false){

                                            header("Location: ../../../book-listing.php?status1=failed&reason=user not found");
                                            
                                        } else {

                                            while ($rows = mysqli_fetch_assoc($resultS)) {
                                                $reg_name = $rows['ADMIN_NAME'];
                                                $reg_email = $rows['ADMIN_EMAIL'];
                                            }

                                        $message = "Your book listing has been <b>Hidden</b> and is now <b>Not</b> public. Possible reason why this has been made will be communicated to you.";
                                        $message .= "<br><br><b>Title:</b> ".$title;
                                        $message .= "<br><b>Category:</b> ".$category;
                                        $message .= "<br><b>Uploaded:</b> ".$dateposted;
                                       

                                        //$button_link = "https://my.sabooksonline.co.za/books/".$contentid;
                                        $button_link = "https://dashboard.sabooksonline.co.za/";
                                        $link_text = "Go To Dashboard";
											
										$subject = "Book Listing Hidden";
                    
                                        include '../templates/emails/multiuse.php';

                                        $sql = "UPDATE posts SET STATUS ='pending' WHERE CONTENTID='$contentid'";
											
											if(!mysqli_query($conn, $sql)){
												
												header("Location: ../../../book-listing.php?status1=failed");
												
											}else{
												header("Location: ../../../book-listing.php?status1=success");
									
												//echo $message2;

												mail($reg_email,$subject,$message2,$headers);

											}

                                        }
									}


?>

?>