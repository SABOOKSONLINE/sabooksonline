<?php
/**
 * PayFast Subscription Cronjob
 * 
 * This script should run daily via cron to:
 * 1. Check for expired subscriptions and degrade users
 * 2. Check for failed recurring payments
 * 3. Update renewal status
 * 
 * Setup cron job (run daily at 2 AM):
 * 0 2 * * * /usr/bin/php /path/to/your/site/cron/payfast-subscription-check.php
 * 
 * Or via URL (if using cron service):
 * https://www.sabooksonline.co.za/cron/payfast-subscription-check.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Application/Config/connection.php';

// Log file
$logFile = __DIR__ . '/subscription-check.log';
$log = function($message) use ($logFile) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    echo $logMessage;
};

$log("=== Starting PayFast Subscription Check ===");

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
    $degradedCount = 0;
    
    while ($row = $result->fetch_assoc()) {
        $expiredCount++;
        $userEmail = $row['user_email'];
        $userKey = $row['ADMIN_USERKEY'];
        $currentPlan = $row['ADMIN_SUBSCRIPTION'];
        $planId = $row['payment_id'];
        $isRecurring = $row['is_recurring'];
        
        $log("Found expired subscription for: $userEmail (Plan: $currentPlan, Recurring: " . ($isRecurring ? 'Yes' : 'No') . ")");
        
        // Check if this is a recurring subscription that should have been renewed
        if ($isRecurring == 1) {
            // Check if PayFast sent a renewal payment notification
            // If not, mark as failed and degrade
            $renewalCheckSql = "SELECT * FROM payment_plans 
                                WHERE user_email = ? 
                                AND token = ? 
                                AND payment_date >= ? 
                                AND status = 'COMPLETE'";
            $renewalStmt = $conn->prepare($renewalCheckSql);
            $renewalStmt->bind_param("sss", $userEmail, $row['token'], $row['end_date']);
            $renewalStmt->execute();
            $renewalResult = $renewalStmt->get_result();
            
            if ($renewalResult->num_rows == 0) {
                // No renewal payment received - mark as failed
                $updateRenewalSql = "UPDATE payment_plans 
                                      SET renewal_status = 'failed', 
                                          updated_at = NOW() 
                                      WHERE payment_id = ?";
                $updateStmt = $conn->prepare($updateRenewalSql);
                $updateStmt->bind_param("i", $planId);
                $updateStmt->execute();
                $updateStmt->close();
                
                $log("  → Recurring payment failed - no renewal received");
            } else {
                // Renewal payment found - skip degradation
                $log("  → Renewal payment found - skipping degradation");
                $renewalStmt->close();
                continue;
            }
            
            $renewalStmt->close();
        }
        
        // Degrade user to Free plan
        $degradeSql = "UPDATE users 
                       SET ADMIN_SUBSCRIPTION = 'Free', 
                           subscription_status = NULL,
                           billing_cycle = NULL
                       WHERE ADMIN_USERKEY = ?";
        $degradeStmt = $conn->prepare($degradeSql);
        $degradeStmt->bind_param("s", $userKey);
        
        if ($degradeStmt->execute()) {
            $degradedCount++;
            $log("  ✓ Degraded user $userEmail from $currentPlan to Free");
        } else {
            $log("  ✗ Failed to degrade user $userEmail: " . $degradeStmt->error);
        }
        
        $degradeStmt->close();
        
        // Update payment plan renewal status
        $updateStatusSql = "UPDATE payment_plans 
                            SET renewal_status = 'expired', 
                                updated_at = NOW() 
                            WHERE payment_id = ?";
        $updateStatusStmt = $conn->prepare($updateStatusSql);
        $updateStatusStmt->bind_param("i", $planId);
        $updateStatusStmt->execute();
        $updateStatusStmt->close();
    }
    
    $stmt->close();
    
    $log("Expired subscriptions found: $expiredCount");
    $log("Users degraded: $degradedCount");
    
    // 2. Check for subscriptions expiring soon (within 3 days) - send reminders
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
    $log("Subscriptions expiring soon: $expiringCount");
    
    // TODO: Send email reminders here if needed
    
    $expiringStmt->close();
    
    $log("=== PayFast Subscription Check Completed ===");
    $log("");
    
} catch (Exception $e) {
    $log("ERROR: " . $e->getMessage());
    $log("Stack trace: " . $e->getTraceAsString());
    http_response_code(500);
    exit(1);
}

$conn->close();
http_response_code(200);
