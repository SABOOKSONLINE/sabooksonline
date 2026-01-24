<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Model.php";

class NotificationModel extends Model
{
    private function createNotificationTable(): bool
    {
        $columns = [
            "id" => "INT AUTO_INCREMENT PRIMARY KEY",
            "title" => "VARCHAR(255) NOT NULL",
            "message" => "TEXT NOT NULL",
            "image_url" => "VARCHAR(500)",
            "action_url" => "VARCHAR(500)",
            "target_audience" => "ENUM('all', 'publishers', 'customers', 'free', 'pro', 'premium', 'standard', 'deluxe') NOT NULL DEFAULT 'all'",
            "target_criteria" => "JSON",
            "scheduled_at" => "DATETIME NULL",
            "sent_at" => "DATETIME NULL",
            "status" => "ENUM('draft', 'scheduled', 'sending', 'sent', 'failed') NOT NULL DEFAULT 'draft'",
            "total_recipients" => "INT DEFAULT 0",
            "successful_sends" => "INT DEFAULT 0",
            "failed_sends" => "INT DEFAULT 0",
            "created_by" => "VARCHAR(255)",
            "created_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            "updated_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
        ];

        return $this->createTable("push_notifications", $columns);
    }

    private function createDeviceTokenTable(): bool
    {
        $columns = [
            "id" => "INT AUTO_INCREMENT PRIMARY KEY",
            "user_email" => "VARCHAR(255) NOT NULL",
            "user_key" => "VARCHAR(255)",
            "device_token" => "VARCHAR(500) NOT NULL",
            "platform" => "ENUM('android', 'ios') NOT NULL",
            "app_version" => "VARCHAR(50)",
            "is_active" => "TINYINT(1) DEFAULT 1",
            "last_used" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            "created_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
        ];

        return $this->createTable("device_tokens", $columns);
    }

    private function createNotificationLogTable(): bool
    {
        $columns = [
            "id" => "INT AUTO_INCREMENT PRIMARY KEY",
            "notification_id" => "INT NOT NULL",
            "user_email" => "VARCHAR(255) NOT NULL",
            "device_token" => "VARCHAR(500) NOT NULL",
            "status" => "ENUM('pending', 'sent', 'failed', 'delivered', 'clicked') NOT NULL DEFAULT 'pending'",
            "error_message" => "TEXT",
            "sent_at" => "TIMESTAMP NULL",
            "delivered_at" => "TIMESTAMP NULL",
            "clicked_at" => "TIMESTAMP NULL",
            "created_at" => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
        ];

        return $this->createTable("notification_logs", $columns);
    }

    public function getAllNotifications(): array
    {
        $this->createNotificationTable();
        
        $sql = "SELECT * FROM push_notifications ORDER BY created_at DESC";
        return $this->fetchAll($sql);
    }

