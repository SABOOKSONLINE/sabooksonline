<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$ftpHost = 'ftp.onerhosting.co.za';  // Replace with your FTP server hostname
$ftpUsername = 'oner_hostings'; // Replace with your FTP username
$ftpPassword = '!Emmanuel@1632'; // Replace with your FTP password

$localFilePath = '../website/email.php';   // Replace with the local file path
$remoteFilePath = '/httpdocs/email.php'; // Replace with the remote file path in Plesk

// Connect to the FTP server
$ftpConnection = ftp_connect($ftpHost);
if (!$ftpConnection) {
    die('Could not connect to the FTP server');
}

// Log in to the FTP server
$loginResult = ftp_login($ftpConnection, $ftpUsername, $ftpPassword);
if (!$loginResult) {
    die('FTP login failed');
}

// Upload the file
$uploadResult = ftp_put($ftpConnection, $remoteFilePath, $localFilePath, FTP_BINARY);
if (!$uploadResult) {
    die('File upload failed');
}

// Close the FTP connection
ftp_close($ftpConnection);

echo 'File uploaded successfully.';
?>
