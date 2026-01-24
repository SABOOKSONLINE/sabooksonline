<?php

require_once __DIR__ . "/../Config/connection.php";

class NotificationModel
{
    private $conn;

    public function __construct($connection = null)
    {
        global $conn;
        $this->conn = $connection ?? $conn;
    }

    /**
     * Get notifications for a specific user
     */
    public function getUserNotifications($userEmail, $limit = 20)
    {
        // Create notification_reads table if it doesn't exist
        $this->createNotificationReadsTable();

        $sql = "SELECT n.id, n.title, n.message, n.image_url, n.action_url, n.created_at,
                       CASE WHEN nr.id IS NOT NULL THEN 1 ELSE 0 END as `read`
                FROM push_notifications n
                LEFT JOIN notification_reads nr ON n.id = nr.notification_id AND nr.user_email = ?
                WHERE (n.target_type = 'all' OR 
                       (n.target_type IN ('customers', 'free') AND 1=1) OR
                       (n.target_type = 'book_buyers' AND ? IN (SELECT DISTINCT user_email FROM book_purchases WHERE payment_status = 'COMPLETE')))
                AND n.status = 'sent'
                ORDER BY n.created_at DESC
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Notification Model Error: " . $this->conn->error);
            return [];
        }

        $stmt->bind_param("ssi", $userEmail, $userEmail, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            // Fix image URL to use full path
            if ($row['image_url']) {
                $row['image_url'] = "https://www.sabooksonline.co.za/cms-data/notifications/" . $row['image_url'];
            }
            $notifications[] = $row;
        }
        
        $stmt->close();
        return $notifications;
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userEmail)
    {
        $this->createNotificationReadsTable();

        $sql = "SELECT COUNT(*) as unread_count
                FROM push_notifications n
                LEFT JOIN notification_reads nr ON n.id = nr.notification_id AND nr.user_email = ?
                WHERE nr.id IS NULL
                AND (n.target_type = 'all' OR 
                     (n.target_type IN ('customers', 'free') AND 1=1) OR
                     (n.target_type = 'book_buyers' AND ? IN (SELECT DISTINCT user_email FROM book_purchases WHERE payment_status = 'COMPLETE')))
                AND n.status = 'sent'";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("ss", $userEmail, $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return (int)$row['unread_count'];
    }

    /**
     * Mark a notification as read for a user
     */
    public function markAsRead($notificationId, $userEmail)
    {
        $this->createNotificationReadsTable();

        $sql = "INSERT IGNORE INTO notification_reads (notification_id, user_email) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("is", $notificationId, $userEmail);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userEmail)
    {
        $this->createNotificationReadsTable();

        $sql = "INSERT IGNORE INTO notification_reads (notification_id, user_email)
                SELECT n.id, ? FROM push_notifications n
                WHERE (n.target_type = 'all' OR 
                       (n.target_type IN ('customers', 'free') AND 1=1) OR
                       (n.target_type = 'book_buyers' AND ? IN (SELECT DISTINCT user_email FROM book_purchases WHERE payment_status = 'COMPLETE')))
                AND n.status = 'sent'
                AND NOT EXISTS (
                    SELECT 1 FROM notification_reads nr 
                    WHERE nr.notification_id = n.id AND nr.user_email = ?
                )";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sss", $userEmail, $userEmail, $userEmail);
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }

    /**
     * Create notification_reads table if it doesn't exist
     */
    private function createNotificationReadsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS notification_reads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            notification_id INT NOT NULL,
            user_email VARCHAR(255) NOT NULL,
            read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_read (notification_id, user_email),
            INDEX idx_user_email (user_email),
            INDEX idx_notification_id (notification_id)
        )";
        
        $this->conn->query($sql);
    }

    /**
     * Format timestamp for display
     */
    public static function formatTime($timestamp)
    {
        $date = new DateTime($timestamp);
        $now = new DateTime();
        $diff = $now->diff($date);
        
        if ($diff->days == 0) {
            if ($diff->h == 0) {
                if ($diff->i == 0) {
                    return 'Just now';
                }
                return $diff->i . 'm ago';
            }
            return $diff->h . 'h ago';
        } elseif ($diff->days < 7) {
            return $diff->days . 'd ago';
        } else {
            return $date->format('M j, Y');
        }
    }
}