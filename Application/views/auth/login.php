<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/google_authUrl.php";
require_once __DIR__ . "/../../Config/connection.php";

$recaptchaConfig = include __DIR__ . "/recaptcha.php";
$siteKey = $recaptchaConfig['RECAPTCHA_SITE_KEY'];
?>

<!-- Google reCAPTCHA Script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    function enableLoginBtn() {
        document.getElementById("loginBtn").disabled = false;
    }
</script>

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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['alert']); ?>
                        <?php endif; ?>

                        <form method="POST" action="/auth/login-handler">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    name="log_email"
                                    placeholder="example@mail.com"
                                    class="form-control"
                                    required
                                    autofocus>
                            </div>

                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="log_pwd2" class="form-control" required>

                                <button type="button" class="btn" id="togglePassword" tabindex="-1">
                                    <i class="fa fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>

                            <div class="form-check mb-3 d-flex justify-content-between align-content-center">
                                <div>
                                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                                    <label class="form-check-label" for="remember_me">
                                        Remember Me
                                    </label>
                                </div>

                                <small>
                                    <a href="/forgot-password" class="text-decoration-none text-muted">
                                        Forgot Password?
                                    </a>
                                </small>
                            </div>

                            <!-- Google reCAPTCHA -->
                            <div class="mb-3 d-flex justify-content-center">
                                <div class="g-recaptcha"
                                    data-sitekey="<?= $siteKey ?>"
                                    data-callback="enableLoginBtn">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-red w-100" id="loginBtn" disabled>
                                Login
                            </button>

                        </form>

                        <div class="text-center mb-3 mt-3">
                            Or continue
                        </div>

                        <!-- Google Login Button -->
                        <div class="text-center mb-4">
                            <a href="<?php echo $authUrl; ?>" class="btn btn-outline-red">
                                Login with <i class="fab fa-google"></i> Google
                            </a>
                        </div>

                        <div class="text-center mb-2">
                            <small>
                                Don't have an account? <a href="/signup">Sign Up</a>
                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <?php require_once __DIR__ . "/../includes/footer.php" ?>
    <?php require_once __DIR__ . "/../includes/scripts.php" ?>

</body>