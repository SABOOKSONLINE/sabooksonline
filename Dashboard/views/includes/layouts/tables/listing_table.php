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


<div class="row mb-3">
    <div class="col-md-6">
        <h5>Showing <?= $startCount ?>–<?= $endCount ?> of <?= $totalBooks ?> matching books</h5>
    </div>
    <div class="col-md-6 text-end">
        <form method="get" class="d-inline">
            <label for="limit" class="form-label me-2">Books per page:</label>
            <select name="limit" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="5" <?= $booksPerPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $booksPerPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $booksPerPage == 25 ? 'selected' : '' ?>>25</option>
            </select>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
</div>

<?php if (empty($booksToShow)): ?>
    <div class="alert alert-warning text-center">No books available.</div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($booksToShow as $book): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <img src="/cms-data/book-covers/<?= html_entity_decode($book['COVER']) ?>"
                         class="card-img-top" alt="<?= html_entity_decode($book["TITLE"]) ?> Book Cover"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="/dashboards/listings/<?= $book["CONTENTID"] ?>" class="text-decoration-none">
                                <?= html_entity_decode($book["TITLE"]) ?>
                            </a>
                        </h5>
                        <p class="mb-1"><strong>Author:</strong> <?= html_entity_decode($book["AUTHORS"]) ?></p>
                        <p class="mb-1"><strong>Date Posted:</strong> <?= html_entity_decode($book["DATEPOSTED"]) ?></p>
                        <p class="mb-1"><strong>Price:</strong>
                            <?= $book["RETAILPRICE"] == 0 ? "FREE" : "R " . html_entity_decode($book["RETAILPRICE"]) ?>
                        </p>
                        <div class="mt-auto">
                            <a href="/dashboards/listings/<?= $book["CONTENTID"] ?>" class="btn btn-outline-primary btn-sm mb-2 w-100" target="_blank">
                                <?= !empty($book['PDFURL']) ? 'Manage Ebook' : 'Manage book' ?>
                            </a>
                            <a href="/dashboards/listings/delete/<?= $book['CONTENTID'] ?>"
                               class="btn btn-danger btn-sm w-100"
                               onclick="return confirm('Are you sure you want to delete this book?')">
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<nav class="mt-4">
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
