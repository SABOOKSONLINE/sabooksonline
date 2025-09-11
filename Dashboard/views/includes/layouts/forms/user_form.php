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

if (isset($userKey)) {
    if (!empty($adminProfileImage)) {
        if (strpos($adminProfileImage, 'vecteezy.com/free-vector/default-profile-picture') !== false) {
            $profile = "/public/images/user-3296.png";
        } elseif (strpos($adminProfileImage, 'googleusercontent.com') !== false) {
            $profile = $adminProfileImage;
        } elseif (strpos($adminProfileImage, 'http') === 0) {
            $profile = $adminProfileImage;
        } else {
            $profile = "/cms-data/profile-images/" . ltrim($adminProfileImage, '/');
        }
    } else {
        $profile = "/public/images/user-3296.png";
    }
} else {
    header("Location: /login");
    exit;
}
?>

<form method="POST"
    action="/dashboards/profile/update<?= $adminId ? "/$adminId" : "" ?>"
    enctype="multipart/form-data"
    class="bg-white rounded shadow-sm p-4 mb-4">
    <div class="row">
        <div class="mb-3">
            <h5 class="fw-bold">Profile Information</h5>
        </div>

        <input type="hidden" name="ADMIN_ID" value="<?= $adminId ?>">

        <div class="col-sm-6">
            <div class="rounded-circle p-0" style="width: 70px; height: 70px;">
                <img src="<?= $profile ?>" alt="Admin Profile"
                    class="rounded-circle"
                    style="width: 100%; height: 100%; object-fit: cover;
                border: 2px solid #dee2e6;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label"> Profile Image <span class="text-danger">*</span></label>
                <input type="hidden" name="existing_profile" value="<?= $adminProfileImage ?>">
                <input type="file" name="ADMIN_PROFILE_IMAGE" class="form-control" accept="image/*" <?= empty($adminProfileImage) ? 'required' : '' ?>>
            </div>
        </div>

        <!-- Name -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ADMIN_NAME" value="<?= $adminName ?>" id="name" required>
            </div>
        </div>

        <!-- Email -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold d-flex align-content-center justify-content-between">Email <small class="text-danger">(Read Only)</small></label>
                <input type="email" class="form-control text-muted bg-light" name="ADMIN_EMAIL" value="<?= $adminEmail ?>" readonly>
            </div>
        </div>

        <!-- Phone -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">Phone Number</label>
                <input type="text" class="form-control" name="ADMIN_NUMBER" value="<?= $adminNumber ?>">
            </div>
        </div>

        <!-- Website URl -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">Website</label>
                <input type="text" class="form-control" name="ADMIN_WEBSITE" value="<?= $adminWebsite ?>">
            </div>
        </div>

        <!-- Physical -->
        <!-- <div class="col-sm-12">
            <div class="mb-3">
                <label class="form-label fw-semibold">Physical Address</label>
                <input type="text" class="form-control" name="ADMIN_ADDRESS" value="<?= $adminAddress ?>">
            </div>
        </div> -->

        <!-- Author -->
        <div class="col-sm-12">
            <div class="mb-3">
                <label class="form-label fw-semibold">Author Bio</label>
                <textarea type="text" class="form-control" rows="6" maxlength="600" name="ADMIN_BIO"><?= $adminBio ?></textarea>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="row">
        <div class="mb-3">
            <h5 class="fw-bold">Social Media Links</h5>
        </div>

        <!-- Facebook -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">Facebook Link</label>
                <input type="text" class="form-control" name="ADMIN_FACEBOOK" value="<?= $adminFacebook ?>">
            </div>
        </div>

        <!-- Instagram -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">Instagram Link</label>
                <input type="text" class="form-control" name="ADMIN_INSTAGRAM" value="<?= $adminInstagram ?>">
            </div>
        </div>

        <!-- Twitter -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">Twitter Link</label>
                <input type="text" class="form-control" name="ADMIN_TWITTER" value="<?= $adminTwitter ?>">
            </div>
        </div>

        <!-- Linkedin -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">LinkedIn Link</label>
                <input type="text" class="form-control" name="ADMIN_LINKEDIN" value="<?= $adminLinkedin ?>">
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="row">
        <div class="mb-3">
            <h5 class="fw-bold">Change Password</h5>
        </div>

        <!-- New Password -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">New Password</label>
                <input type="password" class="form-control" name="ADMIN_PASSWORD" id="new_password" autocomplete="new-password">
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="col-sm-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">Confirm Password</label>
                <input type="password" class="form-control" name="CONFIRM_PASSWORD" id="confirm_password" autocomplete="condirm-password">
            </div>
        </div>
    </div>

    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-success" id="save_button">Update Profile</button>
    </div>
</form>


<script>
    const name = document.querySelector("#name");
    const newPassword = document.querySelector("#new_password");
    const confirmPassword = document.querySelector("#confirm_password");
    const saveButton = document.querySelector("#save_button");

    const disableSaveButton = () => {
        saveButton.classList.remove("btn-success");
        saveButton.classList.add("btn-secondary");
        confirmPassword.classList.add("border-danger");
        saveButton.disabled = true;
    }

    const enableSaveButton = () => {
        saveButton.classList.remove("btn-secondary");
        saveButton.classList.add("btn-success");
        confirmPassword.classList.remove("border-danger");
        saveButton.disabled = false;
    }

    const isInputValid = () => {
        if (newPassword.value !== confirmPassword.value) {
            disableSaveButton();
        } else {
            enableSaveButton();
        }
    }

    newPassword.addEventListener("input", () => {
        isInputValid();
    });

    confirmPassword.addEventListener("input", () => {
        isInputValid();
    })
</script>