<?php


error_reporting(0);
    ini_set('display_errors', 0);

$ftpHost = '41.76.111.78';


$localPath = '../../../themes/theme-02';    // Path to the local zip file

if($domain_type == 'main'){
    $remotePath = '/httpdocs/site/'; // Remote path for extracted files

    $ftpUsername = $customerUsername;
    $ftpPassword = $customerPassword;

}else if($domain_type == 'sub'){
    $ftpUsername = 'sabookso';
    $ftpPassword = '!#Sabo@12345';
    $remotePath = "/$userkey/"; // Remote path for extracted files
}


$ftpConnection = ftp_connect($ftpHost);
$login = ftp_login($ftpConnection, $ftpUsername, $ftpPassword);
if (!$ftpConnection || !$login) {
    die('FTP connection failed.');
}

function uploadRecursive($localDir, $remoteDir) {
    global $ftpConnection;

    if (!ftp_mkdir($ftpConnection, $remoteDir)) {
       // echo "Failed to create remote directory $remoteDir\n";
    }

    $files = scandir($localDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $localFile = $localDir . '/' . $file;
            $remoteFile = $remoteDir . '/' . $file;
            if (is_dir($localFile)) {
                uploadRecursive($localFile, $remoteFile);
            } else {
                if (ftp_put($ftpConnection, $remoteFile, $localFile, FTP_BINARY)) {
                   // echo "Uploaded $localFile to $remoteFile\n";
                } else {
                   // echo "Failed to upload $localFile to $remoteFile\n";
                }
            }
        }
    }
}

uploadRecursive($localPath, $remotePath);

//ftp_close($ftpConnection);
?>

