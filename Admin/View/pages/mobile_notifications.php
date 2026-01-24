<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Push Notifications";
ob_start();

renderHeading(
    "Push Notifications Management",
    "Send targeted notifications to mobile app users"
);

renderAlerts();

renderSectionHeader(
    "Notifications Overview",
    "Create and manage push notifications for mobile app users"
);
?>

<div class="row mb-4">
    <div class="col-12">
        <a href="/admin/mobile/notifications/send" class="btn btn-primary">
            <i class="fas fa-paper-plane me-2"></i>Send New Notification
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($data["notifications"])): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No notifications sent yet</h5>
                        <p class="text-muted">Send your first push notification to engage with mobile users.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="notificationsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Target</th>
                                    <th>Status</th>
                                    <th>Recipients</th>
                                    <th>Success Rate</th>
                                    <th>Sent Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data["notifications"] as $notification): ?>
                                    <tr>
                                        <td><strong>#<?= $notification['id'] ?></strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($notification['image_url'])): ?>
                                                    <img src="https://www.sabooksonline.co.za/cms-data/notifications/<?= htmlspecialchars($notification['image_url']) ?>" 
                                                         class="me-2 rounded" 
                                                         style="width: 40px; height: 40px; object-fit: cover;" 
                                                         alt="Notification image"
                                                         onclick="showImageModal('https://www.sabooksonline.co.za/cms-data/notifications/<?= htmlspecialchars($notification['image_url']) ?>', '<?= htmlspecialchars($notification['title']) ?>')">
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-bold"><?= htmlspecialchars($notification['title']) ?></div>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars(substr($notification['message'], 0, 50)) ?>...
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $notification['target_type']))) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'scheduled' => 'warning',
                                                'sending' => 'info',
                                                'sent' => 'success',
                                                'failed' => 'danger'
                                            ];
                                            $color = $statusColors[$notification['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>">
                                                <?= htmlspecialchars(ucfirst($notification['status'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($notification['total_recipients'] > 0): ?>
                                                <strong><?= number_format($notification['total_recipients']) ?></strong>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($notification['total_recipients'] > 0): ?>
                                                <?php 
                                                $successRate = ($notification['successful_sends'] / $notification['total_recipients']) * 100;
                                                $color = $successRate >= 90 ? 'success' : ($successRate >= 70 ? 'warning' : 'danger');
                                                ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                        <div class="progress-bar bg-<?= $color ?>" 
                                                             style="width: <?= $successRate ?>%"></div>
                                                    </div>
                                                    <small class="text-<?= $color ?>"><?= number_format($successRate, 1) ?>%</small>
                                                </div>
                                                <small class="text-muted">
                                                    <?= $notification['successful_sends'] ?> / <?= $notification['total_recipients'] ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($notification['sent_at']): ?>
                                                <?= date('M j, Y', strtotime($notification['sent_at'])) ?><br>
                                                <small class="text-muted"><?= date('g:i A', strtotime($notification['sent_at'])) ?></small>
                                            <?php elseif ($notification['scheduled_at']): ?>
                                                <span class="text-warning">Scheduled:</span><br>
                                                <small><?= date('M j, Y g:i A', strtotime($notification['scheduled_at'])) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-info view-notification" 
                                                        data-notification='<?= htmlspecialchars(json_encode($notification)) ?>'>
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?php if ($notification['status'] === 'draft'): ?>
                                                    <button class="btn btn-sm btn-outline-primary edit-notification" 
                                                            data-id="<?= $notification['id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-notification" 
                                                            data-id="<?= $notification['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="notificationDetails">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if available
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#notificationsTable').DataTable({
            "pageLength": 25,
            "order": [[ 0, "desc" ]], // Sort by ID descending (newest first)
            "columnDefs": [
                { "orderable": false, "targets": [7] } // Disable sorting on actions
            ]
        });
    }
    
    // View notification details
    document.querySelectorAll('.view-notification').forEach(btn => {
        btn.addEventListener('click', function() {
            const notification = JSON.parse(this.dataset.notification);
            const modal = document.getElementById('viewNotificationModal');
            const detailsDiv = document.getElementById('notificationDetails');
            
            let targetInfo = '';
            if (notification.target_criteria) {
                try {
                    const criteria = JSON.parse(notification.target_criteria);
                    
                    // Format target criteria in a user-friendly way
                    if (notification.target_type === 'subscription' && criteria.subscription_type) {
                        targetInfo = `<span class="badge bg-info">${criteria.subscription_type.charAt(0).toUpperCase() + criteria.subscription_type.slice(1)} Users</span>`;
                    } else if (notification.target_type === 'specific_users' && criteria.emails) {
                        const emails = Array.isArray(criteria.emails) ? criteria.emails : criteria.emails.split('\n').filter(e => e.trim());
                        targetInfo = `<div class="mb-2"><strong>Specific Users (${emails.length}):</strong></div>`;
                        targetInfo += emails.slice(0, 3).map(email => `<span class="badge bg-secondary me-1">${email.trim()}</span>`).join('');
                        if (emails.length > 3) {
                            targetInfo += `<span class="text-muted">... and ${emails.length - 3} more</span>`;
                        }
                    } else {
                        targetInfo = '<span class="text-muted">No specific criteria</span>';
                    }
                } catch (e) {
                    targetInfo = `<span class="text-muted">${notification.target_criteria}</span>`;
                }
            } else {
                targetInfo = '<span class="text-muted">All users</span>';
            }
            
            detailsDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Information</h6>
                        <p><strong>Title:</strong> ${notification.title}</p>
                        <p><strong>Message:</strong> ${notification.message}</p>
                        <p><strong>Status:</strong> <span class="badge bg-primary">${notification.status}</span></p>
                        <p><strong>Created by:</strong> ${notification.created_by || 'Unknown'}</p>
                        <p><strong>Created:</strong> ${new Date(notification.created_at).toLocaleString()}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Targeting & Links</h6>
                        <p><strong>Target Type:</strong> ${notification.target_type.replace('_', ' ')}</p>
                        ${notification.image_url ? `<p><strong>Image URL:</strong> <a href="${notification.image_url}" target="_blank">${notification.image_url}</a></p>` : ''}
                        ${notification.action_url ? `<p><strong>Action URL:</strong> <a href="${notification.action_url}" target="_blank">${notification.action_url}</a></p>` : ''}
                        ${notification.scheduled_at ? `<p><strong>Scheduled:</strong> ${new Date(notification.scheduled_at).toLocaleString()}</p>` : ''}
                    </div>
                </div>
                
                ${notification.total_recipients > 0 ? `
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6>Delivery Statistics</h6>
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">${notification.total_recipients}</h5>
                                        <p class="card-text small">Total Recipients</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-success">${notification.successful_sends}</h5>
                                        <p class="card-text small">Successful</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-danger">${notification.failed_sends}</h5>
                                        <p class="card-text small">Failed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-info">${notification.stats ? notification.stats.delivered_count || 0 : 0}</h5>
                                        <p class="card-text small">Delivered</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ` : ''}
                
                ${targetInfo ? `
                <hr>
                <h6>Target Criteria</h6>
                ${targetInfo}
                ` : ''}
            `;
            
            new bootstrap.Modal(modal).show();
        });
    });
    
    // Edit notification
    document.querySelectorAll('.edit-notification').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            window.location.href = `/admin/mobile/notifications/edit/${id}`;
        });
    });
    
    // Delete notification
    document.querySelectorAll('.delete-notification').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to delete this notification?')) {
                window.location.href = `/admin/mobile/notifications/delete/${id}`;
            }
        });
    });
});

// Show image modal
function showImageModal(imageUrl, title) {
    const modal = document.getElementById('imagePreviewModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    
    modalImage.src = imageUrl;
    modalTitle.textContent = title || 'Notification Image';
    
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}
</script>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">
                    <i class="fas fa-image text-primary me-2"></i><span id="modalTitle">Notification Image</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" class="img-fluid rounded" style="max-height: 400px;" alt="Notification Image">
                <p class="text-muted mt-2 mb-0">Full size notification image preview</p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>