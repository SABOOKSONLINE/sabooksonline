	   
							<?php 
								if (!isset($_GET['userkey'])) {
									
									echo "<h5>You are not permited to be on this page!</h5>";
									
								}else {
									
									$userkey = $_GET['userkey'];
									$subject = $_GET['subject'];
									$message = $_GET['message'];

									$messageid = substr(uniqid(),'0', '12').time();

									//TIME VARIABLE
        							$current_time = date('l jS \of F Y');
									
									include '../../database_connections/sabooks.php';
									
									$sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$userkey';";
									
									$result = mysqli_query($conn, $sql);
									
									
									if(mysqli_num_rows($result) == false){
										
										echo "<h5>Mmh something went completely wrong!</h5>";
										
									} else {
									
										while ($row = mysqli_fetch_assoc($result)) {
											$reg_name = $row['ADMIN_NAME'];
											$reg_email = $row['ADMIN_EMAIL'];
										}

										          

										$message = strip_tags($message);

										$sql = "INSERT INTO messages ( MESSAGE, USERKEY, MESSAGEID, SUBJECT, DAY) VALUES 																	('$message','$userkey','$messageid', '$subject','$current_time')";
										
											$button_link = "mailto:admin@sabooksonline.co.za";
											$link_text = "Reply To Message";
    
											  include '../templates/emails/multiuse.php';
										
											if(!mysqli_query($conn, $sql)){
												
												echo $message;
												
											}else{
												header("Location: ../../../pending-users.php?message=success");
									
												//echo $message2;

												mail($reg_email,$subject,$message2,$headers);

											}
									}
									//excluding image and password
										
									
								}
							?>