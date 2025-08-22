<?php
$reviewsPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalReviews = count($reviews);
$totalPages = ceil($totalReviews / $reviewsPerPage);
$startIndex = ($currentPage - 1) * $reviewsPerPage;

$reviewsToShow = array_slice($reviews, $startIndex, $reviewsPerPage);
$startCount = $startIndex + 1;
$endCount = min($startIndex + $reviewsPerPage, $totalReviews);
?>

<h5 class="mb-3">Showing <?= $startCount ?>–<?= $endCount ?> of <?= $totalReviews ?> matching reviews</h5>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        Reviews per page:
        <form method="get" class="d-inline">
            <select name="limit" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="5" <?= $reviewsPerPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $reviewsPerPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $reviewsPerPage == 25 ? 'selected' : '' ?>>25</option>
            </select>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
    <div><?= $startCount ?>–<?= $endCount ?> of <?= $totalReviews ?> reviews</div>
</div>

<table class="table table-bordered table-hover align-middle table-responsive table-bordered rounded">
    <thead class="table-light">
        <tr>
            <th>Title</th>
            <th>Review</th>
            <th>Rating</th>
            <th>User</th>
            <th>Date Posted</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($reviewsToShow)): ?>
            <tr>
                <td colspan="7" class="text-center">No reviews available.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($reviewsToShow as $review): ?>
                <tr>
                    <td><?= htmlspecialchars($review['TITLE'] ?? 'Untitled') ?></td>
                    <td><?= htmlspecialchars($review['REVIEW'] ?? '') ?></td>
                    <td>
                        <?php
                        $rating = (int)($review['RATING'] ?? 0);
                        for ($i = 0; $i < $rating; $i++) {
                            echo '<span class="text-warning">&#9733;</span>';
                        }
                        for ($i = $rating; $i < 5; $i++) {
                            echo '<span class="text-secondary">&#9733;</span>';
                        }
                        ?>
                        <span class="ms-1"><?= $rating ?>/5</span>
                    </td>
                    <td><?= htmlspecialchars($review['USERNAME'] ?? 'Unknown') ?></td>
                    <td>
                        <?= htmlspecialchars($review['DATEPOSTED'] ?? '') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage - 1 ?>&limit=<?= $reviewsPerPage ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $reviewsPerPage ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage + 1 ?>&limit=<?= $reviewsPerPage ?>">Next</a>
        </li>
    </ul>
</nav>