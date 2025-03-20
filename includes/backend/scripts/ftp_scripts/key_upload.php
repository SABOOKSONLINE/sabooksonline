<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$ftpHost = '41.76.111.78';
$ftpUsername = $customerUsername;
$ftpPassword = $customerPassword;

if($domain_type == 'main'){
    $remoteFilePath = '/httpdocs/site/includes/api_key.txt'; // Replace with the remote file path in Plesk
    $ftpUsername = $customerUsername;
    $ftpPassword = $customerPassword;

}else if($domain_type == 'sub'){
    $ftpUsername = 'sabookso';
    $ftpPassword = '!#Sabo@12345';
    $remoteFilePath = "/$userkey/includes/api_key.txt"; // Remote path for extracted files
}


$localFilePath = 'ftp_scripts/api_key.txt';   // Replace with the local file path

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

//echo 'File uploaded successfully.';
?>
