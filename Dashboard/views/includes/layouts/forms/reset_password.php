<?php
if (isset($_SESSION['alert'])) {
    $type = $_SESSION['alert']['type'];
    $message = $_SESSION['alert']['message'];

    echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
            $message
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";

    unset($_SESSION['alert']);
}

if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            {$_SESSION['success']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['danger'])) {
    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            {$_SESSION['danger']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    unset($_SESSION['danger']);
}
?>

<form method="POST" action="/dashboards/reset_password/handler/<?= $_SESSION['ADMIN_ID'] ?>" class="bg-white rounded mb-4 position-relative" enctype="multipart/form-data">
    <div class="card border-0 shadow-sm p-4 mb-2">
        <div class="mb-2">
            <h5 class="fw-bold">User Password Details</h5>
            <p class="mb-3 text-muted">Enter the details of the user whose password you want to reset:</p>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Enter User Email" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="mb-3 position-relative">
                    <input type="password" class="form-control" name="user_new_password" id="password" placeholder="Enter New Password" required>
                    <button type="button" class="btn" id="togglePassword" tabindex="-1">
                        <i class="fa fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="mb-3">
                    <input type="password" class="form-control" name="user_confrm_new_password" placeholder="Confirm New Password" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="mb-3">
                    <button type="submit" id="submit_pass" class="btn btn-success w-100">Update Password</button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    #togglePassword {
        color: black;
        position: absolute;
        border: none;
        border-radius: none;
        border: 1px solid transparent;
        right: 0;
        margin-top: -38px;
        background: transparent;
    }

    #togglePassword:hover {
        color: grey;
        transform: none;
        box-shadow: none;
    }
</style>

<script>
    document
        .getElementById("togglePassword")
        .addEventListener("click", function() {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });

    const submitBtn = document.getElementById("submit_pass");

    const newPassword = document.querySelector('[name="user_new_password"]');
    const confirmPassword = document.querySelector('[name="user_confrm_new_password"]');

    const isEqual = () => {
        const value1 = newPassword.value.trim();
        const value2 = confirmPassword.value.trim();
        const isMatch = value1 === value2;

        newPassword.classList.toggle('is-invalid', !isMatch);
        confirmPassword.classList.toggle('is-invalid', !isMatch);

        if (!isMatch) {
            confirmPassword.nextElementSibling?.remove();

            const warning = document.createElement('div');
            warning.className = 'invalid-feedback';
            warning.innerText = 'Passwords do not match.';
            confirmPassword.parentNode.appendChild(warning);
        } else {
            confirmPassword.nextElementSibling?.remove();
        }

        return isMatch;
    };

    newPassword.addEventListener('input', isEqual);
    confirmPassword.addEventListener('input', isEqual);

    submitBtn.addEventListener("click", (e) => {
        let hasError = false;

        const inputs = document.querySelectorAll("input");

        inputs.forEach((input) => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                hasError = true;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isEqual()) {
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
        }
    });
</script>