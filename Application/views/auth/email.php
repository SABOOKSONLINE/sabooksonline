<?php
require_once __DIR__ . "/sendEmail.php";


$to = 'kganyamilton@gmail.com';
$link = 'https://sabooksonline.co.za/verify/test';
$message = 'This is a test email';

if(sendEmail($to, $link, $message)){
    echo "Email sent successfully!";
} else {
    echo "Email failed!";
}
