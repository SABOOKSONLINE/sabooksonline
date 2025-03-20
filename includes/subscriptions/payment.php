<?php

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
$cartTotal = $price; // This amount needs to be sourced from your application

//$cartTotal = 5;                     
//$cartTotal = 0; //This amount is for a trial period 
$passphrase = 'SABooksOnline2021';//SABooksOnline2021
$data = array(
    // Merchant details
    'merchant_id' => '18172469',//18172469   test: 10030247
    'merchant_key' => 'gwkk16pbxdd8m',//gwkk16pbxdd8m    test: g84pzvwrmr8rj
    'return_url' => 'https://sabooksonline.co.za/includes/subscriptions/verify_payment',
    'cancel_url' => 'https://sabooksonline.co.za/dashboard/plan',
    'notify_url' => 'https://sabooksonline.co.za/includes/subscriptions/notify',
    // Buyer details
    'name_first' => $_SESSION['ADMIN_NAME'],
    'name_last'  => $next_invoice_date,
    'email_address'=> $_SESSION['ADMIN_EMAIL'],
    // Transaction details
    'm_payment_id' => $_SESSION['ADMIN_USERKEY'], // Unique payment ID to pass through to notify_url
    'amount' => number_format( sprintf( '%.2f', $cartTotal ), 2, '.', '' ),
    'item_name' => $plan,
    // Subscription details
    'subscription_type' => '2',   
);

$_SESSION['upgrade'] = $plan;

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
$htmlForm .= '<input class="ud-btn btn-thm mt-2" type="submit" value="Pay With PayFast"><img src="https://my.sabooksonline.co.za/img/Payfast By Network_dark.svg" width="200px"></form>';

echo $htmlForm;
    

?>