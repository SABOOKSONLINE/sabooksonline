<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

function sendEmail($to, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'opensource@sabooksonline.co.za';
        $mail->Password = 'deddipdvujyayzxa';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@sabooksonline.co.za', 'SA Books Online');
        $mail->addReplyTo('support@sabooksonline.co.za', 'SA Books Online Support');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->AltBody = strip_tags($message);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email error for {$to}: {$mail->ErrorInfo}");
        return false;
    }
}
