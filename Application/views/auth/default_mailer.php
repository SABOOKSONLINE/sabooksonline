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
        $mail->Username = 'opensource@sabooksonline.co.za'; // owner account
        $mail->Password = 'deddipdvujyayzxa'; // app password

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // port 587 requires STARTTLS
        $mail->Port = 587;

        $mail->setFrom('no-reply@sabooksonline.co.za', 'SA Books Online'); // alias
        $mail->addReplyTo('support@sabooksonline.co.za', 'SA Books Online Support'); // optional

        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Verify your email';
        $mail->Body = "$message <a href='$link'>$link</a>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: {$mail->ErrorInfo}");
    }
}
