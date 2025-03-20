	   
							<?php 
								if (!isset($_GET['contentid'])) {
									
									echo "<h5>You are not permited to be on this page!</h5>";
									
								}else {
									
									$contentid = $_GET['contentid'];
                                    $veri_link = $contentid.time();

									//TIME VARIABLE
        							$current_time = date('l jS \of F Y');
									
									include '../../database_connections/sabooks.php';
									
									$sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$contentid';";
									
									$result = mysqli_query($conn, $sql);
									
									
									if(mysqli_num_rows($result) == false){
										
										echo "<h5>Mmh something went completely wrong!</h5>";
										
									} else {
									
										while ($row = mysqli_fetch_assoc($result)) {
											$reg_name = $row['ADMIN_NAME'];
											$reg_email = $row['ADMIN_EMAIL'];
										}
     

                                        $message = "Thank you for taking the time to apply for membership. Please verify your email by clicking the link below. Your account will never be approved unless verified.";
                    
                                        $button_link = "https://my.sabooksonline.co.za/verify.php?verifyid=".$veri_link;
                                        $link_text = "Confirm Your Account";

										$subject = "Account Verification Request";
                    
                                        include '../templates/emails/multiuse.php';

                                        $sql = "UPDATE users SET RESETLINK = '$veri_link' WHERE ADMIN_USERKEY = '$contentid'";
											
											if(!mysqli_query($conn, $sql)){
												
												header("Location: ../../../pending-users.php?confirmation=failed");
												
											}else{
												header("Location: ../../../pending-users.php?confirmation=success");
									
												//echo $message2;

												mail($reg_email,$subject,$message2,$headers);

											}
									}
									//excluding image and password
										
									
								}
							?>