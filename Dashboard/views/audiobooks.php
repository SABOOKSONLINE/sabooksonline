<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../../Application/Config/connection.php";
require_once __DIR__ . "/../models/UserModel.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = $_SESSION['ADMIN_EMAIL'] ?? '';
$userId = $_SESSION['ADMIN_USERKEY'] ?? '';

$userModel = new UserModel($conn);
$purchasedBooks = $userModel->getPurchasedBooksByUserEmail($email);

// ✅ Filter only audiobooks
$audiobooks = array_filter($purchasedBooks, function ($book) {
    return strtolower($book['format'] ?? '') === 'audiobook';
});

function renderBookCard($book, $index, $read = false)
{
    $contentId = htmlspecialchars(strtolower($book['CONTENTID']));
    $cover = htmlspecialchars($book['COVER']);
    $title = htmlspecialchars($book['TITLE']);
    $category = htmlspecialchars($book['CATEGORY'] ?? '');
    $description = htmlspecialchars($book['DESCRIPTION']);
    $format = $book['format'];
    $price = isset($book['amount']) ? 'R' . htmlspecialchars($book['amount']) : '';

    // 🟢 Use audiobook URL if format matches
    $href = (strtolower($format) === 'audiobook') ? "/library/audiobook/$contentId" : ($read ? "/read/$contentId" : "/library/book/$contentId");
    ?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
        <div class="book-card position-relative text-center">
            <span class="book-card-num"><?= $index + 1 ?></span>
            <div class="card shadow-sm rounded-4 overflow-hidden" style="width: 100%; max-width: 260px;">
                <a href="<?= htmlspecialchars($href) ?>">
                    <img src="/cms-data/book-covers/<?= $cover ?>" class="card-img-top" alt="<?= $title ?>">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-dark text-white px-2 py-1">
                            <i class="fas fa-headphones"></i> Audio
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php include __DIR__ . "/includes/header.php"; ?>
<?php include __DIR__ . "/includes/dashboard_heading.php"; ?>
<?php include __DIR__ . "/includes/nav.php"; ?>

<section>
    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . "/includes/layouts/side-bar.php"; ?>

            <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                <?php renderHeading("My Audiobooks", "Listen to and manage all your saved and purchased audiobooks here."); ?>

                <!-- Purchased AudioBooks -->
                <h5 class="mb-3">Purchased AudioBooks</h5>
                <div class="row">
                    <?php if (empty($audiobooks)): ?>
                        <div class="alert alert-info shadow-sm" role="alert">
                            <p>You haven’t purchased any audiobooks yet.</p>
                        </div>
                    <?php else:
                        foreach ($audiobooks as $index => $book) {
                            renderBookCard($book, $index, true);
                        }
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . "/includes/scripts.php"; ?>
</body>
</html>
