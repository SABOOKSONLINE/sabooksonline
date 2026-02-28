<?php
/**
 * Notification Helper - Reusable Push Notification Service
 * 
 * This helper provides easy-to-use functions for sending automatic push notifications
 * when events occur in the system (new books, orders, user signups, etc.)
 * 
 * Usage:
 *   NotificationHelper::sendWelcomeNotification($userEmail, $userName);
 *   NotificationHelper::notifyNewBook($bookTitle, $bookId);
 *   NotificationHelper::notifyOrderCreated($orderId, $userId);
 *   NotificationHelper::notifyOrderPaid($orderId, $userId);
 *   NotificationHelper::notifyOrderStatusChanged($orderId, $userId, $newStatus);
 *   NotificationHelper::notifyBookAddedToOrder($orderId, $bookId, $publisherId);
 */

require_once __DIR__ . "/../../Admin/Model/NotificationModel.php";
require_once __DIR__ . "/../../Admin/Controller/MobileController.php";

class NotificationHelper
{
    private static $notificationModel = null;
    private static $mobileController = null;
    
    /**
     * Initialize models (lazy loading)
     */
    private static function init($conn = null)
    {
        if (self::$notificationModel === null) {
            if ($conn === null) {
                require_once __DIR__ . "/../../Config/connection.php";
                global $conn;
            }
            self::$notificationModel = new NotificationModel($conn);
        }
    }
    
    /**
     * Get connection from model
     */
    private static function getConn()
    {
        self::init();
        return self::$notificationModel->conn;
    }
    
    /**
     * Check if user has notifications enabled
     * For now, we assume all users with device tokens have notifications enabled
     * You can add a user_preferences table later to store this
     */
    private static function userHasNotificationsEnabled($userEmail, $conn = null): bool
    {
        self::init($conn);
        $dbConn = self::getConn();
        
        // Check if user has any active device tokens
        $sql = "SELECT COUNT(*) as count FROM device_tokens WHERE user_email = ? AND is_active = 1";
        $stmt = $dbConn->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return ($row['count'] ?? 0) > 0;
    }
    
    /**
     * Get device tokens for a specific user
     */
    private static function getUserDeviceTokens($userEmail, $conn = null): array
    {
        self::init($conn);
        $dbConn = self::getConn();
        
        $sql = "SELECT device_token, platform FROM device_tokens WHERE user_email = ? AND is_active = 1";
        $stmt = $dbConn->prepare($sql);
        if (!$stmt) return [];
        
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tokens = [];
        while ($row = $result->fetch_assoc()) {
            $tokens[] = $row;
        }
        $stmt->close();
        
        return $tokens;
    }
    
    /**
     * Send push notification to specific user(s)
     */
    private static function sendToUser($userEmail, $title, $message, $actionUrl = null, $imageUrl = null, $conn = null): bool
    {
        if (!self::userHasNotificationsEnabled($userEmail, $conn)) {
            return false;
        }
        
        $tokens = self::getUserDeviceTokens($userEmail, $conn);
        if (empty($tokens)) {
            return false;
        }
        
        $success = false;
        foreach ($tokens as $tokenData) {
            $sent = self::sendPushNotification(
                $tokenData['device_token'],
                $title,
                $message,
                $imageUrl,
                $actionUrl,
                'all',
                $tokenData['platform'],
                $conn
            );
            if ($sent) $success = true;
        }
        
        return $success;
    }
    
    /**
     * Send push notification to multiple users
     */
    private static function sendToUsers(array $userEmails, $title, $message, $actionUrl = null, $imageUrl = null, $conn = null): int
    {
        $successCount = 0;
        foreach ($userEmails as $email) {
            if (self::sendToUser($email, $title, $message, $actionUrl, $imageUrl, $conn)) {
                $successCount++;
            }
        }
        return $successCount;
    }
    
