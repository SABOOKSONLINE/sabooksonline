<?php
// Ensure $filters is defined (set by controller)
if (!isset($filters)) {
    $filters = [
        'search' => '',
        'status' => '',
        'subject' => '',
        'subjects' => [],
        'min_price' => '',
        'max_price' => '',
        'date_from' => '',
        'date_to' => '',
        'sort' => '',
        'price_range' => ['min_price' => 0, 'max_price' => 1000]
    ];
}

$alerts = [];
if (isset($_SESSION['alerts']) && is_array($_SESSION['alerts'])) {
    $alerts = $_SESSION['alerts'];
    unset($_SESSION['alerts']);
}

$perPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$totalBooks = count($books);
$totalPages = ceil($totalBooks / $perPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($totalPages, $currentPage));

$booksForPage = array_slice($books, ($currentPage - 1) * $perPage, $perPage);
$startCount = ($currentPage - 1) * $perPage + 1;
$endCount = min($startCount + $perPage - 1, $totalBooks);
?>

<?php if (!empty($alerts)): ?>
    <div class="mb-4">
        <?php foreach ($alerts as $alert): ?>
            <?php
            $alertClass = 'alert-success';
            if ($alert['type'] === 'error') {
                $alertClass = 'alert-danger';
            }
            ?>
            <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($alert['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
        <h4 class="fw-bold mb-0">Academic Books Table</h4>
    </div>
    <a href="/dashboards/add/academic"
        class="btn btn-outline-dark">
        <i class="fas fa-plus me-2"></i> Add New
    </a>
</div>

<!-- Search and Filters Section -->
<div class="bg-white rounded-4 shadow-sm p-4 mb-4">
    <form method="get" action="" id="searchFilterForm">
        <!-- First Row: Search and Basic Filters -->
        <div class="row g-3 mb-3">
            <!-- Search Input -->
            <div class="col-md-3">
                <label for="search" class="form-label">
                    <i class="fas fa-search me-2"></i>Search Books
                </label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       class="form-control" 
                       placeholder="Search by title, author, subject..."
                       value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
            </div>
            
            <!-- Status Filter -->
            <div class="col-md-2">
                <label for="status" class="form-label">
                    <i class="fas fa-filter me-2"></i>Status
                </label>
                <select name="status" id="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="available" <?= (isset($filters['status']) && $filters['status'] === 'available') ? 'selected' : '' ?>>Available (PDF)</option>
                    <option value="draft" <?= (isset($filters['status']) && $filters['status'] === 'draft') ? 'selected' : '' ?>>Draft (No PDF)</option>
                </select>
            </div>
            
            <!-- Subject Filter -->
            <div class="col-md-2">
                <label for="subject" class="form-label">
                    <i class="fas fa-tags me-2"></i>Subject
                </label>
                <select name="subject" id="subject" class="form-select">
                    <option value="">All Subjects</option>
                    <?php if (!empty($filters['subjects'])): ?>
                        <?php foreach ($filters['subjects'] as $subj): ?>
                            <option value="<?= htmlspecialchars($subj) ?>" <?= (isset($filters['subject']) && $filters['subject'] === $subj) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($subj) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <!-- Sort Filter -->
            <div class="col-md-2">
                <label for="sort" class="form-label">
                    <i class="fas fa-sort me-2"></i>Sort By
                </label>
                <select name="sort" id="sort" class="form-select">
                    <option value="">Newest First</option>
                    <option value="title_asc" <?= (isset($filters['sort']) && $filters['sort'] === 'title_asc') ? 'selected' : '' ?>>Title A-Z</option>
                    <option value="title_desc" <?= (isset($filters['sort']) && $filters['sort'] === 'title_desc') ? 'selected' : '' ?>>Title Z-A</option>
                    <option value="date_newest" <?= (isset($filters['sort']) && $filters['sort'] === 'date_newest') ? 'selected' : '' ?>>Date: Newest</option>
                    <option value="date_oldest" <?= (isset($filters['sort']) && $filters['sort'] === 'date_oldest') ? 'selected' : '' ?>>Date: Oldest</option>
                    <option value="price_low" <?= (isset($filters['sort']) && $filters['sort'] === 'price_low') ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_high" <?= (isset($filters['sort']) && $filters['sort'] === 'price_high') ? 'selected' : '' ?>>Price: High to Low</option>
                </select>
            </div>
            
            <!-- Action Buttons -->
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="fas fa-search me-1"></i>Apply Filters
                </button>
                <a href="?" class="btn btn-outline-secondary" title="Clear all filters">
                    <i class="fas fa-times me-1"></i>Clear
                </a>
            </div>
        </div>
        
        <!-- Second Row: Advanced Filters (Collapsible) -->
        <div class="row g-3">
            <div class="col-12">
                <button type="button" class="btn btn-link p-0 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                    <i class="fas fa-chevron-down me-1"></i> <small>Advanced Filters (Price & Date Range)</small>
                </button>
            </div>
        </div>
        
        <div class="collapse" id="advancedFilters">
            <div class="row g-3 mt-2">
                <!-- Price Range -->
                <div class="col-md-3">
                    <label for="min_price" class="form-label">
                        <i class="fas fa-dollar-sign me-2"></i>Min Price (R)
                    </label>
                    <input type="number" 
                           name="min_price" 
                           id="min_price" 
                           class="form-control" 
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>">
                </div>
                
                <div class="col-md-3">
                    <label for="max_price" class="form-label">
                        <i class="fas fa-dollar-sign me-2"></i>Max Price (R)
                    </label>
                    <input type="number" 
                           name="max_price" 
                           id="max_price" 
                           class="form-control" 
                           step="0.01"
                           min="0"
                           placeholder="<?= number_format($filters['price_range']['max_price'] ?? 1000, 2) ?>"
                           value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>">
                    <small class="text-muted">Max: R<?= number_format($filters['price_range']['max_price'] ?? 1000, 2) ?></small>
                </div>
                
                <!-- Date Range -->
                <div class="col-md-3">
                    <label for="date_from" class="form-label">
                        <i class="fas fa-calendar me-2"></i>From Date
                    </label>
                    <input type="date" 
                           name="date_from" 
                           id="date_from" 
                           class="form-control"
                           value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
                </div>
                
                <div class="col-md-3">
                    <label for="date_to" class="form-label">
                        <i class="fas fa-calendar me-2"></i>To Date
                    </label>
                    <input type="date" 
                           name="date_to" 
                           id="date_to" 
                           class="form-control"
                           value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
                </div>
            </div>
        </div>
        
        <!-- Preserve pagination and limit -->
        <input type="hidden" name="limit" value="<?= $perPage ?>">
        <input type="hidden" name="page" value="1">
    </form>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <h5>Displaying <?= $startCount ?>–<?= $endCount ?> of <?= $totalBooks ?> matching books</h5>
        <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['subject']) || !empty($filters['min_price']) || !empty($filters['max_price']) || !empty($filters['date_from']) || !empty($filters['date_to'])): ?>
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Filtered results
                <?php if (!empty($filters['search'])): ?>
                    | Search: "<?= htmlspecialchars($filters['search']) ?>"
                <?php endif; ?>
                <?php if (!empty($filters['status'])): ?>
                    | Status: <?= htmlspecialchars($filters['status']) ?>
                <?php endif; ?>
                <?php if (!empty($filters['subject'])): ?>
                    | Subject: <?= htmlspecialchars($filters['subject']) ?>
                <?php endif; ?>
                <?php if (!empty($filters['min_price']) || !empty($filters['max_price'])): ?>
                    | Price: R<?= htmlspecialchars($filters['min_price'] ?? '0') ?> - R<?= htmlspecialchars($filters['max_price'] ?? '∞') ?>
                <?php endif; ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="col-md-6 text-end">
        <form method="get" class="d-inline">
            <label for="limit" class="form-label me-2">Books per page:</label>
            <select name="limit" class="form-select d-inline w-auto" onchange="this.form.submit()">
                <option value="5" <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
            </select>
            <!-- Preserve all filters when changing limit -->
            <?php foreach (['search', 'status', 'subject', 'sort', 'min_price', 'max_price', 'date_from', 'date_to'] as $filterKey): ?>
                <?php if (!empty($filters[$filterKey])): ?>
                    <input type="hidden" name="<?= $filterKey ?>" value="<?= htmlspecialchars($filters[$filterKey]) ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