    public function addNotification(array $data): int
    {
        $this->createNotificationTable();
        
        $sql = "INSERT INTO push_notifications (title, message, image_url, action_url, target_type, target_criteria, scheduled_at, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->insert(
            $sql,
            "ssssssss",
            [
                $data["title"],
                $data["message"],
                $data["image_url"] ?? null,
                $data["action_url"] ?? null,
                $data["target_type"],
                $data["target_criteria"] ?? null,
                $data["scheduled_at"] ?? null,
                $data["created_by"]
            ]
        );
    }

    public function updateNotification(int $id, array $data): bool
    {
        $sql = "UPDATE push_notifications 
                SET title = ?, message = ?, image_url = ?, action_url = ?, 
                    target_type = ?, target_criteria = ?, scheduled_at = ?
                WHERE id = ? AND status = 'draft'";

        return $this->update(
            $sql,
            "sssssssi",
            [
                $data["title"],
                $data["message"],
                $data["image_url"] ?? null,
                $data["action_url"] ?? null,
                $data["target_type"],
                $data["target_criteria"] ?? null,
                $data["scheduled_at"] ?? null,
                $id
            ]
        );
    }

    public function deleteNotification(int $id): int
    {
        return $this->delete(
            "DELETE FROM push_notifications WHERE id = ? AND status = 'draft'",
            "i",
            [$id]
        );
    }

    public function getNotificationById(int $id): ?array
    {
        $sql = "SELECT * FROM push_notifications WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notification = $result->fetch_assoc();
        $stmt->close();
        
        return $notification ?: null;
    }

    // Device Token Management
    public function registerDeviceToken(array $data): bool
    {
        $this->createDeviceTokenTable();
        
        $sql = "INSERT INTO device_tokens (user_email, user_key, device_token, platform, app_version)
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    platform = VALUES(platform),
                    app_version = VALUES(app_version),
                    is_active = 1,
                    last_used = CURRENT_TIMESTAMP";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $data["user_email"], $data["user_key"], $data["device_token"], $data["platform"], $data["app_version"]);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    public function getAllDeviceTokens(): array
    {
        $this->createDeviceTokenTable();
        
        // Simple: Get ALL active device tokens - let mobile app handle filtering
        $sql = "SELECT DISTINCT dt.device_token, dt.user_email, dt.platform, dt.app_version
                FROM device_tokens dt
                WHERE dt.is_active = 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tokens = [];
        while ($row = $result->fetch_assoc()) {
            $tokens[] = $row;
        }
        
        $stmt->close();
        return $tokens;
    }

    public function validateNotificationTargets(string $targetType, array $criteria = []): array
    {
        $deviceTokens = $this->getDeviceTokens($targetType, $criteria);
        
        $validation = [
            'target_type' => $targetType,
            'criteria' => $criteria,
            'total_recipients' => count($deviceTokens),
            'recipients' => [],
            'warnings' => []
        ];
        
        // Group recipients by email for summary
        $recipientEmails = [];
        foreach ($deviceTokens as $token) {
            if (!isset($recipientEmails[$token['user_email']])) {
                $recipientEmails[$token['user_email']] = [
                    'email' => $token['user_email'],
                    'devices' => 0,
                    'subscription_status' => $token['subscription_status'] ?? 'free',
                    'admin_subscription' => $token['admin_subscription'] ?? null
                ];
            }
            $recipientEmails[$token['user_email']]['devices']++;
        }
        
        $validation['recipients'] = array_values($recipientEmails);
        
        // Add warnings for potential issues
        if (count($deviceTokens) === 0) {
            $validation['warnings'][] = "No recipients found for the selected target criteria";
        }
        
        if ($targetType === 'specific_users' && isset($criteria['emails'])) {
            $requestedEmails = $criteria['emails'];
            $foundEmails = array_column($validation['recipients'], 'email');
            $missingEmails = array_diff($requestedEmails, $foundEmails);
            
            if (!empty($missingEmails)) {
                $validation['warnings'][] = "Some specified users don't have active device tokens: " . implode(', ', $missingEmails);
            }
        }
        
        return $validation;
    }
    
    public function testNotificationTargeting(): array
    {
        // Test different targeting scenarios
        $tests = [
            'all_users' => $this->validateNotificationTargets('all', []),
            'free_users' => $this->validateNotificationTargets('subscription', ['subscription_type' => 'free']),
            'publishers' => $this->validateNotificationTargets('publishers', []),
            'premium_users' => $this->validateNotificationTargets('subscription', ['subscription_type' => 'premium']),
            'customers' => $this->validateNotificationTargets('customers', []),
        ];
        
        return $tests;
    }

    public function updateNotificationStatus(int $id, string $status, array $stats = []): bool
    {
        $sql = "UPDATE push_notifications SET status = ?";
        $params = [$status];
        $types = "s";
        
        if (isset($stats['total_recipients'])) {
            $sql .= ", total_recipients = ?";
            $params[] = $stats['total_recipients'];
            $types .= "i";
        }
        
        if (isset($stats['successful_sends'])) {
            $sql .= ", successful_sends = ?";
            $params[] = $stats['successful_sends'];
            $types .= "i";
        }
        
        if (isset($stats['failed_sends'])) {
            $sql .= ", failed_sends = ?";
            $params[] = $stats['failed_sends'];
            $types .= "i";
        }
        
        if ($status === 'sent') {
            $sql .= ", sent_at = NOW()";
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        $types .= "i";
        
        return $this->update($sql, $types, $params);
    }

    public function logNotificationSend(int $notificationId, string $userEmail, string $deviceToken, string $status, string $errorMessage = null): bool
    {
        $this->createNotificationLogTable();
        
        $sql = "INSERT INTO notification_logs (notification_id, user_email, device_token, status, error_message, sent_at)
                VALUES (?, ?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", $notificationId, $userEmail, $deviceToken, $status, $errorMessage);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    public function getNotificationStats(int $id): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_logs,
                    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent_count,
                    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_count,
                    SUM(CASE WHEN status = 'clicked' THEN 1 ELSE 0 END) as clicked_count
                FROM notification_logs 
                WHERE notification_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stats = $result->fetch_assoc();
        $stmt->close();
        
        return $stats ?: [];
    }
}