<?php
session_start();

if (!isset($_SESSION['ADMIN_EMAIL'])) {
    header("Location: /login");
    exit;
}

$userEmail = $_SESSION['ADMIN_EMAIL'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - SA Books Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . "/includes/nav.php"; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-bell text-primary"></i> Your Notifications</h4>
                    <button class="btn btn-sm btn-outline-secondary" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i> Mark All as Read
                    </button>
                </div>
                <div class="card-body">
                    <div id="notificationsContainer">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading notifications...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-card {
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.notification-card.unread {
    background-color: #f8f9ff;
    border-left-color: #dc3545;
}

.notification-card:hover {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.notification-time {
    color: #6c757d;
    font-size: 0.875rem;
}

.notification-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let allNotifications = [];

document.addEventListener('DOMContentLoaded', function() {
    loadAllNotifications();
});

function loadAllNotifications() {
    fetch('/api/user/notifications')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allNotifications = data.notifications || [];
                displayNotifications();
            } else {
                document.getElementById('notificationsContainer').innerHTML = `
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Unable to load notifications at this time.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notificationsContainer').innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle"></i> 
                    Error loading notifications. Please refresh the page.
                </div>
            `;
        });
}

function displayNotifications() {
    const container = document.getElementById('notificationsContainer');
    
    if (allNotifications.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                <h5>No notifications yet</h5>
                <p>You'll see notifications here when they arrive.</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = allNotifications.map(notification => `
        <div class="card notification-card mb-3 ${!notification.read ? 'unread' : ''}" 
             onclick="markAsRead(${notification.id}, '${notification.action_url || ''}')"
             style="cursor: ${notification.action_url ? 'pointer' : 'default'};">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    ${notification.image_url ? `
                        <img src="${notification.image_url}" class="notification-image me-3" alt="Notification">
                    ` : `
                        <div class="me-3">
                            <i class="fas fa-bell fa-2x text-primary"></i>
                        </div>
                    `}
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="mb-1">${notification.title}</h6>
                            ${!notification.read ? '<span class="badge bg-danger">New</span>' : ''}
                        </div>
                        <p class="mb-1">${notification.message}</p>
                        <small class="notification-time">
                            <i class="fas fa-clock"></i> ${formatTime(notification.created_at)}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function markAsRead(notificationId, actionUrl) {
    fetch('/api/user/notifications/read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notification_id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update local notification status
            const notification = allNotifications.find(n => n.id === notificationId);
            if (notification) {
                notification.read = true;
                displayNotifications();
            }
            
            // Navigate to action URL if provided
            if (actionUrl && actionUrl !== '#') {
                window.location.href = actionUrl;
            }
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

function markAllAsRead() {
    fetch('/api/user/notifications/read-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all notifications as read
            allNotifications.forEach(n => n.read = true);
            displayNotifications();
            
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'toast-container position-fixed top-0 end-0 p-3';
            toast.innerHTML = `
                <div class="toast show" role="alert">
                    <div class="toast-header">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong class="me-auto">Success</strong>
                    </div>
                    <div class="toast-body">
                        All notifications marked as read!
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 3000);
        }
    })
    .catch(error => console.error('Error marking all notifications as read:', error));
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    const minutes = Math.floor(diff / (1000 * 60));
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    
    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    if (hours < 24) return `${hours}h ago`;
    if (days < 7) return `${days}d ago`;
    
    return date.toLocaleDateString();
}
</script>

</body>
</html>