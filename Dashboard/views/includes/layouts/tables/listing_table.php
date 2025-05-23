<?php
$booksPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalBooks = count($books);
$totalPages = ceil($totalBooks / $booksPerPage);
$startIndex = ($currentPage - 1) * $booksPerPage;

$booksToShow = array_slice($books, $startIndex, $booksPerPage);
$startCount = $startIndex + 1;
$endCount = min($startIndex + $booksPerPage, $totalBooks);
?>

<h5 class="mb-3">Showing <?= $startCount ?>–<?= $endCount ?> of <?= $totalBooks ?> matching books</h5>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        Books per page:
        <form method="get" class="d-inline">
            <select name="limit" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="5" <?= $booksPerPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $booksPerPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $booksPerPage == 25 ? 'selected' : '' ?>>25</option>
            </select>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
    <div><?= $startCount ?>–<?= $endCount ?> of <?= $totalBooks ?> books</div>
</div>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Date Posted</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($booksToShow)): ?>
            <tr>
                <td colspan="6" class="text-center">No books available.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($booksToShow as $book): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= htmlspecialchars($book['COVER']) ?>"
                                class="me-2 rounded shadow-sm"
                                alt="<?= htmlspecialchars($book["TITLE"]) ?> Book Cover"
                                width="50" height="75">
                            <div>
                                <a href="/dashboards/listings/<?= $book["CONTENTID"] ?>">
                                    <?= htmlspecialchars($book["TITLE"]) ?>
                                </a>
                                <br>
                                <small class="text-muted"><b>ISBN:</b> <?= htmlspecialchars($book["ISBN"]) ?></small>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($book["AUTHORS"]) ?></td>
                    <td><?= htmlspecialchars($book["DATEPOSTED"]) ?></td>
                    <td>
                        <?= $book["RETAILPRICE"] == 0 ? "FREE" : "R " . htmlspecialchars($book["RETAILPRICE"]) ?>
                    </td>
                    <td>
                        <span class="badge <?= ($book['STATUS'] ?? 'inactive') === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= htmlspecialchars(strtoupper($book["STATUS"])) ?>
                        </span>
                    </td>
                    <td>
                        <a href="/dashboards/listings/delete/<?= $book['CONTENTID'] ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure you want to delete this book?')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage - 1 ?>&limit=<?= $booksPerPage ?>">Previous</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $booksPerPage ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $currentPage + 1 ?>&limit=<?= $booksPerPage ?>">Next</a>
        </li>
    </ul>
</nav>