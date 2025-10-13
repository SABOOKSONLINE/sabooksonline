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
        <h5>Displaying <?= $startCount ?>–<?= $endCount ?> of <?= $totalBooks ?> matching books from your catalogue</h5>
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

    <div class="filter-buttons mb-5 d-flex flex-wrap justify-content-center gap-2">
    <button class="btn btn-outline-dark btn-sm px-4 py-2 filter-btn active" data-filter="all">
        All
    </button>
    <button class="btn btn-outline-primary btn-sm px-4 py-2 filter-btn" data-filter="ebook">
        Ebook
    </button>
    <button class="btn btn-outline-success btn-sm px-4 py-2 filter-btn" data-filter="audiobook">
        Audiobook
    </button>
</div>
<?php endif; ?>



    <div class="row g-4">
    <?php foreach ($booksToShow as $book): 
        $contentId = strtolower(html_entity_decode($book['CONTENTID']));
        $cover     = html_entity_decode($book['COVER']);
        $title     = html_entity_decode($book['TITLE']);
        $category  = html_entity_decode($book['CATEGORY']);
        $desc      = html_entity_decode($book['DESCRIPTION']);
        $hPrice    = number_format((float)($book['RETAILPRICE'] ?? 0), 2);
        $ePrice    = number_format((float)($book['EBOOKPRICE'] ?? 0), 2);
        $aPrice    = number_format((float)($book['ABOOKPRICE'] ?? 0), 2);
        $pdfUrl    = trim($book['PDFURL'] ?? '');

        $types = [];
        if ($pdfUrl) $types[] = 'ebook';
        if ($book['ABOOKPRICE']) $types[] = 'audiobook';
        $dataTypeAttr = implode(' ', $types);
    ?>
        <div class="col-xl-6 col-lg-6 col-md-12" data-type="<?= $dataTypeAttr ?>">
            <div class="d-flex gap-4 p-3 border shadow-sm rounded bg-white flex-wrap flex-md-nowrap">
                
                <!-- Cover Image -->
                <div class="flex-shrink-0" style="width: 200px;">
                    <a href="/library/book/<?= $contentId ?>">
                        <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= $cover ?>" 
                             alt="<?= htmlspecialchars($title) ?>" 
                             class="img-fluid rounded" 
                             style="object-fit: cover; width: 100%; height: 300px;">
                    </a>
                </div>

                <!-- Details -->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                        <div>
                            <a class="h5 fw-bold text-decoration-none text-dark" href="/library/book/<?= $contentId ?>">
                                <?= strlen($title) > 40 ? substr($title, 0, 40) . '...' : $title ?>
                            </a>
                            <p class="text-muted small mb-2"><?= substr($desc, 0, 150) ?>...</p>
                        </div>

                        <!-- Badges -->
                        <div class="text-end">
                            <?php if (!empty($pdfUrl)): ?>
                                <span class="badge bg-primary">Ebook</span>
                            <?php endif; ?>
                            <?php if (!empty($book['ABOOKPRICE'])): ?>
                                <span class="badge bg-info text-dark">Audiobook</span>
                            <?php endif; ?>
                           <?php if ($hPrice !== 0): ?>
                                <span class="badge bg-secondary">Book</span>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- Prices -->
                    <div class="mt-2 small">
                        <strong>Book:</strong> R<?= $hPrice ?><br>
                        <strong>Ebook:</strong> R<?= $ePrice ?><br>
                        <strong>Audiobook:</strong> R<?= $aPrice ?>
                    </div>

                    <!-- Actions -->
                    <div class="mt-3 d-flex flex-column flex-sm-row gap-2">
                        <a href="/dashboards/listings/<?= $contentId ?>" 
                           class="btn btn-outline-dark btn-sm w-100 w-sm-auto" target="_blank">
                            Edit
                        </a>
                        <a href="/dashboards/listings/delete/<?= $contentId ?>"
                           class="btn btn-danger btn-sm w-100 w-sm-auto"
                           onclick="return confirm('Are you sure you want to delete this book?')">
                            Delete
                        </a>
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>


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
<script>
document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', () => {
        const filter = button.getAttribute('data-filter');
        const cards = document.querySelectorAll('[data-type]');

        cards.forEach(card => {
            const type = card.getAttribute('data-type');
            if (filter === 'all' || type.includes(filter)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // Highlight selected filter
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
    });
});
</script>
