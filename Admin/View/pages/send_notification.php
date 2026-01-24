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
                                <select class="form-select" name="target_audience" required id="targetAudience">
                                    <option value="all">📱 All Users (Everyone)</option>
                                    <option value="publishers">📚 Publishers (Pro/Premium/Standard/Deluxe)</option>
                                    <option value="customers">🛒 Free Users (Basic customers)</option>
                                    <option value="book_buyers">💰 Book Buyers (Made purchases)</option>
                                    <option value="new_users">🆕 New Users (Registered recently)</option>
                                    <option value="active_users">🔥 Active Users (Login regularly)</option>
                                    <option value="free">🆓 Free Users Only</option>
                                    <option value="pro">⭐ Pro Subscribers</option>
                                    <option value="premium">💎 Premium Subscribers</option>
                                    <option value="standard">📖 Standard Subscribers</option>
                                    <option value="deluxe">👑 Deluxe Subscribers</option>
                                    <option value="inactive_users">😴 Inactive Users (Bring them back)</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle text-info"></i> <strong>Simple:</strong> Sent to ALL app users, but app filters based on their subscription in localStorage.
                                </small>
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
                                <input type="file" class="form-control" name="notification_image" accept="image/*" id="notificationImage">
                                <small class="form-text text-muted">Optional image to display with notification (JPG, PNG, WEBP)</small>
                                
                                <!-- Image Preview -->
                                <div class="mt-3" id="imagePreviewContainer" style="display: none;">
                                    <div class="card" style="max-width: 300px;">
                                        <img id="imagePreview" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Notification preview">
                                        <div class="card-body p-2">
                                            <small class="text-muted">Preview - How it will look in notifications</small>
                                            <button type="button" class="btn btn-sm btn-outline-danger float-end" onclick="clearImagePreview()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                    
                    <!-- Simple approach - no complex criteria needed -->
                    
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
                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                <i class="fas fa-save me-2"></i>Save as Draft
                            </button>
                            <button type="submit" name="action" value="send" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i>Send to All App Users
                            </button>
                            <small class="d-block text-muted mt-2">
                                <i class="fas fa-mobile-alt"></i> Will send to ALL mobile app users. App filters based on target audience.
                            </small>
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
    
    // Simple: Update preview based on target audience selection
    document.getElementById('targetAudience').addEventListener('change', function() {
        const preview = document.querySelector('.notification-preview .notification-title');
        if (preview && this.value !== 'all') {
            preview.textContent = `[${this.options[this.selectedIndex].text}] ${document.querySelector('input[name="title"]').value || 'Notification Title'}`;
        }
    });

    // Image Preview Functionality
    const notificationImageInput = document.getElementById('notificationImage');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    if (notificationImageInput) {
        notificationImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                    
                    // Update mobile preview if it exists
                    const mobilePreviewImage = document.getElementById('previewImage');
                    if (mobilePreviewImage) {
                        mobilePreviewImage.src = e.target.result;
                        document.getElementById('previewImageContainer').style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Clear image preview function
    window.clearImagePreview = function() {
        if (notificationImageInput) {
            notificationImageInput.value = '';
            imagePreviewContainer.style.display = 'none';
            
            // Clear mobile preview if it exists
            const mobilePreviewContainer = document.getElementById('previewImageContainer');
            if (mobilePreviewContainer) {
                mobilePreviewContainer.style.display = 'none';
            }
        }
    };
    
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

<!-- Simple approach - no preview modal needed -->

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>