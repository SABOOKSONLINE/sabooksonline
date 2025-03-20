<?php


ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

// API SCRIPT FOR FETCHING DATA FROM SABOOKS ONLINE
$fileContent = file_get_contents('../api_key.txt');
include '../api_fetch.php';

for ($i = 0; $i < count($title); $i++):

include '../db.php';
// Tell PayFast that this page is reachable by triggering a header 200
header( 'HTTP/1.0 200 OK' );
flush();

session_start();

$email = $_SESSION['email'];

define( 'SANDBOX_MODE', true );
$pfHost = SANDBOX_MODE ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
// Posted variables from ITN
$pfData = $_POST;

// Strip any slashes in data
foreach( $pfData as $key => $val ) {
    $pfData[$key] = stripslashes( $val );
}

// Convert posted variables to a string
foreach( $pfData as $key => $val ) {
    if( $key !== 'signature' ) {
        $pfParamString .= $key .'='. urlencode( $val ) .'&';
    } else {
        break;
    }
}

$pfParamString = substr( $pfParamString, 0, -1 );

//verify pfValidSignature

function pfValidSignature( $pfData, $pfParamString, $pfPassphrase = null ) {
    // Calculate security signature
    if($pfPassphrase === null) {
        $tempParamString = $pfParamString;
    } else {
        $tempParamString = $pfParamString.'&passphrase='.urlencode( $pfPassphrase );
    }

    $signature = md5( $tempParamString );
    return ( $pfData['signature'] === $signature );
} 

function pfValidIP() {
    // Variable initialization
    $validHosts = array(
        'www.payfast.co.za',
        'sandbox.payfast.co.za',
        'w1w.payfast.co.za',
        'w2w.payfast.co.za',
        );

    $validIps = [];

    foreach( $validHosts as $pfHostname ) {
        $ips = gethostbynamel( $pfHostname );

        if( $ips !== false )
            $validIps = array_merge( $validIps, $ips );
    }

    // Remove duplicates
    $validIps = array_unique( $validIps );
    $referrerIp = gethostbyname(parse_url($_SERVER['HTTP_REFERER'])['host']);
    if( in_array( $referrerIp, $validIps, true ) ) {
        return true;
    }
    return false;
} 

$cartTotal = $pfData['amount_gross'];

function pfValidPaymentData( $cartTotal, $pfData ) {
    return !(abs((float)$cartTotal - (float)$pfData['amount_gross']) > 0.01);
}


function pfValidServerConfirmation( $pfParamString, $pfHost = 'sandbox.payfast.co.za', $pfProxy = null ) {
    // Use cURL (if available)
    if( in_array( 'curl', get_loaded_extensions(), true ) ) {
        // Variable initialization
        $url = 'https://'. $pfHost .'/eng/query/validate';

        // Create default cURL object
        $ch = curl_init();
    
        // Set cURL options - Use curl_setopt for greater PHP compatibility
        // Base settings
        curl_setopt( $ch, CURLOPT_USERAGENT, NULL );  // Set user agent
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );      // Return output as string rather than outputting it
        curl_setopt( $ch, CURLOPT_HEADER, false );             // Don't include header in output
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );
        
        // Standard settings
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $pfParamString );
        if( !empty( $pfProxy ) )
            curl_setopt( $ch, CURLOPT_PROXY, $pfProxy );
    
        // Execute cURL
        $response = curl_exec( $ch );
        curl_close( $ch );
        if ($response === 'VALID') {
            return true;
        }
    }
    return false;
} 


        $myFile = fopen('notify.html', 'wb') or die();


        $check1 = pfValidSignature($pfData, $pfParamString);
        $check1 ? fwrite($myFile , " Signature Valid "): fwrite($myFile , " Signature Failed ");

        $check2 = pfValidIP();
        $check2 ? fwrite($myFile , " IP Valid "): fwrite($myFile , " IP Not Valid ");

        $check3 = pfValidPaymentData($pfData['amount_gross'], $pfData);
        $check3 ? fwrite($myFile , "Payment Valid"): fwrite($myFile , " Payment Total Invalid ");

        $check4 = pfValidServerConfirmation($pfParamString, $pfHost);
        $check4 ? fwrite($myFile , " Server Valid "): fwrite($myFile , " Server Not Confirmed");

        if($check2 && $check3) {
            // All checks have passed, the payment is successful

        $file = fopen('result.txt', 'wb');

        
        $orderNumber = $pfData['item_name'];
        $userkey = $pfData['m_payment_id'];
        $orderamount = $pfData['amount_gross'];
        $paymentstatus = $pfData['payment_status'];

        //$invoice_number = $_GET['invoice'];

         //TIME VARIABLE
         $d = strtotime("10:30pm April 15 2021");

         $invoice_number = substr(rand(),0, 5);

         $orderdate = date('l jS \of F Y');

         $sqlE = "UPDATE product_order SET product_current = 'completed', invoice_number = '$invoice_number' WHERE product_current = 'cart' AND user_id = '$userkey'";

        if(mysqli_query($con, $sqlE)){
			
			//mail('emmanuel@blackicon.co.za','Testing','Message','From :<emmanuel@oner.co.za>');

            //fwrite($file, $user_id);

            $sql = "SELECT * FROM user_info WHERE user_id = '$userkey';";

            if(!mysqli_num_rows(mysqli_query($con, $sql))){
              echo "<center class='alert alert-warning'>Email Not Found!</center>";
            }else {


                $insert_invoices = "INSERT INTO invoices (invoice_number, invoice_user, invoice_date, invoice_items, invoice_total) VALUES('$invoice_number','$userkey','$orderdate','','$orderamount');";

                mysqli_query($con, $insert_invoices);

                //$number = mysqli_num_rows(mysqli_query($con, $sql));
                $row = mysqli_fetch_assoc(mysqli_query($con, $sql));
        
                $dehash = $row['password'];
        
                $reg_email = $row["email"];
                $reg_name = $row["first_name"];

                $msg_notify = "Your order has been successfully paid with R".$orderamount." via PayFast, please check the details below for more information.";            
                $message = "Your order has been successfully paid with R".$orderamount." via PayFast, please check the details below for more information.";

                $message .= "<br><br><b>Invoice No:</b> ".$invoice_number;
                //$message .= "<br><b>Number of Items:</b> ".$number;
                $message .= "<br><b>Payment Method:</b> PayFast (Debit/Credit Card)";
                $message .= "<br><b>Payment Status:</b> ".$paymentstatus;
                $message .= "<br><b>Date Paid:</b> ".$orderdate;
                $message .= "<br><b>Amount Paid:</b> R".$orderamount;
            

                //$button_link = "https://my.sabooksonline.co.za/books/".$contentid;
                $button_link = "https://".$domain[$i]."/my-orders";
                $link_text = "View Purchased Products";

                $not_type = "Paid";
                
                $subject = "Payment Confirmation For Invoice #".$invoice_number;

                include '../templates/email.php';

                
                mail($reg_email,$subject,$message2,$headers);
      
            }

            
             
				
                       
        }else {
            fwrite($file, 'Failed');
        }
    
} else {
    // Some checks have failed, check payment manually and log for investigation

    fwrite($myFile , "Failed payment");
}

endfor;


