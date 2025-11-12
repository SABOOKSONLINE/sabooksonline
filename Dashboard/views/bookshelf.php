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
$libraryBooks = $userModel->getUserBooksByAction($userId, 'library');
$likedBooks = $userModel->getUserBooksByAction($userId, 'like');

// Merge liked & library, deduplicate by CONTENTID
$combinedBooks = array_values(array_reduce(array_merge($libraryBooks, $likedBooks), function ($carry, $book) {
    $carry[$book['CONTENTID']] = $book;
    return $carry;
}, []));

function renderBookCard($book, $index, $isPurchased = false, $forceFormatCheck = true)
{
    $format = strtolower($book['format'] ?? '');

    // ✅ Only enforce format filtering if $forceFormatCheck is true
    if ($forceFormatCheck && $format !== 'ebook') {
        return;
    }

    $contentId = htmlspecialchars(strtolower($book['CONTENTID']));
    $cover = htmlspecialchars($book['COVER']);
    $title = htmlspecialchars($book['TITLE']);
    $price = isset($book['amount']) ? 'R' . htmlspecialchars($book['amount']) : '';

    $href = $isPurchased ? "/read/$contentId" : "/library/book/$contentId";
    ?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
        <div class="book-card position-relative text-center">
            <span class="book-card-num"><?= $index + 1 ?></span>
            <div class="card shadow-sm rounded-4 overflow-hidden" style="width: 100%; max-width: 260px;">
                <a href="<?= htmlspecialchars($href) ?>">
                    <img src="/cms-data/book-covers/<?= $cover ?>" class="card-img-top" alt="<?= $title ?>">
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
                <?php renderHeading("My Bookshelf", "View and manage all your saved and purchased books here."); ?>

                <!-- Purchased Books -->
                <h5 class="mb-3">Purchased Books</h5>
                <div class="row">
                    <?php if (empty($purchasedBooks)): ?>
                        <div class="alert alert-info shadow-sm" role="alert">
                            <p>You haven’t purchased any books yet.</p>
                        </div>
                    <?php else:
                        foreach ($purchasedBooks as $index => $book) {
                            renderBookCard($book, $index,true,true);
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
