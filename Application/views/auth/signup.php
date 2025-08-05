<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/google_authUrl.php";
require_once __DIR__ . "/../../Config/connection.php";
?>

<body class="d-flex flex-column min-vh-100 login-page">

    <section class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                    <div class="card p-4 shadow">
                        <h3 class="text-center mb-3">
                            <a href="/home">
                                <img src="/public/images/sabo_logo.png" class="mb-2" alt="sabooksonline logo" width="150">
                            </a>
                        </h3>

                        <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
                        <?php if (!empty($_SESSION['alert'])): ?>
                            <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_SESSION['alert']['message']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['alert']); ?>
                        <?php endif; ?>

                        <form method="POST" action="/auth/signup-handler">
                            <div class="mb-3">
                                <label for="reg_name" class="form-label">Name</label>
                                <input type="text" name="reg_name" placeholder="e.g John" class="form-control" required autofocus">
                            </div>
                            <div class="mb-3">
                                <label for="reg_phone" class="form-label">Phone number</label>
                                <input type="number" name="reg_phone" placeholder="e.g 27 XX XXX XXXX" class="form-control" required">
                            </div>
                            <div class=" mb-3">
                                <label for="reg_mail" class="form-label">Email</label>
                                <input type="email" name="reg_mail" placeholder="example@mail.com" class="form-control" required">
                            </div>
                            <div class=" mb-3">
                                <label for="confirm_mail" class="form-label">Confirm Email</label>
                                <input type="email" name="confirm_mail" placeholder="example@mail.com" class="form-control" required">
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <button type="button" class="btn" id="togglePassword" tabindex="-1">
                                    <i class="fa fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required">
                            </div>

                            <!-- Terms and Conditions Checkbox -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms_accepted" id="terms_accepted" required>
                                    <label class="form-check-label" for="terms_accepted">
                                        I agree to the <a href="/terms-and-conditions" target="_blank" class="">Terms and Conditions</a> and <a href="/privacy-policy" target="_blank" class="">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-red w-100" id="signupBtn" disabled>Sign Up</button>
                        </form>

                        <div class="text-center mb-3 mt-3">
                            Or
                        </div>

                        <!-- Google Signup Button -->
                        <div class="text-center mb-4">
                            <a href="<?php echo $authUrl; ?>" class="btn btn-outline-red">
                                Sign Up with <i class="fab fa-google"></i> Google
                            </a>
                        </div>

                        <div class="text-center mb-2">
                            <small>Already have an account? <a href="/login" class="">Login</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . "/../includes/footer.php" ?>
    <?php require_once __DIR__ .  "/../includes/scripts.php" ?>
</body>