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
                                <input type="text" class="form-control" name="title" required maxlength="255" placeholder="Enter notification title">
                                <small class="form-text text-muted">Keep it concise and attention-grabbing</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Target Audience *</label>
                                <select class="form-select" name="target_audience" required id="targetAudience">
                                    <option value="all">All Users (Everyone)</option>
                                    <option value="publishers">Publishers (Pro/Premium/Standard/Deluxe)</option>
                                    <option value="customers">Free Users (Basic customers)</option>
                                    <option value="book_buyers">Book Buyers (Made purchases)</option>
                                    <option value="new_users">New Users (Registered recently)</option>
                                    <option value="active_users">Active Users (Login regularly)</option>
                                    <option value="free">Free Users Only</option>
                                    <option value="pro">Pro Subscribers</option>
                                    <option value="premium">Premium Subscribers</option>
                                    <option value="standard">Standard Subscribers</option>
                                    <option value="deluxe">Deluxe Subscribers</option>
                                    <option value="inactive_users">Inactive Users (Bring them back)</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle text-info"></i> <strong>Simple:</strong> Sent to ALL app users, but app filters based on their subscription in localStorage.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Message *</label>
                        <textarea class="form-control" name="message" required rows="4" maxlength="500" placeholder="Enter your notification message here..."></textarea>
                        <small class="form-text text-muted">Maximum 500 characters for optimal display</small>
                    </div>
                    
                    <div class="row mb-4">
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
                                <input type="url" class="form-control" name="action_url" placeholder="https://example.com/page">
                                <small class="form-text text-muted">URL to open when notification is tapped</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Simple approach - no complex criteria needed -->
                    
                    <div class="mb-4">
                        <label class="form-label">Scheduling Options</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="schedule_type" 
                                                   value="now" id="scheduleNow" checked>
                                            <label class="form-check-label" for="scheduleNow">
                                                <strong>Send Immediately</strong>
                                                <br><small class="text-muted">Notification will be sent right away</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="schedule_type" 
                                                   value="later" id="scheduleLater">
                                            <label class="form-check-label" for="scheduleLater">
                                                <strong>Schedule for Later</strong>
                                                <br><small class="text-muted">Choose a specific date and time</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="scheduleDateContainer" class="mt-3" style="display: none;">
                                    <label class="form-label">Select Date & Time</label>
                                    <input type="datetime-local" class="form-control" name="scheduled_at">
                                    <small class="form-text text-muted">Choose when to send this notification</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Back Button -->
                                        <div class="col-md-3">
                                            <a href="/admin/mobile/notifications" class="btn btn-outline-secondary w-100">
                                                <i class="fas fa-arrow-left me-2"></i>Back to List
                                            </a>
                                        </div>
                                        
                                        <!-- Preview Button -->
                                        <div class="col-md-3 mt-2 mt-md-0">
                                            <button type="button" class="btn btn-outline-info w-100" onclick="previewRecipients()">
                                                <i class="fas fa-users me-2"></i>Preview Recipients
                                            </button>
                                        </div>
                                        
                                        <!-- Save Draft Button -->
                                        <div class="col-md-3 mt-2 mt-md-0">
                                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary w-100">
                                                <i class="fas fa-save me-2"></i>Save Draft
                                            </button>
                                        </div>
                                        
                                        <!-- Send Button -->
                                        <div class="col-md-3 mt-2 mt-md-0">
                                            <button type="submit" name="action" value="send" class="btn btn-success w-100">
                                                <i class="fas fa-paper-plane me-2"></i>Send Notification
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Info Text -->
                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle text-info"></i> 
                                                Notification will be sent to all mobile users, but only <strong id="targetText">selected audience</strong> will see it based on their app settings.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
// Global functions first (outside DOMContentLoaded)
window.previewRecipients = function() {
    console.log('🎯 Preview Recipients called');
    const targetAudience = document.getElementById('targetAudience').value;
    const targetText = document.getElementById('targetAudience').options[document.getElementById('targetAudience').selectedIndex].text;
    
    console.log('🎯 Target data:', { targetAudience, targetText });
    
    // Show modal and loading state
    const modal = new bootstrap.Modal(document.getElementById('recipientPreviewModal'));
    document.getElementById('previewContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Analyzing users who match "${targetText}"...</p>
        </div>
    `;
    modal.show();
    
    // Make AJAX request to preview recipients
    console.log('🌐 Making API request to:', '/admin/mobile/notifications/preview');
    
    fetch('/admin/mobile/notifications/preview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            target_audience: targetAudience
        })
    })
    .then(response => {
        console.log('📡 API Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('✅ API Response data:', data);
        
        if (data.success) {
            displayRecipientPreview(data);
        } else {
            document.getElementById('previewContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error: ${data.message || 'Unknown error'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('❌ API Request failed:', error);
        document.getElementById('previewContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> Network error: ${error.message}
                <br><small class="text-muted">Check browser console for details</small>
            </div>
        `;
    });
};

