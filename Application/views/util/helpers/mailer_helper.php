<?php

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../../../vendor/autoload.php';

function sendEmail($from, $to, $message, $link = "", $name = "")
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'da7.host-ww.net';
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = 'Sabo_365+';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($from, $name);
        $mail->addAddress($to);
        $mail->Subject = 'Verify your email';
        $mail->isHTML(true);
        $mail->Body = "$message <a href='$link'>$link</a>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: {$mail->ErrorInfo}");
    }
}
