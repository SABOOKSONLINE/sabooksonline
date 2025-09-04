<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

function sendVerificationEmail($to, $userName, $verificationLink = null, $device = 'SABO Website')
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'www22.jnb2.host-h.net';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@sabooksonline.co.za';
        $mail->Password = '75o783F0O4L79o';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('no-reply@sabooksonline.co.za', 'SA Books Online');
        $mail->addAddress($to);
        $mail->isHTML(true);

        if ($verificationLink) {
            $mail->Subject = 'Verify your email';
            ob_start();
            include __DIR__ . '/../emailTemplates/verification.php';
            $body = ob_get_clean();
        } else {
            $mail->Subject = 'Thank You for Signing Up!';
            ob_start();
            include __DIR__ . '/../emailTemplates/Signup.php';
            $body = ob_get_clean();
        }

        $mail->Body = $body;
        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: {$e->getMessage()}");
    }
}




