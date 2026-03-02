<?php
// Ensure $filters is defined (set by controller)
if (!isset($filters)) {
    $filters = [
        'search' => '',
        'status' => '',
        'category' => '',
        'categories' => []
    ];
}

$booksPerPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalBooks = count($books);
$totalPages = ceil($totalBooks / $booksPerPage);
$startIndex = ($currentPage - 1) * $booksPerPage;

$booksToShow = array_slice($books, $startIndex, $booksPerPage);
$startCount = $startIndex + 1;
$endCount = min($startIndex + $booksPerPage, $totalBooks);
?>

<!-- Search and Filters Section -->
<div class="bg-white rounded-4 shadow-sm p-4 mb-4">
    <form method="get" action="" id="searchFilterForm">
        <div class="row g-3">
            <!-- Search Input -->
            <div class="col-md-4">
                <label for="search" class="form-label">
                    <i class="fas fa-search me-2"></i>Search Books
                </label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       class="form-control" 
                       placeholder="Search by title, author, ISBN, publisher..."
                       value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
            </div>
            
            <!-- Status Filter -->
            <div class="col-md-3">
                <label for="status" class="form-label">
                    <i class="fas fa-filter me-2"></i>Status
                </label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?= (isset($filters['status']) && $filters['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= (isset($filters['status']) && $filters['status'] === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    <option value="draft" <?= (isset($filters['status']) && $filters['status'] === 'draft') ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>
            
            <!-- Category Filter -->
            <div class="col-md-3">
                <label for="category" class="form-label">
                    <i class="fas fa-tags me-2"></i>Category
                </label>
                <select name="category" id="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php if (!empty($filters['categories'])): ?>
                        <?php foreach ($filters['categories'] as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($filters['category']) && $filters['category'] === $cat) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <!-- Action Buttons -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-dark w-100 me-2">
                    <i class="fas fa-search me-1"></i>Search
                </button>
                <a href="?" class="btn btn-outline-secondary" title="Clear filters">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
        
        <!-- Preserve pagination and limit -->
        <input type="hidden" name="limit" value="<?= $booksPerPage ?>">
        <input type="hidden" name="page" value="1">
    </form>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h5>Displaying <?= $startCount ?>–<?= $endCount ?> of <?= $totalBooks ?> matching books from your catalogue</h5>
        <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['category'])): ?>
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Filtered results
                <?php if (!empty($filters['search'])): ?>
                    | Search: "<?= htmlspecialchars($filters['search']) ?>"
                <?php endif; ?>
                <?php if (!empty($filters['status'])): ?>
                    | Status: <?= htmlspecialchars($filters['status']) ?>
                <?php endif; ?>
                <?php if (!empty($filters['category'])): ?>
                    | Category: <?= htmlspecialchars($filters['category']) ?>
                <?php endif; ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="col-md-6 text-end">
        <form method="get" class="d-inline">
            <label for="limit" class="form-label me-2">Books per page:</label>
            <select name="limit" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="5" <?= $booksPerPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $booksPerPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $booksPerPage == 25 ? 'selected' : '' ?>>25</option>
            </select>
            <!-- Preserve filters when changing limit -->
            <?php if (!empty($filters['search'])): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($filters['search']) ?>">
            <?php endif; ?>
            <?php if (!empty($filters['status'])): ?>
                <input type="hidden" name="status" value="<?= htmlspecialchars($filters['status']) ?>">
            <?php endif; ?>
            <?php if (!empty($filters['category'])): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($filters['category']) ?>">
            <?php endif; ?>
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
                <div class="d-flex gap-3 p-3 shadow-sm rounded-5 bg-white flex-wrap flex-lg-nowrap align-items-center">

                    <!-- Cover Image -->
                    <div class="flex-shrink-0" style="width: 200px; margin-left: auto; margin-right: auto;">
                        <a href="/library/book/<?= $contentId ?>">
                            <img src="/cms-data/book-covers/<?= $cover ?>"
                                alt="<?= htmlspecialchars($title) ?>"
                                class="img-fluid rounded-4"
                                style="object-fit: cover; width: 100%; height: 300px;">
                        </a>
                    </div>

                    <!-- Details -->
                    <div class="flex-grow-1">
                        <div class="d-block">
                            <div>
                                <a class="h5 fw-bold text-decoration-none text-dark" href="/library/book/<?= $contentId ?>">
                                    <?= strlen($title) > 40 ? substr($title, 0, 40) . '...' : $title ?>
                                </a>
                            </div>

                            <!-- Badges -->
                            <!-- <div class="my-2">
                                <?php if ($hPrice !== 0): ?>
                                    <span class="badge bg-secondary rounded-pill">Book</span>
                                <?php endif; ?>
                                <?php if (!empty($pdfUrl)): ?>
                                    <span class="badge bg-primary rounded-pill">Ebook</span>
                                <?php endif; ?>
                                <?php if (!empty($book['ABOOKPRICE'])): ?>
                                    <span class="badge bg-info text-dark rounded-pill">Audiobook</span>
                                <?php endif; ?>
                            </div> -->

                            <hr class="mt-3" />

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
                <?php
                // Build query string for pagination links (preserve filters)
                $buildQuery = function($page) use ($booksPerPage, $filters) {
                    $params = ['page' => $page, 'limit' => $booksPerPage];
                    if (!empty($filters['search'])) $params['search'] = $filters['search'];
                    if (!empty($filters['status'])) $params['status'] = $filters['status'];
                    if (!empty($filters['category'])) $params['category'] = $filters['category'];
                    return '?' . http_build_query($params);
                };
                ?>
                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $currentPage <= 1 ? '#' : $buildQuery($currentPage - 1) ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $buildQuery($i) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $currentPage >= $totalPages ? '#' : $buildQuery($currentPage + 1) ?>">Next</a>
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