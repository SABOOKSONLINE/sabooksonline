<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

function sendEmail($to, $link, $message)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@sabooksonline.co.za';
        $mail->Password = 'DehKaDRqASgW4rB&';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('no-reply@sabooksonline.co.za', 'SA Books Online');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Verify your email';
        $mail->Body = "$message <a href='$link'>$link</a>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: {$mail->ErrorInfo}");
    }
}
