<?php

        $subs = $title.' - '.$type;

		$sql1 = "INSERT INTO invoices (INVOICE_NUMBER, INVOICE_DATE, INVOICE_DUE, INVOICE_USER, INVOICE_SUBSCRIPTION, INVOICE_AMOUNT, INVOICE_METHOD, INVOICE_STATUS, INVOICE_DATEFOR) VALUES ('$contentid','$current_time','$current_time','$userkey','$subs','$amount','$payment_method','$payment_status','$current_time');";
          
        mysqli_query($conn, $sql1);

?>