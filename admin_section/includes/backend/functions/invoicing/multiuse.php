<?php


        $invoice_number = substr($contentid.time(), 0, 5);

        $current_time = date('l jS \of F Y');

        $payment_status = "Unpaid";

        $payment_method = "Online";

     
          
          $sql = "INSERT INTO invoices (INVOICE_NUMBER, INVOICE_DATE, INVOICE_DUE, INVOICE_USER, INVOICE_SUBSCRIPTION, INVOICE_AMOUNT, INVOICE_METHOD, INVOICE_STATUS, INVOICE_DATEFOR) VALUES ('$invoice_number','$current_time','$current_time','$contentid','$sub_type','$amount','$payment_method','$payment_status','$current_time');";
          
          mysqli_query($conn, $sql);

      

?>