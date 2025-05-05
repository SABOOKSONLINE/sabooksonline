<?php

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$client = new Google_Client();
$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://sabooksonline.co.za');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: ' . filter_var('https://login.sabooksonline.co.za', FILTER_SANITIZE_URL));
    exit;
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $service = new Google_Service_Oauth2($client);
    $user = $service->userinfo->get();

    // Store user data in variables
    $userId = $user->getId();
    $userName = $user->getName();
    $userEmail = $user->getEmail();
    
    // Store user data in the database
    $conn = new mysqli("localhost", "google", "!Emmanuel@1632", "manqas_google");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Connected successfully<br>";
    }
    
    $stmt = $conn->prepare("INSERT INTO users (google_id, name, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $userId, $userName, $userEmail);
    if ($stmt->execute()) {
        echo "Record inserted successfully<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    $stmt->close();
    $conn->close();

    // Send confirmation email
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.sabooksonline.co.za';
        $mail->SMTPAuth = true;
        $mail->Username = 'emmanuel@sabooksonline.co.za';
        $mail->Password = '!Emmanuel@1632';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('emmanuel@sabooksonline.co.za', 'SA Books Online');
        $mail->addAddress($userEmail, $userName);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to our website';
        $mail->Body    = 'Thank you for registering with us.';

        // Attempt to send email
        if ($mail->send()) {
            echo 'Registration successful. Confirmation email sent.';
        } else {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $authUrl = $client->createAuthUrl();
    echo '<a href="' . $authUrl . '">Register with Google</a>';
}
