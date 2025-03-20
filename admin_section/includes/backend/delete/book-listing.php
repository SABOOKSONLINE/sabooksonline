
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

                                        $message = "Your book listing has been <b>Deleted</b> and is now <b>Not public</b>. Possible reason why this has been made will be communicated to you.";
                                        $message .= "<br><br><b>Title:</b> ".$title;
                                        $message .= "<br><b>Category:</b> ".$category;
                                        $message .= "<br><b>Uploaded:</b> ".$dateposted;
                                       

                                        //$button_link = "https://my.sabooksonline.co.za/books/".$contentid;
                                        $button_link = "https://dashboard.sabooksonline.co.za/";
                                        $link_text = "Go To Dashboard";
                    
                                        include '../templates/emails/multiuse.php';
											
										$subject = "Book Listing Removal";

                                        $sql = "DELETE FROM posts WHERE CONTENTID ='$contentid';";
											
											if(!mysqli_query($conn, $sql)){
												
												header("Location: ../../../book-listing.php?status1=failed");
												
											}else{
												
												
                                                $target = "../../../books/".strtolower($contentid);

                                                

                                                function delete_files($target) {
                                                    if(is_dir($target)){
                                                        $files = glob( $target . '*', GLOB_MARK ); 
                                                        foreach( $files as $file ){
                                                            delete_files( $file );      
                                                        }
                                                        rmdir( $target );
                                                    } elseif(is_file($target)) {
                                                        unlink( $target ); 
                                                    }
                                                }
												
												delete_files($target);
												
                                                    header("Location: ../../../book-listing.php?status1=success");
									
                                                    //echo $target;
    
                                                   mail($reg_email,$subject,$message2,$headers);

												
											}

                                        }
									}



?>