    /**
     * Send push notification using Expo API
     */
    private static function sendPushNotification(string $deviceToken, string $title, string $message, ?string $imageUrl, ?string $actionUrl, string $targetAudience, string $platform, $conn = null): bool
    {
        $url = 'https://exp.host/--/api/v2/push/send';
        
        $notificationData = [
            'to' => $deviceToken,
            'title' => $title,
            'body' => $message,
            'sound' => 'default',
            'badge' => 1,
            'data' => [
                'action_url' => $actionUrl ?? '',
                'target_audience' => $targetAudience,
                'notification_id' => uniqid(),
                'timestamp' => time()
            ]
        ];
        
        if ($imageUrl) {
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $imageUrl = 'https://www.sabooksonline.co.za/cms-data/notifications/' . $imageUrl;
            }
            $notificationData['data']['image'] = $imageUrl;
        }
        
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json',
            'Accept-Encoding: gzip, deflate'
        ];
        
        $title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');
        $message = mb_convert_encoding($message, 'UTF-8', 'UTF-8');
        $notificationData['title'] = $title;
        $notificationData['body'] = $message;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationData, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $responseData = json_decode($response, true);
            if (isset($responseData['data'][0]['status']) && $responseData['data'][0]['status'] === 'ok') {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Send welcome notification when user signs up
     */
    public static function sendWelcomeNotification($userEmail, $userName, $conn = null): bool
    {
        $title = "Welcome to SA Books Online! 📚";
        $message = "Hi {$userName}! Your account has been created successfully. Start exploring our collection of books!";
        $actionUrl = "/library";
        
        return self::sendToUser($userEmail, $title, $message, $actionUrl, null, $conn);
    }
    
    /**
     * Notify all users about new book (regular books)
     */
    public static function notifyNewBook($bookTitle, $bookId, $conn = null): bool
    {
        self::init($conn);
        $dbConn = self::getConn();
        
        // Get all users with active device tokens
        $sql = "SELECT DISTINCT dt.device_token, dt.user_email, dt.platform, dt.app_version
                FROM device_tokens dt
                WHERE dt.is_active = 1";
        
        $stmt = $dbConn->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tokens = [];
        while ($row = $result->fetch_assoc()) {
            $tokens[] = $row;
        }
        $stmt->close();
        
        if (empty($tokens)) return false;
        
        $title = "📖 New Book Available!";
        $message = "Check out '{$bookTitle}' - now available in our library!";
        $actionUrl = "/library/book/{$bookId}";
        
        $successCount = 0;
        foreach ($tokens as $device) {
            if (self::sendPushNotification(
                $device['device_token'],
                $title,
                $message,
                null,
                $actionUrl,
                'all',
                $device['platform'],
                $conn
            )) {
                $successCount++;
            }
        }
        
        return $successCount > 0;
    }
    
    /**
     * Notify all users about new academic book
     */
    public static function notifyNewAcademicBook($bookTitle, $bookId, $conn = null): bool
    {
        self::init($conn);
        $dbConn = self::getConn();
        
        $sql = "SELECT DISTINCT dt.device_token, dt.user_email, dt.platform, dt.app_version
                FROM device_tokens dt
                WHERE dt.is_active = 1";
        
        $stmt = $dbConn->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tokens = [];
        while ($row = $result->fetch_assoc()) {
            $tokens[] = $row;
        }
        $stmt->close();
        
        if (empty($tokens)) return false;
        
        $title = "🎓 New Academic Book!";
        $message = "New academic book '{$bookTitle}' has been added to our collection!";
        $actionUrl = "/books/academic/{$bookId}";
        
        $successCount = 0;
        foreach ($tokens as $device) {
            if (self::sendPushNotification(
                $device['device_token'],
                $title,
                $message,
                null,
                $actionUrl,
                'all',
                $device['platform'],
                $conn
            )) {
                $successCount++;
            }
        }
        
        return $successCount > 0;
    }
    
    /**
     * Notify user when order is created (pending payment)
     */
    public static function notifyOrderCreated($orderId, $userId, $orderNumber, $conn = null): bool
    {
        // Get user email from user ID
        $userEmail = self::getUserEmailById($userId, $conn);
        if (!$userEmail) return false;
        
        $title = "🛒 Order Created";
        $message = "Your order #{$orderNumber} has been created. Please complete payment to proceed.";
        $actionUrl = "/orders/{$orderId}";
        
        return self::sendToUser($userEmail, $title, $message, $actionUrl, null, $conn);
    }
    
    /**
     * Notify user when order is paid
     */
    public static function notifyOrderPaid($orderId, $userId, $orderNumber, $conn = null): bool
    {
        $userEmail = self::getUserEmailById($userId, $conn);
        if (!$userEmail) return false;
        
        $title = "✅ Payment Successful!";
        $message = "Your order #{$orderNumber} has been paid successfully. We'll process it soon!";
        $actionUrl = "/orders/{$orderId}";
        
        return self::sendToUser($userEmail, $title, $message, $actionUrl, null, $conn);
    }
    
    /**
     * Notify user when order status changes
     */
    public static function notifyOrderStatusChanged($orderId, $userId, $newStatus, $orderNumber, $conn = null): bool
    {
        $userEmail = self::getUserEmailById($userId, $conn);
        if (!$userEmail) return false;
        
        $statusMessages = [
            'processing' => "Your order #{$orderNumber} is being processed.",
            'packed' => "Your order #{$orderNumber} has been packed and is ready to ship!",
            'shipped' => "Your order #{$orderNumber} has been shipped! Track your delivery.",
            'out_for_delivery' => "Your order #{$orderNumber} is out for delivery!",
            'delivered' => "Your order #{$orderNumber} has been delivered! Enjoy your books!",
            'cancelled' => "Your order #{$orderNumber} has been cancelled.",
            'returned' => "Your order #{$orderNumber} has been returned."
        ];
        
        $title = "📦 Order Update";
        $message = $statusMessages[strtolower($newStatus)] ?? "Your order #{$orderNumber} status has been updated to {$newStatus}.";
        $actionUrl = "/orders/{$orderId}";
        
        return self::sendToUser($userEmail, $title, $message, $actionUrl, null, $conn);
    }
    
    /**
     * Notify publisher when their book is added to an order
     */
    public static function notifyBookAddedToOrder($orderId, $bookId, $bookTitle, $publisherId, $orderNumber, $conn = null): bool
    {
        // Get publisher email from publisher ID
        $publisherEmail = self::getPublisherEmailById($publisherId, $conn);
        if (!$publisherEmail) return false;
        
        $title = "💰 New Order for Your Book!";
        $message = "Your book '{$bookTitle}' has been added to order #{$orderNumber}!";
        $actionUrl = "/admin/orders/{$orderId}";
        
        return self::sendToUser($publisherEmail, $title, $message, $actionUrl, null, $conn);
    }
    
    /**
     * Get user email by user ID
     */
    private static function getUserEmailById($userId, $conn = null): ?string
    {
        self::init($conn);
        $dbConn = self::getConn();
        
        $sql = "SELECT ADMIN_EMAIL FROM users WHERE ADMIN_ID = ?";
        $stmt = $dbConn->prepare($sql);
        if (!$stmt) return null;
        
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['ADMIN_EMAIL'] ?? null;
    }
    
    /**
     * Get publisher email by publisher ID (from posts table)
     */
    private static function getPublisherEmailById($publisherId, $conn = null): ?string
    {
        self::init($conn);
        $dbConn = self::getConn();
        
        // Get user ID from posts table
        $sql = "SELECT USERID FROM posts WHERE CONTENTID = ? OR ID = ? LIMIT 1";
        $stmt = $dbConn->prepare($sql);
        if (!$stmt) return null;
        
        $stmt->bind_param("ss", $publisherId, $publisherId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        if (!$row || !isset($row['USERID'])) return null;
        
        // Get email from user ID
        $userKey = $row['USERID'];
        return self::getUserEmailByKey($userKey, $conn);
    }
    
    /**
     * Get user email by user key
     */
    private static function getUserEmailByKey($userKey, $conn = null): ?string
    {
        self::init($conn);
        $dbConn = self::getConn();
        
        $sql = "SELECT ADMIN_EMAIL FROM users WHERE ADMIN_USERKEY = ? OR ADMIN_ID = ?";
        $stmt = $dbConn->prepare($sql);
        if (!$stmt) return null;
        
        $stmt->bind_param("ss", $userKey, $userKey);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['ADMIN_EMAIL'] ?? null;
    }
    
    /**
     * Notify admin when order status changes (for admin users)
     */
    public static function notifyAdminOrderStatusChanged($orderId, $orderNumber, $newStatus, $conn = null): bool
    {
        self::init($conn);
        $dbConn = self::getConn();
        
        // Get all admin users (users with subscription Pro/Premium/Standard/Deluxe)
        $sql = "SELECT DISTINCT dt.user_email 
                FROM device_tokens dt
                INNER JOIN users u ON dt.user_email = u.ADMIN_EMAIL
                WHERE dt.is_active = 1 
                AND u.ADMIN_SUBSCRIPTION IN ('Pro', 'Premium', 'Standard', 'Deluxe')";
        
        $stmt = $dbConn->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $adminEmails = [];
        while ($row = $result->fetch_assoc()) {
            $adminEmails[] = $row['user_email'];
        }
        $stmt->close();
        
        if (empty($adminEmails)) return false;
        
        $title = "📊 Order Status Update";
        $message = "Order #{$orderNumber} status changed to {$newStatus}";
        $actionUrl = "/admin/orders/{$orderId}";
        
        return self::sendToUsers($adminEmails, $title, $message, $actionUrl, null, $conn) > 0;
    }
}
