<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . "/../Config/connection.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

function emailExist($conn, $email)
{
    $sql = "SELECT email FROM newsletter_subscribers WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

function validEmail($email)
{
    if (empty($email)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email address is required.'];
        return false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid email format.'];
        return false;
    }
    return true;
}

function insertEmail($conn, $email)
{
    $sql = "INSERT INTO newsletter_subscribers (email) VALUES (?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'DB error: ' . mysqli_error($conn)];
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'You have been subscribed!'];
        return true;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Insert failed: ' . mysqli_stmt_error($stmt)];
        return false;
    }
}

$message = '
<div style="max-width:600px; margin:20px auto; font-family:Arial, sans-serif; border:1px solid #e2e2e2; padding:20px; border-radius:8px; background-color:#f9f9f9;">
    <h2 style="color:#28a745; margin-top:0;">You\'re Subscribed</h2>
    <p style="font-size:16px; color:#333;">Hi there,</p>
    <p style="font-size:16px; color:#333;">Thank you for subscribing to our newsletter.</p>
    <p style="font-size:16px; color:#333;">You’ll now be the first to know about:</p>
    <ul style="font-size:16px; color:#333; padding-left:20px;">
        <li>Trending books</li>
        <li>Latest collections</li>
        <li>Exclusive offers</li>
    </ul>
    <p style="font-size:16px; color:#333;">Stay tuned and happy reading.</p>
    <p style="margin-top:30px; font-size:16px; color:#333;">— The <strong>SA Books Online</strong> Team</p>
    <hr style="margin:20px 0; border:none; border-top:1px solid #ddd;">
</div>';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST['subscribe']);

    if (validEmail($email)) {
        if (!emailExist($conn, $email)) {
            if (insertEmail($conn, $email)) {
                sendEmail("no-reply@sabooksonline.co.za", $email, $message);
            }
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email already subscribed.'];
        }
    }

    header("Location: /home#nl-section");
    exit;
}
