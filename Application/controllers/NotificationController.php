<?php

session_start();

require_once __DIR__ . "/../models/NotificationModel.php";

class NotificationController
{
    private $notificationModel;
    private $userEmail;

    public function __construct()
    {
        if (!isset($_SESSION['ADMIN_EMAIL'])) {
            header("Location: /login");
            exit;
        }

        $this->userEmail = $_SESSION['ADMIN_EMAIL'];
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Display notifications page
     */
    public function index()
    {
        $notifications = $this->notificationModel->getUserNotifications($this->userEmail);
        $unreadCount = $this->notificationModel->getUnreadCount($this->userEmail);
        
        include __DIR__ . "/../views/notifications_traditional.php";
    }

    /**
     * Handle AJAX requests for notifications
     */
    public function handleAction()
    {
        header('Content-Type: application/json');

        $action = $_GET['action'] ?? $_POST['action'] ?? '';

        switch ($action) {
            case 'get':
                $notifications = $this->notificationModel->getUserNotifications($this->userEmail);
                $unreadCount = $this->notificationModel->getUnreadCount($this->userEmail);
                
                echo json_encode([
                    'success' => true,
                    'notifications' => $notifications,
                    'unread_count' => $unreadCount
                ]);
                break;

            case 'mark_read':
                $input = json_decode(file_get_contents('php://input'), true);
                $notificationId = $input['notification_id'] ?? 0;
                
                if (!$notificationId) {
                    echo json_encode(['success' => false, 'message' => 'Missing notification ID']);
                    break;
                }
                
                $success = $this->notificationModel->markAsRead($notificationId, $this->userEmail);
                echo json_encode(['success' => $success]);
                break;

            case 'mark_all_read':
                $success = $this->notificationModel->markAllAsRead($this->userEmail);
                echo json_encode(['success' => $success]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Unknown action']);
                break;
        }
    }

    /**
     * Get notifications data for navigation dropdown
     */
    public function getNotificationsForNav()
    {
        $notifications = $this->notificationModel->getUserNotifications($this->userEmail, 5);
        $unreadCount = $this->notificationModel->getUnreadCount($this->userEmail);
        
        return [
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ];
    }
}