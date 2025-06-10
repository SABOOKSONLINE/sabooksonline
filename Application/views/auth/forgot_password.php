<?php
require_once __DIR__ . "/../includes/header.php";

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
                        </h3>
                        </a>
                        <h5 class="my-3 text-center">
                            Forgot Password!
                        </h5>

                        <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
                        <?php if (!empty($_SESSION['alert'])): ?>
                            <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_SESSION['alert']['message']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['alert']); ?>
                        <?php endif; ?>

                        <form method="POST" action="/auth/forgot-password-handler">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    placeholder="example@mail.com"
                                    class="form-control"
                                    required
                                    autofocus
                                    value="">
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Send Reset Link</button>
                        </form>

                        <div class="text-center mb-3 mt-3">
                            Or
                        </div>

                        <div class="text-center mb-3">
                            <small>Remember your password? <a href="/login" class="text-decoration-none">Login</a></small>
                        </div>
                        <div class="text-center">
                            <small>Don’t have an account? <a href="/signup" class="text-decoration-none">Sign Up</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . "/../includes/footer.php" ?>

    <?php require_once __DIR__ .  "/../includes/scripts.php" ?>
</body>