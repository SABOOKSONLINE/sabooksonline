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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-bell text-primary"></i> Your Notifications</h4>
                    <?php if ($unreadCount > 0): ?>
                        <button class="btn btn-sm btn-outline-secondary" onclick="markAllAsRead()">
                            <i class="fas fa-check-double"></i> Mark All as Read (<?= $unreadCount ?>)
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($notifications)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                            <h5>No notifications yet</h5>
                            <p>You'll see notifications here when they arrive.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-card mb-3 <?= !$notification['read'] ? 'unread' : '' ?>" 
                                 onclick="markAsRead(<?= $notification['id'] ?>, '<?= htmlspecialchars($notification['action_url'] ?? '') ?>')"
                                 style="cursor: <?= $notification['action_url'] ? 'pointer' : 'default' ?>;">
                                <div class="d-flex align-items-start">
                                    <?php if ($notification['image_url']): ?>
                                        <img src="<?= htmlspecialchars($notification['image_url']) ?>" 
                                             class="notification-image me-3" alt="Notification">
                                    <?php else: ?>
                                        <div class="me-3">
                                            <i class="fas fa-bell fa-2x text-primary"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="mb-1"><?= htmlspecialchars($notification['title']) ?></h6>
                                            <?php if (!$notification['read']): ?>
                                                <span class="badge bg-danger">New</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                                        <small class="notification-time">
                                            <i class="fas fa-clock"></i> 
                                            <?= NotificationModel::formatTime($notification['created_at']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-card {
    border: 1px solid #dee2e6;
    border-left: 4px solid #007bff;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.3s ease;
    background: #fff;
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
function markAsRead(notificationId, actionUrl) {
    fetch('/notifications/action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            action: 'mark_read',
            notification_id: notificationId 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh page to show updated state
            if (actionUrl && actionUrl !== '#') {
                window.location.href = actionUrl;
            } else {
                window.location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        // Navigate anyway if there's an action URL
        if (actionUrl && actionUrl !== '#') {
            window.location.href = actionUrl;
        }
    });
}

function markAllAsRead() {
    fetch('/notifications/action', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            action: 'mark_all_read'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and refresh
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 p-3';
            toast.innerHTML = `
                <div class="toast show" role="alert" style="z-index: 9999;">
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
                window.location.reload();
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
        alert('Error updating notifications. Please refresh the page.');
    });
}
</script>

</body>
</html>