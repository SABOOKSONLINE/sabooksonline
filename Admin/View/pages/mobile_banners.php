<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Mobile App Banners";
ob_start();

renderHeading(
    "Mobile App Banner Management",
    "Manage banners displayed in the mobile application"
);

renderAlerts();

renderSectionHeader(
    "Mobile Banners Overview",
    "Create and manage banners for different mobile app screens"
);
?>

<div class="row mb-4">
    <div class="col-12">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
            <i class="fas fa-plus me-2"></i>Add New Mobile Banner
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($data["banners"])): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-mobile-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No mobile banners yet</h5>
                        <p class="text-muted">Create your first mobile banner to get started.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="bannersTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Preview</th>
                                    <th>Title</th>
                                    <th>Screen</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Schedule</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data["banners"] as $banner): ?>
                                    <tr>
                                        <td><strong>#<?= $banner['id'] ?></strong></td>
                                        <td>
                                            <?php if (!empty($banner['image'])): ?>
                                                <img src="https://www.sabooksonline.co.za/cms-data/banners/<?= htmlspecialchars($banner['image']) ?>" 
                                                     alt="Banner" class="img-thumbnail" 
                                                     style="width: 60px; height: 40px; object-fit: cover; cursor: pointer;" 
                                                     onclick="showImageModal('https://www.sabooksonline.co.za/cms-data/banners/<?= htmlspecialchars($banner['image']) ?>', '<?= htmlspecialchars($banner['title'] ?? 'Banner') ?>')">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 40px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($banner['title'] ?? '') ?></div>
                                            <?php if (!empty($banner['description'])): ?>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars(substr($banner['description'], 0, 50)) ?>...
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= htmlspecialchars(ucfirst($banner['screen'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= $banner['priority'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $now = new DateTime();
                                            $startDate = new DateTime($banner['start_date']);
                                            $endDate = $banner['end_date'] ? new DateTime($banner['end_date']) : null;
                                            
                                            $isActive = ($banner['is_active'] ?? 0) && 
                                                       $startDate <= $now && 
                                                       (!$endDate || $endDate >= $now);
                                            ?>
                                            <span class="badge bg-<?= $isActive ? 'success' : 'secondary' ?>">
                                                <?= $isActive ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                <strong>Start:</strong> <?= date('M j, Y', strtotime($banner['start_date'])) ?><br>
                                                <?php if ($banner['end_date']): ?>
                                                    <strong>End:</strong> <?= date('M j, Y', strtotime($banner['end_date'])) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">No end date</span>
                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-info view-banner-details" 
                                                        data-banner='<?= htmlspecialchars(json_encode($banner)) ?>'
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#bannerDetailsModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-primary edit-banner" 
                                                        data-banner='<?= htmlspecialchars(json_encode($banner)) ?>'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning toggle-banner" 
                                                        data-id="<?= $banner['id'] ?>">
                                                    <i class="fas fa-<?= $banner['is_active'] ? 'pause' : 'play' ?>"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-banner" 
                                                        data-id="<?= $banner['id'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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

<!-- Add/Edit Banner Modal -->
<div class="modal fade" id="addBannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Mobile Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bannerForm" method="POST" action="/admin/mobile/banners" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Screen *</label>
                                <select class="form-select" name="screen" required>
                                    <option value="home">Home</option>
                                    <option value="books">Books</option>
                                    <option value="profile">Profile</option>
                                    <option value="cart">Cart</option>
                                    <option value="search">Search</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    
                        <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Banner Image *</label>
                                <input type="file" class="form-control" name="banner_image" accept="image/*" required>
                                <small class="form-text text-muted">Upload JPG, PNG, or WEBP image (max 5MB)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Action URL</label>
                                <input type="url" class="form-control" name="action_url">
                                <small class="form-text text-muted">URL to open when banner is tapped</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <input type="number" class="form-control" name="priority" value="0" min="0" max="100">
                                <small class="form-text text-muted">Higher numbers appear first</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control" name="end_date">
                                <small class="form-text text-muted">Leave empty for no expiry</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Banner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Banner Details Modal -->
<div class="modal fade" id="bannerDetailsModal" tabindex="-1" aria-labelledby="bannerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannerDetailsModalLabel">
                    <i class="fas fa-mobile-alt me-2"></i>Banner Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Banner Image -->
                    <div class="col-md-5">
                        <h6 class="fw-bold mb-3"><i class="fas fa-image me-2"></i>Banner Preview</h6>
                        <div class="text-center mb-3">
                            <img id="modalBannerImage" src="" alt="Banner Image" 
                                 class="img-fluid rounded shadow" style="max-height: 200px; max-width: 100%;">
                        </div>
                        <div class="text-center">
                            <small class="text-muted">Mobile App Preview</small>
                        </div>
                    </div>
                    
                    <!-- Banner Information -->
                    <div class="col-md-7">
                        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Banner Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td class="fw-bold"><i class="fas fa-tag me-2"></i>Title:</td>
                                <td id="modalBannerTitle">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-align-left me-2"></i>Description:</td>
                                <td id="modalBannerDescription">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-mobile-alt me-2"></i>Screen:</td>
                                <td><span id="modalBannerScreen" class="badge bg-info">-</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-sort-numeric-up me-2"></i>Priority:</td>
                                <td><span id="modalBannerPriority" class="badge bg-secondary">-</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-toggle-on me-2"></i>Status:</td>
                                <td><span id="modalBannerStatus" class="badge">-</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-link me-2"></i>Action URL:</td>
                                <td id="modalBannerActionUrl">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-calendar me-2"></i>Start Date:</td>
                                <td id="modalBannerStartDate">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-calendar-times me-2"></i>End Date:</td>
                                <td id="modalBannerEndDate">-</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="fas fa-hashtag me-2"></i>Banner ID:</td>
                                <td><span id="modalBannerId" class="font-monospace">-</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if available
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#bannersTable').DataTable({
            "pageLength": 25,
            "order": [[ 4, "desc" ]], // Sort by priority
            "columnDefs": [
                { "orderable": false, "targets": [1, 7] } // Disable sorting on preview and actions
            ]
        });
    }
    
    // View banner details functionality
    document.querySelectorAll('.view-banner-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const banner = JSON.parse(this.dataset.banner);
            
            // Banner Image
            const bannerImage = banner.image_url ? 
                `/cms-data/banners/${banner.image_url}` : 
                '/img/lazy-placeholder.jpg';
            document.getElementById('modalBannerImage').src = bannerImage;
            
            // Banner Information
            document.getElementById('modalBannerTitle').textContent = banner.title || 'No Title';
            document.getElementById('modalBannerDescription').textContent = banner.description || 'No Description';
            document.getElementById('modalBannerScreen').textContent = (banner.screen || 'home').charAt(0).toUpperCase() + (banner.screen || 'home').slice(1);
            document.getElementById('modalBannerPriority').textContent = banner.priority || '0';
            document.getElementById('modalBannerId').textContent = `#${banner.id}`;
            
            // Status Badge
            const now = new Date();
            const startDate = new Date(banner.start_date);
            const endDate = banner.end_date ? new Date(banner.end_date) : null;
            const isActive = (banner.is_active || 1) && startDate <= now && (!endDate || endDate >= now);
            
            const statusBadge = document.getElementById('modalBannerStatus');
            statusBadge.textContent = isActive ? 'Active' : 'Inactive';
            statusBadge.className = `badge ${isActive ? 'bg-success' : 'bg-secondary'}`;
            
            // Action URL
            const actionUrlElement = document.getElementById('modalBannerActionUrl');
            if (banner.action_url) {
                actionUrlElement.innerHTML = `<a href="${banner.action_url}" target="_blank" class="text-decoration-none">${banner.action_url} <i class="fas fa-external-link-alt"></i></a>`;
            } else {
                actionUrlElement.textContent = 'No Action URL';
            }
            
            // Dates
            document.getElementById('modalBannerStartDate').textContent = new Date(banner.start_date).toLocaleString();
            document.getElementById('modalBannerEndDate').textContent = banner.end_date ? new Date(banner.end_date).toLocaleString() : 'No End Date';
        });
    });

    // Edit banner functionality
    document.querySelectorAll('.edit-banner').forEach(btn => {
        btn.addEventListener('click', function() {
            const banner = JSON.parse(this.dataset.banner);
            const form = document.getElementById('bannerForm');
            const modal = document.getElementById('addBannerModal');
            
            // Populate form
            form.title.value = banner.title;
            form.description.value = banner.description || '';
            form.action_url.value = banner.action_url || '';
            form.screen.value = banner.screen;
            form.priority.value = banner.priority;
            form.start_date.value = banner.start_date.replace(' ', 'T');
            form.end_date.value = banner.end_date ? banner.end_date.replace(' ', 'T') : '';
            form.is_active.checked = banner.is_active == 1;
            
            // Change modal title and form action
            modal.querySelector('.modal-title').textContent = 'Edit Mobile Banner';
            form.action = `/admin/mobile/banners/edit/${banner.id}`;
            
            // Show modal
            new bootstrap.Modal(modal).show();
        });
    });
    
    // Reset form when modal is closed
    document.getElementById('addBannerModal').addEventListener('hidden.bs.modal', function() {
        const form = document.getElementById('bannerForm');
        form.reset();
        form.action = '/admin/mobile/banners/add';
        this.querySelector('.modal-title').textContent = 'Add Mobile Banner';
    });
    
    // Toggle banner status
    document.querySelectorAll('.toggle-banner').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Toggle banner status?')) {
                window.location.href = `/admin/mobile/banners/toggle/${id}`;
            }
        });
    });
    
    // Delete banner
    document.querySelectorAll('.delete-banner').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to delete this banner?')) {
                window.location.href = `/admin/mobile/banners/delete/${id}`;
            }
        });
    });
    
    // Set default start date to now
    const startDateInput = document.querySelector('input[name="start_date"]');
    if (startDateInput && !startDateInput.value) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        startDateInput.value = now.toISOString().slice(0, 16);
    }
});

// Show image modal for banners
function showImageModal(imageUrl, title) {
    const modal = document.getElementById('imagePreviewModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    
    modalImage.src = imageUrl;
    modalTitle.textContent = title || 'Banner Image';
    
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
                    <i class="fas fa-image text-primary me-2"></i><span id="modalTitle">Banner Image</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" class="img-fluid rounded" style="max-height: 400px;" alt="Banner Image">
                <p class="text-muted mt-2 mb-0">Full size banner image preview</p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>