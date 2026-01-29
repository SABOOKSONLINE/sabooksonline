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

<div class="bg-white rounded-4 shadow-sm p-3 mb-4">
    <?php if (empty($booksToShow)): ?>
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-circle"></i> No books available.
        </div>
    <?php else: ?>

        <!-- Filter Tabs (styled like form tabs) -->
        <ul class="nav nav-tabs" id="bookFilterTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active filter-btn"
                    data-filter="all"
                    type="button"
                    role="tab">
                    <i class="fas fa-book me-2"></i>All Books
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link filter-btn"
                    data-filter="ebook"
                    type="button"
                    role="tab">
                    <i class="fas fa-file-pdf me-2"></i>E-Books
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link filter-btn"
                    data-filter="audiobook"
                    type="button"
                    role="tab">
                    <i class="fas fa-headphones me-2"></i>Audiobooks
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link filter-btn"
                    data-filter="physical"
                    type="button"
                    role="tab">
                    <i class="fas fa-book-open me-2"></i>Physical Copies
                </button>
            </li>
        </ul>
    <?php endif; ?>


    <div class="row g-4 mt-1">
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
                <div class="d-flex gap-4 p-3 border shadow-sm rounded-4 bg-white flex-wrap flex-md-nowrap">

                    <!-- Cover Image -->
                    <div class="flex-shrink-0" style="width: 200px; margin-left: auto; margin-right: auto;">
                        <a href="/library/book/<?= $contentId ?>">
                            <img src="/cms-data/book-covers/<?= $cover ?>"
                                alt="<?= htmlspecialchars($title) ?>"
                                class="img-fluid rounded"
                                style="object-fit: cover; width: 100%; height: 300px;">
                        </a>
                    </div>

                    <!-- Details -->
                    <div class="flex-grow-1 align-items-center justify-content-center">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <a class="h5 fw-bold text-decoration-none text-dark" href="/library/book/<?= $contentId ?>">
                                    <?= strlen($title) > 40 ? substr($title, 0, 40) . '...' : $title ?>
                                </a>
                            </div>

                            <hr class="mt-2" />

                            <!-- Badges -->
                            <div class="text-end my-2">
                                <?php if ($hPrice !== 0): ?>
                                    <span class="badge bg-secondary rounded-pill">Book</span>
                                <?php endif; ?>
                                <?php if (!empty($pdfUrl)): ?>
                                    <span class="badge bg-primary rounded-pill">Ebook</span>
                                <?php endif; ?>
                                <?php if (!empty($book['ABOOKPRICE'])): ?>
                                    <span class="badge bg-info text-dark rounded-pill">Audiobook</span>
                                <?php endif; ?>
                            </div>

                            <p class="small text-muted mb-2"><?= substr($desc, 0, 100) ?>...</p>
                        </div>

                        <!-- Prices -->
                        <ul class="list-group list-group-flush mt-2 small">
                            <?php if ($ePrice > 0): ?>
                                <li class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-file-pdf text-primary me-2"></i>E-Book</span>
                                    <span class="badge bg-primary">R<?= $ePrice ?></span>
                                </li>
                            <?php endif; ?>

                            <?php if ($aPrice > 0): ?>
                                <li class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-headphones text-success me-2"></i>Audiobook</span>
                                    <span class="badge bg-success">R<?= $aPrice ?></span>
                                </li>
                            <?php endif; ?>

                            <?php if ($hPrice > 0): ?>
                                <li class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-book text-secondary me-2"></i>Physical Book</span>
                                    <span class="badge bg-secondary">R<?= $hPrice ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <!-- Actions -->
                        <div class="mt-3 d-flex flex-column flex-sm-row gap-2">
                            <a href="/dashboards/listings/<?= $contentId ?>"
                                class="btn btn-outline-dark btn-sm w-100 w-sm-auto" target="_blank">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
                            <a href="/dashboards/listings/delete/<?= $contentId ?>"
                                class="btn btn-danger btn-sm w-sm-auto"
                                onclick="return confirm('Are you sure you want to delete this book?')">
                                <i class="fas fa-trash"></i>
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
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #000;
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: #000;
        border-color: #000;
    }

    .nav-tabs .nav-link.disabled {
        color: #6c757d;
        background-color: #e9ecef;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.65;
    }

    .nav-tabs .nav-link.disabled:hover {
        color: #6c757d;
        background-color: #e9ecef;
    }
</style>

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