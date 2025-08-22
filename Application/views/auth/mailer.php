<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

function sendVerificationEmail($to, $link)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'ns1.host-h.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@sabooksonline.co.za';
        $mail->Password = '75o783F0O4L79o';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 465;

        $mail->setFrom('no-reply@sabooksonline.co.za', 'SA Books Online');
        $mail->addAddress($to);
        $mail->Subject = 'Verify your email';
        $mail->isHTML(true);
        $mail->Body = "Click the link to verify your email: <a href='$link'>$link</a>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: {$e->getMessage()}");
    }
}
