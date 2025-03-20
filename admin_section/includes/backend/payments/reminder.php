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
									
									$sql = "SELECT * FROM invoices WHERE INVOICE_NUMBER = '$contentid';";
									
									$result = mysqli_query($conn, $sql);
									
									
									if(mysqli_num_rows($result) == false){
										
										echo "<h5>Mmh something went completely wrong!</h5>";
										
									} else {
									
										while ($row = mysqli_fetch_assoc($result)) {

											$number = $row['INVOICE_NUMBER'];
											$date = $row['INVOICE_DATE'];
											$due = $row['INVOICE_DUE'];
											$userkey = $row['INVOICE_USER'];
											$subscription = $row['INVOICE_SUBSCRIPTION'];
											$method = $row['INVOICE_METHOD'];
											$status = $row['INVOICE_STATUS'];
											$datefor = $row['INVOICE_DATEFOR'];
											$amount = $row['INVOICE_AMOUNT'];

										}

                                        $sqlS = "SELECT * FROM users WHERE ADMIN_USERKEY = '$userkey';";
									
									    $resultS = mysqli_query($conn, $sqlS);

                                        if(mysqli_num_rows($resultS) == false){

                                            header("Location: ../../../subscription.php?status1=failed&reason=user not found");
                                            
                                        } else {

                                            while ($rows = mysqli_fetch_assoc($resultS)) {
                                                $reg_name = $rows['ADMIN_NAME'];
                                                $reg_email = $rows['ADMIN_EMAIL'];
                                            }

                                        $message = "Your account is now overdue with R".$amount." for a monthly subscription.";
                                        $message .= "<br><br><b>Invoice No:</b> ".$number;
                                        $message .= "<br><b>Subscription:</b> ".$subscription;
                                        $message .= "<br><b>Payment Method:</b> ".$method;
                                        $message .= "<br><b>Payment Status:</b> ".$status;
                                        $message .= "<br><b>Date Issued:</b> ".$datefor;
                                        $message .= "<br><b>Due Date:</b> ".$due;
                                        $message .= "<br><b>Amount Due:</b> R".$amount;
                                       

                                        //$button_link = "https://my.sabooksonline.co.za/books/".$contentid;
                                        $button_link = "https://dashboard.sabooksonline.co.za/";
                                        $link_text = "Go To Dashboard";

                                        $not_type = "Unpaid";
											
										$subject = "Unpaid invoice reminder for #".$number;
                    
                                        include '../templates/emails/multiuse.php';

                                        $sql = "INSERT INTO notifications ( USERKEY, MESSAGE , TYPE, TIME ) VALUES ( '$userkey','$message' , '$not_type', '$current_time' );";
											
											if(!mysqli_query($conn, $sql)){
												
												header("Location: ../../../subscriptions.php?reminder=failed");
												
											}else{
												header("Location: ../../../subscriptions.php?reminder=success");
									
												//echo $message2;

												mail($reg_email,$subject,$message2,$headers);

											}

                                        }
									}


?>

?>