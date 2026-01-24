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
                'image_url' => $_POST['image_url'] ?? null,
                'action_url' => $_POST['action_url'] ?? null,
                'target_type' => $_POST['target_type'] ?? 'all',
                'target_criteria' => $_POST['target_criteria'] ?? null,
                'scheduled_at' => $_POST['scheduled_at'] ?? null,
                'created_by' => $_SESSION['ADMIN_EMAIL'] ?? 'admin'
            ];

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

        // Get target device tokens
        $criteria = $notification['target_criteria'] ? json_decode($notification['target_criteria'], true) : [];
        $deviceTokens = $this->notificationModel->getDeviceTokens($notification['target_type'], $criteria);

        $successCount = 0;
        $failCount = 0;

        foreach ($deviceTokens as $device) {
            $success = $this->sendPushNotification(
                $device['device_token'],
                $notification['title'],
                $notification['message'],
                $notification['image_url'],
                $notification['action_url'],
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

    private function sendPushNotification(string $deviceToken, string $title, string $message, ?string $imageUrl, ?string $actionUrl, string $platform): bool
    {
        // This is a placeholder for actual push notification implementation
        // You would integrate with Firebase Cloud Messaging (FCM) or Apple Push Notification service (APNs)
        
        // For FCM (Firebase Cloud Messaging)
        $serverKey = 'YOUR_FCM_SERVER_KEY'; // Store this in config
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $notification = [
            'title' => $title,
            'body' => $message,
        ];
        
        if ($imageUrl) {
            $notification['image'] = $imageUrl;
        }
        
        $data = [
            'action_url' => $actionUrl
        ];
        
        $payload = [
            'to' => $deviceToken,
            'notification' => $notification,
            'data' => $data
        ];
        
        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // For demo purposes, always return true
        // In production, parse the response and return actual status
        return $httpCode === 200;
    }
}