<?php
// Start the session
    session_start();

  //DATABASE CONNECTIONS SCRIPT
  include '../database_connections/sabooks.php';

  $voucher = mysqli_real_escape_string($conn, $_POST['voucher_number']);
  $invoice = mysqli_real_escape_string($conn, $_POST['invoice_number']);
  $userkey = mysqli_real_escape_string($conn, $_SESSION['ADMIN_USERKEY']);

            $sql = "SELECT * FROM vouchers WHERE VOUCHER = '$voucher' AND STATUS = 'Active';";

            if(!mysqli_num_rows(mysqli_query($conn, $sql))){
              echo "<div class='alert alert-warning'>The voucher code <b>".strtoupper($voucher)."</b> does not exist or has already been used.</div>";
            }else {
                
                $sql = "UPDATE vouchers SET STATUS = 'Used' WHERE VOUCHER ='$voucher'";
            
    
                    if(mysqli_query($conn, $sql)){

                      $sql = "UPDATE invoices SET INVOICE_STATUS = 'Paid' WHERE INVOICE_NUMBER = '$invoice'";

                    $message = "Your invoice has been paid by Voucher code ".$voucher.", You may login to your account to download the updated invoice.";
                    $button_link = "https://sabooksonline.co.za/dashboard-view-invoice?invoice=".$invoice;
                    $link_text = "View Updated Invoice";
                  		  
                    include '../templates/email.php';

                    $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$userkey';";

                    if(!mysqli_num_rows(mysqli_query($conn, $sql))){
                      echo "<center class='alert alert-warning'>Email Not Found!</center>";
                    }else {
                      $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

                      $reg_name = $row['ADMIN_NAME'];
                      $reg_email = $row['ADMIN_EMAIL'];
                    }

                    $subject = 'Invoice paid by Voucher '.$voucher.' for Invoice';

                    mail($reg_email,$subject,$message2,$headers);

                    if(mysqli_query($conn, $sql)){

                      $sqlE = "UPDATE posts SET STATUS = 'active' WHERE CONTENTID = '$invoice'";

                      if(mysqli_query($conn, $sqlE)){
			                  echo '<script>window.location.href="dashboard-view-invoice?invoice='.$invoice.'&status=success";</script>';
                      //echo "<p class='alert alert-success p-2 mb-3' style='border: none !important'>Your password has been changed! Please check your email for confirmation. </p>";
                      }
                        
                    }
                                   
                    }else {
                          echo "<p class='alert alert-warning p-2 mb-3'>Your request could not be processed!</p>";
                    }
                                 
                  }