</div>

<?php if (empty($books)): ?>
    <div class="alert alert-info">No books found. <a href="/dashboards/add/academic">Add</a> your first book!</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Price</th>
                    <th>Publish Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($booksForPage as $book): ?>

                    <tr>
                        <td>
                            <?php if (!empty($book['cover_image_path'])): ?>
                                <img src="/cms-data/academic/covers/<?= html_entity_decode($book['cover_image_path']) ?>"
                                    alt="Cover" class="img-thumbnail" style="max-width: 80px;">
                            <?php else: ?>
                                <span class="text-muted">No cover</span>
                            <?php endif; ?>
                        </td>
                        <td><?= html_entity_decode($book['title']) ?></td>
                        <td><?= html_entity_decode($book['subject']) ?></td>
                        <td>R <?= number_format($book['ebook_price'] ?? 0.00, 2) ?></td>
                        <td><?= !empty($book['publish_date']) ? date('M d, Y', strtotime($book['publish_date'])) : 'N/A' ?></td>
                        <td>
                            <?php if (!empty($book['pdf_path'])): ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="/dashboards/add/academic?id=<?= $book['id'] ?>&action=edit"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="<?= $book['id'] ?>"
                                    class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="/dashboards/academic/book/delete/<?= $book['id'] ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this magazine?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mt-3">
                <?php
                // Build query string for pagination links (preserve all filters)
                $buildQuery = function($page) use ($perPage, $filters) {
                    $params = ['page' => $page, 'limit' => $perPage];
                    foreach (['search', 'status', 'subject', 'sort', 'min_price', 'max_price', 'date_from', 'date_to'] as $filterKey) {
                        if (!empty($filters[$filterKey])) {
                            $params[$filterKey] = $filters[$filterKey];
                        }
                    }
                    return '?' . http_build_query($params);
                };
                ?>
                <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $currentPage <= 1 ? '#' : $buildQuery($currentPage - 1) ?>">Previous</a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i === $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $buildQuery($i) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $currentPage >= $totalPages ? '#' : $buildQuery($currentPage + 1) ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

<?php endif; ?>