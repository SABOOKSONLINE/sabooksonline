<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = $user ?? [];

$adminId = htmlspecialchars($user['ADMIN_ID'] ?? '');
$adminName = htmlspecialchars($user['ADMIN_NAME'] ?? '');
$adminEmail = htmlspecialchars($user['ADMIN_EMAIL'] ?? '');
$adminNumber = htmlspecialchars($user['ADMIN_NUMBER'] ?? '');
$adminPassword = htmlspecialchars($user['ADMIN_PASSWORD'] ?? '');
$adminType = htmlspecialchars($user['ADMIN_TYPE'] ?? '');
$adminStatus = htmlspecialchars($user['ADMIN_USER_STATUS'] ?? 'inactive');
$adminDate = htmlspecialchars($user['ADMIN_DATE'] ?? date('Y-m-d'));

$adminWebsite = htmlspecialchars($user['ADMIN_WEBSITE'] ?? '');
$adminAddress = htmlspecialchars($user['ADMIN_GOOGLE'] ?? '');
$adminBio = htmlspecialchars($user['ADMIN_BIO'] ?? '');
$adminProfileImage = htmlspecialchars($user['ADMIN_PROFILE_IMAGE'] ?? '');


$adminFacebook = htmlspecialchars($user['ADMIN_FACEBOOK'] ?? '');
$adminInstagram = htmlspecialchars($user['ADMIN_INSTAGRAM'] ?? '');
$adminTwitter = htmlspecialchars($user['ADMIN_TWITTER'] ?? '');
$adminLinkedin = htmlspecialchars($user['ADMIN_LINKEDIN'] ?? '');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userKey = $_SESSION['ADMIN_USERKEY'] ?? "";

if (!empty($userKey)) {
    // $adminProfileImage = $_SESSION['ADMIN_PROFILE_IMAGE'] ?? "";

    if (!empty($adminProfileImage)) {
        if (strpos($adminProfileImage, 'googleusercontent.com') === false) {
            // Not a Google image, so prefix with sabooks URL
            $profile = "/cms-data/profile-images/" . ltrim($adminProfileImage, '/');

        } else {
            // Google image, use as is
            $profile = $adminProfileImage;
        }
    } else {
        // No image set
        $profile = "/public/images/user-3296.png";
    }
} else {
    // No admin logged in
    $profile = null;
}


?>

<form method="POST"
    action="/dashboards/profile/update<?= $adminId ? "/$adminId" : "" ?>"
    class="bg-white rounded mb-4 overflow-hidden position-relative">
    <div class="card border-0 shadow-sm p-4 mb-3">
        <div class="row">
            <div class="border-bottom pb-3 mb-4">
                <h5 class="fw-bold">Profile Information</h5>
            </div>

            <input type="hidden" name="ADMIN_ID" value="<?= $adminId ?>">


            <div class="col-sm-6">
            <button class="btn btn-outline-secondary rounded-circle p-0 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 70px; height: 70px;">
                <img src="<?= $profile ?>" alt="Admin Profile"
                class="rounded-circle"
                style="width: 100%; height: 100%; object-fit: cover;
                border: 2px solid #dee2e6;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            </button>
            </div>

            <div class="col-md-6">
            <label class="form-label"> Profile Image <span class="text-danger">*</span></label>
            <input type="hidden" name="existing_profile" value="<?= $adminProfileImage?>">
            <input type="file" name="profile" class="form-control" accept="image/*" <?= empty($adminProfileImage) ? 'required' : '' ?>>
            </div>


            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name*</label>
                    <input type="text" class="form-control" name="ADMIN_NAME" value="<?= $adminName ?>" required>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold d-flex align-content-center justify-content-between">Email <small class="text-danger">(Read Only)</small></label>
                    <input type="email" class="form-control text-muted bg-light" name="ADMIN_EMAIL" value="<?= $adminEmail ?>" readonly>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Phone Number</label>
                    <input type="text" class="form-control" name="ADMIN_NUMBER" value="<?= $adminNumber ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Website</label>
                    <input type="text" class="form-control" name="ADMIN_WEBSITE" value="<?= $adminWebsite ?>">
                </div>
            </div>

            <div class="col-sm-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Physical Address</label>
                    <input type="text" class="form-control" name="ADMIN_ADDRESS" value="<?= $adminAddress ?>">
                </div>
            </div>

            <div class="col-sm-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Author Bio</label>
                    <textarea type="text" class="form-control" rows="6" maxlength="600" name="ADMIN_BIO"><?= $adminBio ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-3">
        <div class="row">
            <div class="border-bottom pb-3 mb-4">
                <h5 class="fw-bold">Social Media Links</h5>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Facebook Link</label>
                    <input type="text" class="form-control" name="ADMIN_FACEBOOK" value="<?= $adminFacebook ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Instagram Link</label>
                    <input type="text" class="form-control" name="ADMIN_INSTAGRAM" value="<?= $adminInstagram ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Twitter Link</label>
                    <input type="text" class="form-control" name="ADMIN_TWITTER" value="<?= $adminTwitter ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">LinkedIn Link</label>
                    <input type="text" class="form-control" name="ADMIN_LINKEDIN" value="<?= $adminLinkedin ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-3">
        <div class="row">
            <div class="border-bottom pb-3 mb-4">
                <h5 class="fw-bold">Change Password</h5>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" class="form-control" name="ADMIN_PASSWORD">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" class="form-control" name="CONFIRM_PASSWORD">
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-success">Update Profile</button>
    </div>
</form>