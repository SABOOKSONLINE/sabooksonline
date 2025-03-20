<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection script
include '../../database_connections/sabooks.php';

// Define the file upload directory
$uploadDirectory = "../../../cms-data/submissions/";

$contentid = $_POST['contentid'];
$userkey = $_SESSION['ADMIN_USERKEY'];
$name = $_SESSION['ADMIN_NAME'];

// Create the upload directory if it doesn't exist
if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

// Get the current time
$current_time = date('l jS \of F Y');

// Handle PDF file
$pdfFile = $_FILES['pdf'];
$pdfFileName = basename($pdfFile['name']);
$pdfFilePath = $uploadDirectory . $pdfFileName;
move_uploaded_file($pdfFile['tmp_name'], $pdfFilePath);

// Handle Cover Image
$coverImage = $_FILES['cover'];
$coverImageName = basename($coverImage['name']);
$coverImagePath = $uploadDirectory . $coverImageName;
move_uploaded_file($coverImage['tmp_name'], $coverImagePath);

$status = 'Pending';
$type = 'Pdf';
$uniqueKey = substr(uniqid(), 0, 10);

// Insert file details into the app_submissions table
$sql = "INSERT INTO app_submissions (USERKEY, CONTENTID, AUDIO, IMAGE, SUBMITTED, TYPE, STATUS_APP, IDENTITY) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $userkey, $contentid, $pdfFileName, $coverImageName, $current_time, $type, $status, $uniqueKey);

if ($stmt->execute()) {
    // Notify admin by sending an email
    $to = 'emmanuel@sabooksonline.co.za';
    $subject = 'New Submission Notification';
    $message = 'A new App Submission has been made on Sabooks Online by ' . $userkey . '. Please log in to check.';
    $headers = 'From: noreply@sabooksonline.co.za' . "\r\n" .
        'Reply-To: noreply@sabooksonline.co.za' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    // Log the action into pending_updates table
    $log_sql = "INSERT INTO pending_updates (message, type, link, userkey, datetime, contentid, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_message = 'New App Submission by ' . $name;
    $log_type = 'Submission';
    $log_link = ''; // Adjust as needed
    $log_status = 'Pending';

    $log_stmt->bind_param("sssssss", $log_message, $log_type, $log_link, $userkey, $current_time, $uniqueKey, $log_status);
    $log_stmt->execute();

    $log_stmt->close();

    
    echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Files uploaded and saved to the database successfully!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-listings?result=success');},3000);</script>";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();

// Close the database connection
$conn->close();
?>