function displayRecipientPreview(data) {
    const { target_audience, matching_users, total_app_users, summary } = data;
    
    let html = `
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">${total_app_users}</h3>
                        <small>Total App Users</small>
                        <div class="mt-1"><i class="fas fa-mobile-alt"></i> Will receive push</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">${matching_users.length}</h3>
                        <small>Will Actually See It</small>
                        <div class="mt-1"><i class="fas fa-eye"></i> Matches target</div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    if (summary && summary.length > 0) {
        html += '<div class="mb-3"><h6><i class="fas fa-chart-pie text-info"></i> Breakdown:</h6>';
        summary.forEach(item => {
            html += `<span class="badge bg-info me-2">${item.label}: ${item.count}</span>`;
        });
        html += '</div>';
    }
    
    if (matching_users.length > 0) {
        html += `
            <h6><i class="fas fa-users text-success"></i> Users Who Will See This Notification:</h6>
            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Subscription</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        matching_users.forEach(user => {
            const subscriptionBadge = user.subscription ? 
                `<span class="badge bg-primary">${user.subscription}</span>` : 
                '<span class="badge bg-secondary">Free</span>';
                
            const statusBadge = user.has_app ? 
                '<span class="badge bg-success">Has App</span>' : 
                '<span class="badge bg-warning">Web Only</span>';
                
            html += `
                <tr>
                    <td>${user.email}</td>
                    <td>${subscriptionBadge}</td>
                    <td>${statusBadge}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
    } else {
        html += `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>No users match this target audience!</strong><br>
                Consider selecting a different audience or wait for more users to join.
            </div>
        `;
    }
    
    document.getElementById('previewContent').innerHTML = html;
}

// Clear image preview function
window.clearImagePreview = function() {
    const notificationImageInput = document.getElementById('notificationImage');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    
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

// DOM Ready code
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('notificationForm');
    if (!form) {
        console.error('❌ Notification form not found');
        return;
    }
    
    const titleInput = form.querySelector('input[name="title"]');
    const messageInput = form.querySelector('textarea[name="message"]');
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
    
    // Update target text and preview based on target audience selection
    const targetAudienceSelect = document.getElementById('targetAudience');
    const targetText = document.getElementById('targetText');
    
    if (targetAudienceSelect && targetText) {
        targetAudienceSelect.addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].text;
            targetText.textContent = selectedText.toLowerCase();
            
            const preview = document.querySelector('.notification-preview .notification-title');
            if (preview && this.value !== 'all') {
                const titleInput = document.querySelector('input[name="title"]');
                const titleValue = titleInput ? titleInput.value || 'Notification Title' : 'Notification Title';
                preview.textContent = `[${selectedText}] ${titleValue}`;
            }
        });
    }

    // Image Preview Functionality
    const notificationImageInput = document.getElementById('notificationImage');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    if (notificationImageInput && imagePreview && imagePreviewContainer) {
        notificationImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                    
                    // Update mobile preview if it exists
                    const mobilePreviewImage = document.getElementById('previewImage');
                    const mobilePreviewContainer = document.getElementById('previewImageContainer');
                    if (mobilePreviewImage && mobilePreviewContainer) {
                        mobilePreviewImage.src = e.target.result;
                        mobilePreviewContainer.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Handle schedule type changes
    if (scheduleRadios.length > 0) {
        scheduleRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const scheduleDateContainer = document.getElementById('scheduleDateContainer');
                const scheduledAtInput = form.querySelector('input[name="scheduled_at"]');
                
                if (scheduleDateContainer && scheduledAtInput) {
                    if (this.value === 'later') {
                        scheduleDateContainer.style.display = 'block';
                        scheduledAtInput.required = true;
                    } else {
                        scheduleDateContainer.style.display = 'none';
                        scheduledAtInput.required = false;
                        scheduledAtInput.value = '';
                    }
                }
            });
        });
    }
    
    // Form submission handling
    if (form) {
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
    } // Close form if statement
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

/* Form styling improvements */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn {
    font-weight: 500;
    border-radius: 0.375rem;
}

.btn-outline-secondary:hover {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-info:hover {
    color: #fff;
    background-color: #0dcaf0;
    border-color: #0dcaf0;
}

.btn-outline-primary:hover {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

/* Form section spacing */
.mb-4 {
    margin-bottom: 2rem !important;
}

/* Radio button styling */
.form-check {
    padding-left: 1.5em;
}

.form-check-input {
    margin-top: 0.25rem;
}

.form-check-label {
    cursor: pointer;
}

/* Action buttons area */
.card.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
}
</style>

<!-- Preview Recipients Modal -->
<div class="modal fade" id="recipientPreviewModal" tabindex="-1" aria-labelledby="recipientPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recipientPreviewModalLabel">
                    <i class="fas fa-users text-primary"></i> Who Will See This Notification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>How it works:</strong> Notification sent to ALL mobile app users, but only users matching your target audience will see it (client-side filtering).
                </div>
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