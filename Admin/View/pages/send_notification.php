<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Send Push Notification";
ob_start();

renderHeading(
    "Send Push Notification",
    "Create and send targeted notifications to mobile app users"
);

renderAlerts();
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-paper-plane me-2"></i>Notification Details
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" id="notificationForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" class="form-control" name="title" required maxlength="255">
                                <small class="form-text text-muted">Keep it concise and attention-grabbing</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Target Audience *</label>
                                <select class="form-select" name="target_type" required id="targetType">
                                    <option value="all">All Users (Everyone with app installed)</option>
                                    <option value="subscription">By Subscription Level</option>
                                    <option value="publishers">Publishers (Pro/Premium/Standard/Deluxe)</option>
                                    <option value="customers">Customers (Free users + Book buyers)</option>
                                    <option value="specific_users">Specific Users (by Email)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea class="form-control" name="message" required rows="4" maxlength="500"></textarea>
                        <small class="form-text text-muted">Maximum 500 characters for optimal display</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Notification Image</label>
                                <input type="file" class="form-control" name="notification_image" accept="image/*">
                                <small class="form-text text-muted">Optional image to display with notification (JPG, PNG, WEBP)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Action URL</label>
                                <input type="url" class="form-control" name="action_url">
                                <small class="form-text text-muted">URL to open when notification is tapped</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Target Criteria (shown conditionally) -->
                    <div id="subscriptionCriteria" class="mb-3" style="display: none;">
                        <label class="form-label">Subscription Type</label>
                        <select class="form-select" name="target_criteria[subscription_type]">
                            <option value="">All Paid Subscriptions (Pro, Premium, Standard, Deluxe)</option>
                            <option value="free">Free Users</option>
                            <option value="pro">Pro Subscribers</option>
                            <option value="premium">Premium Subscribers</option>
                            <option value="standard">Standard Subscribers</option>
                            <option value="deluxe">Deluxe Subscribers</option>
                        </select>
                        <small class="form-text text-muted">
                            <strong>Publishers:</strong> Pro/Premium/Standard/Deluxe users<br>
                            <strong>Customers:</strong> Free users or anyone who made purchases
                        </small>
                    </div>
                    
                    <div id="specificUsersCriteria" class="mb-3" style="display: none;">
                        <label class="form-label">User Emails</label>
                        <textarea class="form-control" name="target_criteria[emails]" rows="3" 
                                  placeholder="Enter email addresses, one per line"></textarea>
                        <small class="form-text text-muted">Enter one email address per line</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Schedule</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="schedule_type" 
                                           value="now" id="scheduleNow" checked>
                                    <label class="form-check-label" for="scheduleNow">
                                        Send Now
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="schedule_type" 
                                           value="later" id="scheduleLater">
                                    <label class="form-check-label" for="scheduleLater">
                                        Schedule for Later
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div id="scheduleDateContainer" class="mt-2" style="display: none;">
                            <input type="datetime-local" class="form-control" name="scheduled_at">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/admin/mobile/notifications" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Notifications
                        </a>
                        <div>
                            <button type="button" class="btn btn-info me-2" onclick="previewRecipients()">
                                <i class="fas fa-eye me-2"></i>Preview Recipients
                            </button>
                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                <i class="fas fa-save me-2"></i>Save as Draft
                            </button>
                            <button type="submit" name="action" value="send" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Notification
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-mobile-alt me-2"></i>Preview
                </h6>
            </div>
            <div class="card-body">
                <div class="notification-preview bg-light rounded p-3">
                    <div class="d-flex align-items-start">
                        <div class="notification-icon me-2">
                            <i class="fas fa-bell text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="notification-title fw-bold" id="previewTitle">
                                Notification Title
                            </div>
                            <div class="notification-message text-muted small" id="previewMessage">
                                Your notification message will appear here...
                            </div>
                            <div class="notification-image mt-2" id="previewImageContainer" style="display: none;">
                                <img id="previewImage" class="img-fluid rounded" style="max-height: 100px;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="small">
                    <h6>Tips for Effective Notifications:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-1"></i> Keep titles under 50 characters</li>
                        <li><i class="fas fa-check text-success me-1"></i> Use clear, actionable language</li>
                        <li><i class="fas fa-check text-success me-1"></i> Include relevant images when possible</li>
                        <li><i class="fas fa-check text-success me-1"></i> Test with different user segments</li>
                        <li><i class="fas fa-check text-success me-1"></i> Schedule during peak usage hours</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('notificationForm');
    const titleInput = form.querySelector('input[name="title"]');
    const messageInput = form.querySelector('textarea[name="message"]');
    const imageInput = form.querySelector('input[name="image_url"]');
    const targetTypeSelect = document.getElementById('targetType');
    const scheduleRadios = form.querySelectorAll('input[name="schedule_type"]');
    
    // Preview elements
    const previewTitle = document.getElementById('previewTitle');
    const previewMessage = document.getElementById('previewMessage');
    const previewImage = document.getElementById('previewImage');
    const previewImageContainer = document.getElementById('previewImageContainer');
    
    // Update preview in real-time
    titleInput.addEventListener('input', function() {
        previewTitle.textContent = this.value || 'Notification Title';
    });
    
    messageInput.addEventListener('input', function() {
        previewMessage.textContent = this.value || 'Your notification message will appear here...';
    });
    
    imageInput.addEventListener('input', function() {
        if (this.value) {
            previewImage.src = this.value;
            previewImageContainer.style.display = 'block';
        } else {
            previewImageContainer.style.display = 'none';
        }
    });
    
    // Handle target type changes
    targetTypeSelect.addEventListener('change', function() {
        // Hide all criteria sections
        document.getElementById('subscriptionCriteria').style.display = 'none';
        document.getElementById('specificUsersCriteria').style.display = 'none';
        
        // Show relevant criteria section
        if (this.value === 'subscription') {
            document.getElementById('subscriptionCriteria').style.display = 'block';
        } else if (this.value === 'specific_users') {
            document.getElementById('specificUsersCriteria').style.display = 'block';
        }
    });
    
    // Handle schedule type changes
    scheduleRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const scheduleDateContainer = document.getElementById('scheduleDateContainer');
            const scheduledAtInput = form.querySelector('input[name="scheduled_at"]');
            
            if (this.value === 'later') {
                scheduleDateContainer.style.display = 'block';
                scheduledAtInput.required = true;
            } else {
                scheduleDateContainer.style.display = 'none';
                scheduledAtInput.required = false;
                scheduledAtInput.value = '';
            }
        });
    });
    
    // Preview Recipients Function
    window.previewRecipients = function() {
        const targetType = document.getElementById('targetType').value;
        const subscriptionType = document.querySelector('select[name="target_criteria[subscription_type]"]')?.value || '';
        const specificEmails = document.querySelector('textarea[name="target_criteria[emails]"]')?.value || '';
        
        // Show loading state
        const modal = new bootstrap.Modal(document.getElementById('recipientPreviewModal'));
        document.getElementById('previewContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading recipients...</div>';
        modal.show();
        
        // Prepare criteria
        const criteria = {};
        if (targetType === 'subscription' && subscriptionType) {
            criteria.subscription_type = subscriptionType;
        } else if (targetType === 'specific_users' && specificEmails) {
            criteria.emails = specificEmails.split('\n').map(email => email.trim()).filter(email => email);
        }
        
        // Make AJAX request to preview recipients
        fetch('/admin/mobile/notifications/preview', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                target_type: targetType,
                criteria: criteria
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRecipientPreview(data.validation);
            } else {
                document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger">Error loading recipients: ' + data.message + '</div>';
            }
        })
        .catch(error => {
            document.getElementById('previewContent').innerHTML = '<div class="alert alert-danger">Error loading recipients: ' + error.message + '</div>';
        });
    };
    
    function displayRecipientPreview(validation) {
        let html = `
            <div class="mb-3">
                <h6><i class="fas fa-bullseye text-primary"></i> Target: <span class="badge bg-primary">${validation.target_type.replace('_', ' ').toUpperCase()}</span></h6>
                <h6><i class="fas fa-users text-success"></i> Total Recipients: <span class="badge bg-success">${validation.total_recipients}</span></h6>
            </div>
        `;
        
        if (validation.warnings && validation.warnings.length > 0) {
            html += '<div class="alert alert-warning"><strong>Warnings:</strong><ul class="mb-0">';
            validation.warnings.forEach(warning => {
                html += `<li>${warning}</li>`;
            });
            html += '</ul></div>';
        }
        
        if (validation.recipients && validation.recipients.length > 0) {
            html += '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Email</th><th>Devices</th><th>Subscription</th></tr></thead><tbody>';
            validation.recipients.forEach(recipient => {
                const subscriptionBadge = recipient.subscription_status ? 
                    `<span class="badge bg-info">${recipient.subscription_status}</span>` : 
                    '<span class="badge bg-secondary">Free</span>';
                html += `
                    <tr>
                        <td>${recipient.email}</td>
                        <td><span class="badge bg-primary">${recipient.devices}</span></td>
                        <td>${subscriptionBadge}</td>
                    </tr>
                `;
            });
            html += '</tbody></table></div>';
        }
        
        document.getElementById('previewContent').innerHTML = html;
    }
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        const action = e.submitter.value;
        
        if (action === 'send') {
            if (!confirm('Are you sure you want to send this notification? This action cannot be undone.')) {
                e.preventDefault();
                return;
            }
        }
        
        // Process specific users emails
        const specificUsersTextarea = form.querySelector('textarea[name="target_criteria[emails]"]');
        if (specificUsersTextarea && specificUsersTextarea.value) {
            const emails = specificUsersTextarea.value.split('\n')
                .map(email => email.trim())
                .filter(email => email.length > 0);
            
            // Create hidden input with JSON array
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'target_criteria[emails]';
            hiddenInput.value = JSON.stringify(emails);
            form.appendChild(hiddenInput);
            
            // Remove the textarea value to avoid conflicts
            specificUsersTextarea.name = 'target_criteria[emails_display]';
        }
    });
    
    // Set minimum datetime to now
    const scheduledAtInput = form.querySelector('input[name="scheduled_at"]');
    if (scheduledAtInput) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        scheduledAtInput.min = now.toISOString().slice(0, 16);
    }
});
</script>

<style>
.notification-preview {
    border: 1px solid #dee2e6;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.notification-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-title {
    font-size: 14px;
    line-height: 1.2;
}

.notification-message {
    font-size: 12px;
    line-height: 1.3;
    margin-top: 2px;
}
</style>

<!-- Recipient Preview Modal -->
<div class="modal fade" id="recipientPreviewModal" tabindex="-1" aria-labelledby="recipientPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recipientPreviewModalLabel">
                    <i class="fas fa-eye text-primary"></i> Notification Recipients Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>