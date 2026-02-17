<?php
/**
 * TEST MODE - PayFast Subscription Check
 * 
 * This is a test version that shows what would happen WITHOUT making changes
 * Visit: https://www.sabooksonline.co.za/cron/test-subscription-check.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Application/Config/connection.php';

// Test mode flag
$TEST_MODE = true;

// Log file
$logFile = __DIR__ . '/subscription-check-test.log';
$log = function($message) use ($logFile) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    echo $logMessage . "<br>";
};

echo "<h2>PayFast Subscription Check - TEST MODE</h2>";
echo "<p><strong>No changes will be made - this is a dry run</strong></p>";
echo "<hr>";

$log("=== Starting PayFast Subscription Check (TEST MODE) ===");

try {
    // 1. Find expired subscriptions (end_date < today)
    $today = date('Y-m-d');
    $log("Checking for expired subscriptions (end_date < $today)...");
    
    $expiredSql = "SELECT pp.*, u.ADMIN_USERKEY, u.ADMIN_EMAIL, u.ADMIN_SUBSCRIPTION
                    FROM payment_plans pp
                    JOIN users u ON pp.user_email = u.ADMIN_EMAIL
                    WHERE pp.end_date < ? 
                    AND pp.status = 'COMPLETE'
                    AND pp.renewal_status = 'active'
                    AND u.ADMIN_SUBSCRIPTION != 'Free'";
    
    $stmt = $conn->prepare($expiredSql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $expiredCount = 0;
    $wouldDegradeCount = 0;
    $expiredUsers = [];
    
    while ($row = $result->fetch_assoc()) {
        $expiredCount++;
        $userEmail = $row['user_email'];
        $userKey = $row['ADMIN_USERKEY'];
        $currentPlan = $row['ADMIN_SUBSCRIPTION'];
        $planId = $row['payment_id'];
        $isRecurring = $row['is_recurring'];
        $endDate = $row['end_date'];
        
        $log("Found expired subscription for: $userEmail");
        $log("  - Current Plan: $currentPlan");
        $log("  - End Date: $endDate");
        $log("  - Recurring: " . ($isRecurring ? 'Yes' : 'No'));
        
        // Check if this is a recurring subscription that should have been renewed
        $shouldDegrade = true;
        if ($isRecurring == 1) {
            // Check if PayFast sent a renewal payment notification
            $renewalCheckSql = "SELECT * FROM payment_plans 
                                WHERE user_email = ? 
                                AND token = ? 
                                AND payment_date >= ? 
                                AND status = 'COMPLETE'";
            $renewalStmt = $conn->prepare($renewalCheckSql);
            $renewalStmt->bind_param("sss", $userEmail, $row['token'], $endDate);
            $renewalStmt->execute();
            $renewalResult = $renewalStmt->get_result();
            
            if ($renewalResult->num_rows == 0) {
                $log("  → Recurring payment FAILED - no renewal received");
                $shouldDegrade = true;
            } else {
                $log("  → Renewal payment FOUND - would skip degradation");
                $shouldDegrade = false;
            }
            
            $renewalStmt->close();
        }
        
        if ($shouldDegrade) {
            $wouldDegradeCount++;
            $expiredUsers[] = [
                'email' => $userEmail,
                'current_plan' => $currentPlan,
                'end_date' => $endDate,
                'recurring' => $isRecurring
            ];
            $log("  ✓ WOULD DEGRADE: $userEmail from $currentPlan to Free");
        }
    }
    
    $stmt->close();
    
    echo "<h3>Summary:</h3>";
    echo "<ul>";
    echo "<li><strong>Expired subscriptions found:</strong> $expiredCount</li>";
    echo "<li><strong>Users that would be degraded:</strong> $wouldDegradeCount</li>";
    echo "</ul>";
    
    if (!empty($expiredUsers)) {
        echo "<h3>Users that would be degraded:</h3>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Email</th><th>Current Plan</th><th>End Date</th><th>Recurring</th></tr>";
        foreach ($expiredUsers as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['current_plan']) . "</td>";
            echo "<td>" . htmlspecialchars($user['end_date']) . "</td>";
            echo "<td>" . ($user['recurring'] ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    $log("Expired subscriptions found: $expiredCount");
    $log("Users that would be degraded: $wouldDegradeCount");
    
    // 2. Check for subscriptions expiring soon (within 3 days)
    $threeDaysLater = date('Y-m-d', strtotime('+3 days'));
    $log("Checking for subscriptions expiring soon (end_date <= $threeDaysLater)...");
    
    $expiringSql = "SELECT pp.*, u.ADMIN_USERKEY, u.ADMIN_EMAIL, u.ADMIN_SUBSCRIPTION
                     FROM payment_plans pp
                     JOIN users u ON pp.user_email = u.ADMIN_EMAIL
                     WHERE pp.end_date BETWEEN ? AND ?
                     AND pp.status = 'COMPLETE'
                     AND pp.renewal_status = 'active'
                     AND u.ADMIN_SUBSCRIPTION != 'Free'";
    
    $expiringStmt = $conn->prepare($expiringSql);
    $expiringStmt->bind_param("ss", $today, $threeDaysLater);
    $expiringStmt->execute();
    $expiringResult = $expiringStmt->get_result();
    
    $expiringCount = $expiringResult->num_rows;
    $expiringUsers = [];
    
    while ($row = $expiringResult->fetch_assoc()) {
        $expiringUsers[] = [
            'email' => $row['user_email'],
            'plan' => $row['ADMIN_SUBSCRIPTION'],
            'end_date' => $row['end_date']
        ];
    }
    
    $log("Subscriptions expiring soon: $expiringCount");
    
    if (!empty($expiringUsers)) {
        echo "<h3>Subscriptions expiring soon (within 3 days):</h3>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Email</th><th>Plan</th><th>End Date</th></tr>";
        foreach ($expiringUsers as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['plan']) . "</td>";
            echo "<td>" . htmlspecialchars($user['end_date']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    $expiringStmt->close();
    
    $log("=== PayFast Subscription Check Completed (TEST MODE) ===");
    $log("");
    
    echo "<hr>";
    echo "<p><strong>Test completed!</strong> Check the log file: <code>cron/subscription-check-test.log</code></p>";
    echo "<p>If everything looks correct, run the actual script: <code>cron/payfast-subscription-check.php</code></p>";
    
} catch (Exception $e) {
    $log("ERROR: " . $e->getMessage());
    $log("Stack trace: " . $e->getTraceAsString());
    echo "<p style='color: red;'><strong>ERROR:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    http_response_code(500);
    exit(1);
}

$conn->close();
http_response_code(200);
