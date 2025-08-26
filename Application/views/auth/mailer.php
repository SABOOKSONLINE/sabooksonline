<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

function sendVerificationEmail($to, $link)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'www22.jnb2.host-h.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@sabooksonline.co.za';
        $mail->Password = '75o783F0O4L79o';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL (465)
        $mail->Port = 465;

        $mail->setFrom('no-reply@sabooksonline.co.za', 'SA Books Online');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Verify your email';
        $mail->Body = "Click the link to verify your email: <a href='$link'>$link</a>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: {$e->getMessage()}");
    }
}


