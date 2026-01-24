<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/MobileBannerModel.php";
require_once __DIR__ . "/../Model/NotificationModel.php";

class MobileController extends Controller
{
    private MobileBannerModel $mobileBannerModel;
    private NotificationModel $notificationModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->mobileBannerModel = new MobileBannerModel($conn);
        $this->notificationModel = new NotificationModel($conn);
    }

    public function banners(): void
    {
        $banners = $this->mobileBannerModel->getAllMobileBanners();

        $this->render("mobile_banners", [
            "banners" => $banners
        ]);
    }

    public function notifications(): void
    {
        $notifications = $this->notificationModel->getAllNotifications();
        
        // Get stats for each notification
        foreach ($notifications as &$notification) {
            $notification['stats'] = $this->notificationModel->getNotificationStats($notification['id']);
        }

        $this->render("mobile_notifications", [
            "notifications" => $notifications
        ]);
    }

    public function sendNotification(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'message' => $_POST['message'] ?? '',
                'image_url' => null,
                'action_url' => $_POST['action_url'] ?? null,
                'target_type' => $_POST['target_type'] ?? 'all',
                'target_audience' => $_POST['target_audience'] ?? 'all', // Default to 'all' if not provided
                'target_criteria' => $_POST['target_criteria'] ?? null,
                'scheduled_at' => $_POST['scheduled_at'] ?? null,
                'created_by' => $_SESSION['ADMIN_EMAIL'] ?? 'admin'
            ];

            // Handle notification image upload
            if (isset($_FILES['notification_image']) && $_FILES['notification_image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['notification_image'];
                $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                
                if (in_array($image['type'], $allowed)) {
                    // Check file size (2MB max for notifications)
                    if ($image['size'] <= 2 * 1024 * 1024) {
                        $uploadDir = __DIR__ . "/../../cms-data/notifications/";
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $fileName = uniqid("notification_", true) . "." . pathinfo($image['name'], PATHINFO_EXTENSION);
                        $targetPath = $uploadDir . $fileName;

                        if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                            $data['image_url'] = $fileName;
                        } else {
                            $_SESSION['error'] = "Failed to upload notification image.";
                            header("Location: /admin/mobile/notifications/send");
                            exit;
                        }
                    } else {
                        $_SESSION['error'] = "Notification image is too large! Maximum size is 2MB.";
                        header("Location: /admin/mobile/notifications/send");
                        exit;
                    }
                } else {
                    $_SESSION['error'] = "Invalid image format! Only JPG, PNG, or WEBP allowed.";
                    header("Location: /admin/mobile/notifications/send");
                    exit;
                }
            }

            // Validate required fields
            if (empty($data['title']) || empty($data['message'])) {
                $_SESSION['error'] = "Title and message are required.";
                header("Location: /admin/mobile/notifications");
                exit;
            }

            // Parse target criteria if it's JSON
            if ($data['target_criteria']) {
                $data['target_criteria'] = json_encode($_POST['target_criteria']);
            }

            $notificationId = $this->notificationModel->addNotification($data);

            if ($notificationId) {
                // If no scheduled time, send immediately
                if (empty($data['scheduled_at'])) {
                    $this->processNotificationSend($notificationId);
                } else {
                    $this->notificationModel->updateNotificationStatus($notificationId, 'scheduled');
                }
                
                $_SESSION['success'] = "Notification created successfully.";
            } else {
                $_SESSION['error'] = "Failed to create notification.";
            }

            header("Location: /admin/mobile/notifications");
            exit;
        }

        $this->render("send_notification", []);
    }

    private function processNotificationSend(int $notificationId): bool
    {
        $notification = $this->notificationModel->getNotificationById($notificationId);
        if (!$notification) {
            return false;
        }

        // Update status to sending
        $this->notificationModel->updateNotificationStatus($notificationId, 'sending');

        // Simple: Get ALL device tokens - app will filter locally
        $deviceTokens = $this->notificationModel->getAllDeviceTokens();
        
        // Get target_audience with default fallback
        $targetAudience = $notification['target_audience'] ?? 'all';
        
        error_log("📧 Notification #{$notificationId} - Sending to ALL mobile users:");
        error_log("   Target Audience: {$targetAudience}");
        error_log("   Total Devices: " . count($deviceTokens));
        error_log("   App will filter locally based on user subscription");

        $successCount = 0;
        $failCount = 0;

        foreach ($deviceTokens as $device) {
            $success = $this->sendPushNotification(
                $device['device_token'],
                $notification['title'],
                $notification['message'],
                $notification['image_url'] ?? null,
                $notification['action_url'] ?? null,
                $targetAudience,
                $device['platform']
            );

            if ($success) {
                $successCount++;
                $this->notificationModel->logNotificationSend(
                    $notificationId,
                    $device['user_email'],
                    $device['device_token'],
                    'sent'
                );
            } else {
                $failCount++;
                $this->notificationModel->logNotificationSend(
                    $notificationId,
                    $device['user_email'],
                    $device['device_token'],
                    'failed',
                    'Push notification service error'
                );
            }
        }

        // Update final status
        $this->notificationModel->updateNotificationStatus($notificationId, 'sent', [
            'total_recipients' => count($deviceTokens),
            'successful_sends' => $successCount,
            'failed_sends' => $failCount
        ]);

        return true;
    }
    
    public function resendNotification(int $notificationId): void
    {
        $notification = $this->notificationModel->getNotificationById($notificationId);
        
        if (!$notification) {
            $_SESSION['error'] = "Notification not found.";
            header("Location: /admin/mobile/notifications");
            exit;
        }
        
        // Reset notification stats and status
        $this->notificationModel->updateNotificationStatus($notificationId, 'sending', [
            'total_recipients' => 0,
            'successful_sends' => 0,
            'failed_sends' => 0
        ]);
        
        // Clear old notification logs for this notification
        $this->notificationModel->clearNotificationLogs($notificationId);
        
        // Resend the notification
        $success = $this->processNotificationSend($notificationId);
        
        if ($success) {
            $_SESSION['success'] = "Notification resent successfully.";
        } else {
            $_SESSION['error'] = "Failed to resend notification.";
        }
        
        header("Location: /admin/mobile/notifications");
        exit;
    }
    
    public function deleteNotification(int $notificationId): void
    {
        $success = $this->notificationModel->deleteNotification($notificationId);
        
        if ($success) {
            $_SESSION['success'] = "Notification deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete notification.";
        }
        
        header("Location: /admin/mobile/notifications");
        exit;
    }
    
    public function previewNotificationRecipients(): void
    {
        error_log("🎯 Preview Recipients API called");
        header('Content-Type: application/json');
        
        try {
            // Get JSON input
            $rawInput = file_get_contents('php://input');
            error_log("📥 Raw input: " . $rawInput);
            
            $input = json_decode($rawInput, true);
            error_log("📋 Decoded input: " . print_r($input, true));
            
            if (!$input || !isset($input['target_audience'])) {
                error_log("❌ Invalid input data");
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid input data - missing target_audience']);
                return;
            }
            
            $targetAudience = $input['target_audience'];
            error_log("🎯 Target audience: " . $targetAudience);
            
            // Get preview data
            $previewData = $this->getTargetAudiencePreview($targetAudience);
            error_log("📊 Preview data: " . print_r($previewData, true));
            
            echo json_encode([
                'success' => true,
                'target_audience' => $targetAudience,
                'matching_users' => $previewData['matching_users'],
                'total_app_users' => $previewData['total_app_users'],
                'summary' => $previewData['summary']
            ]);
            
        } catch (Exception $e) {
            error_log("❌ Preview Recipients Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    
    private function getTargetAudiencePreview(string $targetAudience): array
    {
        // Get all device tokens (represents mobile app users)
        $deviceTokens = $this->notificationModel->getAllDeviceTokens();
        $totalAppUsers = count($deviceTokens);
        
        // Get all users from database
        $sql = "SELECT ADMIN_EMAIL, ADMIN_SUBSCRIPTION, 
                       COALESCE(ADMIN_SUBSCRIPTION, 'Free') as subscription,
                       ADMIN_DATE,
                       (SELECT COUNT(*) FROM book_purchases bp WHERE bp.user_email = users.ADMIN_EMAIL AND bp.payment_status = 'COMPLETE') as purchase_count,
                       (SELECT COUNT(*) FROM device_tokens dt WHERE dt.user_email = users.ADMIN_EMAIL AND dt.is_active = 1) > 0 as has_app
                FROM users 
                WHERE ADMIN_EMAIL IS NOT NULL AND ADMIN_EMAIL != ''";
        
        try {
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Database prepare error: " . $this->conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result();
        } catch (Exception $e) {
            error_log("Preview SQL Error: " . $e->getMessage());
            // Return sample data on error
            return [
                'matching_users' => [],
                'total_matching' => 0,
                'total_app_users' => $totalAppUsers,
                'summary' => []
            ];
        }
        
        $allUsers = [];
        while ($row = $result->fetch_assoc()) {
            $allUsers[] = [
                'email' => $row['ADMIN_EMAIL'],
                'subscription' => $row['ADMIN_SUBSCRIPTION'] ?: 'Free',
                'subscription_lower' => strtolower($row['ADMIN_SUBSCRIPTION'] ?: 'free'),
                'registration_date' => $row['ADMIN_DATE'],
                'purchase_count' => (int)$row['purchase_count'],
                'has_app' => (bool)$row['has_app']
            ];
        }
        
        // Filter users based on target audience
        $matchingUsers = array_filter($allUsers, function($user) use ($targetAudience) {
            switch ($targetAudience) {
                case 'all':
                    return true;
                    
                case 'publishers':
                    return in_array($user['subscription_lower'], ['pro', 'premium', 'standard', 'deluxe']);
                    
                case 'customers':
                case 'free':
                    return in_array($user['subscription_lower'], ['free', '']) || !$user['subscription'];
                    
                case 'book_buyers':
                    return $user['purchase_count'] > 0;
                    
                case 'new_users':
                    if (!$user['registration_date']) return true;
                    $daysSinceReg = (time() - strtotime($user['registration_date'])) / (60 * 60 * 24);
                    return $daysSinceReg <= 30;
                    
                case 'active_users':
                    return $user['has_app']; // Users with mobile app are considered active
                    
                case 'inactive_users':
                    return !$user['has_app']; // Users without mobile app are considered inactive
                    
                case 'pro':
                case 'premium':
                case 'standard':
                case 'deluxe':
                    return $user['subscription_lower'] === $targetAudience;
                    
                default:
                    return true;
            }
        });
        
        // Create summary
        $summary = [];
        $subscriptionCounts = [];
        
        foreach ($matchingUsers as $user) {
            $sub = $user['subscription'];
            $subscriptionCounts[$sub] = ($subscriptionCounts[$sub] ?? 0) + 1;
        }
        
        foreach ($subscriptionCounts as $sub => $count) {
            $summary[] = ['label' => $sub, 'count' => $count];
        }
        
        // Limit to first 50 users for display
        $displayUsers = array_slice($matchingUsers, 0, 50);
        
        return [
            'matching_users' => $displayUsers,
            'total_matching' => count($matchingUsers),
            'total_app_users' => $totalAppUsers,
            'summary' => $summary
        ];
    }

    private function sendPushNotification(string $deviceToken, string $title, string $message, ?string $imageUrl, ?string $actionUrl, string $targetAudience, string $platform): bool
    {
        // Use Expo Push Notification Service
        // Device tokens are in format: ExponentPushToken[xxxxxxxxxxxxxxxxxxxxxx]
        $url = 'https://exp.host/--/api/v2/push/send';
        
        // Build notification payload for Expo
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
        
        // Add image if provided
        if ($imageUrl) {
            // If imageUrl is relative, make it absolute
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $imageUrl = 'https://www.sabooksonline.co.za/cms-data/notifications/' . $imageUrl;
            }
            $notificationData['data']['image'] = $imageUrl;
        }
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Accept-Encoding: gzip, deflate'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationData));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        // Log the response for debugging
        error_log("📤 Expo Push Notification Response (HTTP $httpCode): " . $response);
        if ($curlError) {
            error_log("❌ cURL Error: " . $curlError);
        }
        
        // Parse Expo's response
        if ($httpCode === 200) {
            $responseData = json_decode($response, true);
            if (isset($responseData['data']) && isset($responseData['data'][0])) {
                $status = $responseData['data'][0]['status'] ?? 'unknown';
                if ($status === 'ok') {
                    error_log("✅ Push notification sent successfully to: $deviceToken");
                    return true;
                } else {
                    $error = $responseData['data'][0]['message'] ?? 'Unknown error';
                    error_log("❌ Expo push failed: $error");
                    return false;
                }
            }
        }
        
        error_log("❌ Failed to send push notification. HTTP Code: $httpCode, Response: $response");
        return false;
    }
    
    public function handleBannerForm(): void
    {
        session_start();
        $action = $_POST['action'] ?? 'add';
        
        if ($action === 'add') {
            $this->addBanner();
        } elseif ($action === 'edit') {
            $bannerId = $_POST['id'] ?? 0;
            $this->updateBanner($bannerId);
        }
        
        // Redirect back to banners page
        header("Location: /admin/mobile/banners");
        exit;
    }
    
    private function addBanner(): void
    {
        try {
            error_log("🔧 Starting banner creation process...");
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'action_url' => trim($_POST['action_url'] ?? ''),
                'screen' => $_POST['screen'] ?? 'home',
                'priority' => (int)($_POST['priority'] ?? 0),
                'start_date' => $_POST['start_date'] ?? date('Y-m-d H:i:s'),
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null
            ];

            // Handle image upload
            if (!isset($_FILES['banner_image']) || $_FILES['banner_image']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "Please select a banner image to upload!";
                return;
            }

            $image = $_FILES['banner_image'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            
            if (!in_array($image['type'], $allowed)) {
                $_SESSION['error'] = "Invalid image format! Only JPG, PNG, or WEBP allowed.";
                return;
            }

            // Check file size (5MB max)
            if ($image['size'] > 5 * 1024 * 1024) {
                $_SESSION['error'] = "Image file is too large! Maximum size is 5MB.";
                return;
            }

            $uploadDir = __DIR__ . "/../../cms-data/banners/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid("mobile_banner_", true) . "." . pathinfo($image['name'], PATHINFO_EXTENSION);
            $targetPath = $uploadDir . $fileName;

            if (!move_uploaded_file($image['tmp_name'], $targetPath)) {
                $_SESSION['error'] = "Failed to upload image file.";
                return;
            }

            $data['image_url'] = $fileName;

            if (empty($data['title'])) {
                $_SESSION['error'] = "Title is required!";
                return;
            }

            // Debug: Log the data being sent to the model
            error_log("Banner data being inserted: " . print_r($data, true));
            
            $bannerId = $this->mobileBannerModel->addMobileBanner($data);
            
            error_log("Banner insert result: " . ($bannerId ? "Success (ID: $bannerId)" : "Failed"));
            
            if ($bannerId) {
                $_SESSION['success'] = "Mobile banner created successfully! ID: $bannerId";
            } else {
                $_SESSION['error'] = "Failed to create mobile banner. Check error logs for details.";
            }
            
        } catch (Exception $e) {
            error_log("❌ Add Banner Error: " . $e->getMessage());
            error_log("❌ Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = "Error creating banner: " . $e->getMessage();
        } catch (Error $e) {
            error_log("❌ PHP Error in addBanner: " . $e->getMessage());
            error_log("❌ Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = "PHP Error: " . $e->getMessage();
        }
    }
    
    private function updateBanner(int $bannerId): void
    {
        try {
            if ($bannerId <= 0) {
                $_SESSION['error'] = "Invalid banner ID.";
                return;
            }
            
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'action_url' => trim($_POST['action_url'] ?? ''),
                'screen' => $_POST['screen'] ?? 'home',
                'priority' => (int)($_POST['priority'] ?? 0),
                'start_date' => $_POST['start_date'] ?? date('Y-m-d H:i:s'),
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null
            ];

            // Handle image upload if new image provided
            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['banner_image'];
                $allowed = ['image/jpeg', 'image/png', 'image/webp'];
                
                if (!in_array($image['type'], $allowed)) {
                    $_SESSION['error'] = "Invalid image format! Only JPG, PNG, or WEBP allowed.";
                    return;
                }

                $uploadDir = __DIR__ . "/../../cms-data/banners/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid("mobile_banner_", true) . "." . pathinfo($image['name'], PATHINFO_EXTENSION);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                    $data['image_url'] = $fileName;
                } else {
                    $_SESSION['error'] = "Failed to upload image file.";
                    return;
                }
            } else {
                // Keep existing image if no new image uploaded
                $existingBanner = $this->mobileBannerModel->getMobileBannerById($bannerId);
                $data['image_url'] = $existingBanner['image_url'] ?? '';
            }

            if (empty($data['title'])) {
                $_SESSION['error'] = "Title is required!";
                return;
            }

            $success = $this->mobileBannerModel->updateMobileBanner($bannerId, $data);
            if ($success) {
                $_SESSION['success'] = "Mobile banner updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update mobile banner.";
            }
            
        } catch (Exception $e) {
            error_log("Update Banner Error: " . $e->getMessage());
            $_SESSION['error'] = "Error updating banner: " . $e->getMessage();
        }
    }
}