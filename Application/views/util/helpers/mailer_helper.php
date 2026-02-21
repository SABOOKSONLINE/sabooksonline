<?php

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../../../vendor/autoload.php';

function sendEmail($from, $to, $message, $link = "", $name = "")
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'ns1.host-h.net';
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = getenv('SMTP_PASSWORD') ?: '';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 465;

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
