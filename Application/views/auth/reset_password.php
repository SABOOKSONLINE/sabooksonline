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
                            Password Reset!
                        </h5>

                        <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
                        <?php if (!empty($_SESSION['alert'])): ?>
                            <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($_SESSION['alert']['message']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['alert']); ?>
                        <?php endif; ?>

                        <form method="POST" action="/auth/reset-password-handler">
                            <input type="hidden" name="token" value="<?= isset($_GET['token']) ? $_GET['token'] : '' ?>" required>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ .  "/../includes/scripts.php" ?>
</body>