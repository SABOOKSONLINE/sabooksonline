

<?php

require_once 'httpdocs/includes/database_connections/sabooks.php'; // Include your database configuration

// Create connection
$conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    $sql = "UPDATE events SET DURATION = DURATION - 1 WHERE STATUS = 'Active'";
    
    if ($conn->query($sql)) {
        // Logging
        $ipAddress = '41.76.111.78';
        $deviceType = 'SABO Server';
        $invoiceUser = 'SABO Server';
        $logMessage = "Events crons ran successfully.";
        
        $logInsertSql = "INSERT INTO logs (user_id, ip_address, device_type, action) VALUES (?, ?, ?, ?)";
        $logStmt = $conn->prepare($logInsertSql);
        
        if ($logStmt) {
            $logStmt->bind_param("ssss", $invoiceUser, $ipAddress, $deviceType, $logMessage);
            
            if ($logStmt->execute()) {
                echo 'Cron Ran Correctly!';
            } else {
                echo "Error inserting log.";
            }
            
            $logStmt->close();
        } else {
            echo "Error preparing log statement.";
        }
    } else {
        echo "Error updating records.";
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}

$conn->close();
?>
