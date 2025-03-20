<?php

// API SCRIPT FOR FETCHING DATA FROM SABOOKS ONLINE
$fileContent = file_get_contents('../api_key.txt');
include '../api_fetch.php';

for ($i = 0; $i < count($title); $i++):

ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

session_start();

//DATABASE CONNECTIONS SCRIPT
include '../db.php';

$cartTotal = $_GET['amount'];

if(!isset($_SESSION['user_id'])){
    echo 'FSGS';
}else {
    $userkey = $_SESSION['user_id'];
    $sql = "SELECT * FROM user_info WHERE user_id = '$userkey'";
    $result = mysqli_query($con, $sql);
    
    while($row = mysqli_fetch_assoc($result)) { 
        $username = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
    }
    
} 

    $orderNumber = $_GET['invoice'];

    $userkey = $_SESSION['user_id'];

    /**
     * @param array $data
     * @param null $passPhrase
     * @return string
     */
    function generateSignature($data, $passPhrase = null) {
        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val ) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );
        if( $passPhrase !== null ) {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }
        return md5( $getString );
    }
    // Construct variables
	//10000100
	//46f0cd694581a

 
    // Construct variables
    $passphrase = $passphrase[$i];
    $data = array(
        // Merchant details
        'merchant_id' => $merchant_id[$i],//data
        'merchant_key' => $merchant_key[$i],//data
        'return_url' => 'https://'.$domain[$i].'/my-orders?user='.$userkey,
        'cancel_url' => 'https://'.$domain[$i].'/cart?status=cancelled&invoice='.$orderNumber,
        'notify_url' => 'https://'.$domain[$i].'/includes/payment/notify',
        // Buyer details
        'name_first' => $username,
        'name_last'  => $last_name,
        'email_address'=> $email,
        // Transaction details
        'm_payment_id' => $userkey, //Unique payment ID to pass through to notify_url
        'amount' => number_format( sprintf( '%.2f', $cartTotal ), 2, '.', '' ),
        'item_name' => $orderNumber
    );

    $signature = generateSignature($data, $passphrase);
    $data['signature'] = $signature;

    // If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
    $testingMode = false;
    $pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
    $htmlForm = '<form action="https://'.$pfHost.'/eng/process" method="post">';
    foreach($data as $name=> $value)
    {
        $htmlForm .= '<input name="'.$name.'" type="hidden" value=\''.$value.'\' />';
    }
    $htmlForm .= '<button class="cart__total__amount" style="border: none !important;width:100%;"><span>Checkout</span></button></form>';

    echo $htmlForm;

    endfor;