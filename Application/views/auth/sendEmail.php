<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../../vendor/autoload.php';

function sendEmail($to, $link, $message)
{
    $mail = new PHPMailer(true);
    try {
        // Debugging
        $mail->SMTPDebug = 2; // 0 = off (for production), 2 = client/server messages
        $mail->Debugoutput = function ($str, $level) {
            error_log("SMTP Debug [$level]: $str");
        };

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';       // Your hosting SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'opensource@sabooksonline.co.za';
        $mail->Password = 'deddipdvujyayzxa';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL (465)
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@sabooksonline.co.za', 'SA Books Online');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify your email';
        $mail->Body = "$message <a href='$link'>$link</a>";

        // Send email
        if ($mail->send()) {
            error_log("✅ Email successfully sent to $to");
            return true;
        } else {
            error_log("❌ Email failed: {$mail->ErrorInfo}");
            return false;
        }
    } catch (Exception $e) {
        error_log("❌ PHPMailer Exception: {$e->getMessage()}");
        return false;
    }
}
