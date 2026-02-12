<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Books";
ob_start();

renderHeading(
    "Books Management",
    "View and manage all books from the posts table."
);

renderAlerts();

// Calculate statistics
$totalBooks = count($data["books"]);
$activeBooks = count(array_filter($data["books"], fn($b) => !empty($b['STATUS']) && strtolower($b['STATUS']) === 'active'));
$inactiveBooks = count(array_filter($data["books"], fn($b) => !empty($b['STATUS']) && strtolower($b['STATUS']) !== 'active'));
$withPrice = count(array_filter($data["books"], fn($b) => !empty($b['RETAILPRICE']) && (float)$b['RETAILPRICE'] > 0));
$totalValue = array_sum(array_map(fn($b) => (float)($b['RETAILPRICE'] ?? 0), $data["books"]));
?>

<style>
.books-page {
    --primary-color: #6366f1;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
    --dark-color: #1f2937;
    --light-bg: #f9fafb;
    --border-color: #e5e7eb;
}

.stat-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-color);
}

.stat-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.25rem;
}

.stat-card-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.search-container {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.search-input-wrapper {
    position: relative;
}

.search-input-wrapper .form-control {
    padding-left: 3rem;
    padding-right: 3rem;
    border-radius: 10px;
    border: 2px solid var(--border-color);
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.search-input-wrapper .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    z-index: 10;
}

.clear-search {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.25rem;
    display: none;
    z-index: 10;
}

.clear-search:hover {
    color: var(--danger-color);
}

.clear-search.show {
    display: block;
}

.filter-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}

.filter-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid var(--border-color);
    background: white;
    color: #6b7280;
}

.filter-badge:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    transform: translateY(-1px);
}

.filter-badge.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.books-table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 1.25rem 1.5rem;
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--dark-color);
    margin: 0;
}

.table-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.table-responsive {
    max-height: 600px;
    overflow-y: auto;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f8fafc;
    color: #374151;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem;
    border-bottom: 2px solid var(--border-color);
    position: sticky;
    top: 0;
    z-index: 5;
    white-space: nowrap;
}

.table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f3f4f6;
}

.table tbody tr:hover {
    background-color: #f9fafb;
    transform: scale(1.001);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    font-size: 0.9rem;
    color: #374151;
}

.book-title {
    font-weight: 600;
    color: var(--dark-color);
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.book-isbn {
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    color: #6b7280;
    background: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
}

.badge-custom {
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.price-tag {
    font-weight: 700;
    color: var(--success-color);
    font-size: 1rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #9ca3af;
}

.empty-state-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.table-footer {
    padding: 1.25rem 1.5rem;
    background: #f9fafb;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-wrapper {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.page-info {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: 1px solid var(--border-color);
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>

<?php
renderSectionHeader(
    "Overview Statistics",
    "Key metrics and insights about your books"
);
?>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-card-value"><?= number_format($totalBooks) ?></div>
            <div class="stat-card-label">Total Books</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-card-value"><?= number_format($activeBooks) ?></div>
            <div class="stat-card-label">Active Books</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-card-value"><?= number_format($inactiveBooks) ?></div>
            <div class="stat-card-label">Inactive Books</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-card-value">R<?= number_format($totalValue, 2) ?></div>
            <div class="stat-card-label">Total Value</div>
        </div>
    </div>
</div>

<?php
renderSectionHeader(
    "Books Catalog",
    "Search, filter, and manage your books"
);
?>

<div class="books-page">
    <?php if (empty($data["books"])): ?>
        <div class="books-table-card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h4 class="mb-2">No Books Found</h4>
                <p class="text-muted">There are currently no books in the system.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="search-container">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="bookSearch" 
                    class="form-control" 
                    placeholder="Search by title, ISBN, publisher, category..."
                    autocomplete="off">
                <button class="clear-search" id="clearSearch" title="Clear search">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="filter-badges">
                <span class="filter-badge active" data-filter="all">All Books</span>
                <span class="filter-badge" data-filter="active">Active</span>
                <span class="filter-badge" data-filter="inactive">Inactive</span>
                <span class="filter-badge" data-filter="with-price">With Price</span>
                <span class="filter-badge" data-filter="no-price">No Price</span>
            </div>
        </div>

        <div class="books-table-card">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="fas fa-list me-2"></i>Books List
                </h5>
                <div class="table-actions">
                    <button class="btn btn-sm btn-outline-secondary btn-action" id="exportBtn" title="Export to CSV">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="booksTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ID</th>
                            <th><i class="fas fa-book me-1"></i>Title</th>
                            <th><i class="fas fa-tags me-1"></i>Category</th>
                            <th><i class="fas fa-barcode me-1"></i>ISBN</th>
                            <th><i class="fas fa-building me-1"></i>Publisher</th>
                            <th><i class="fas fa-tag me-1"></i>Price</th>
                            <th><i class="fas fa-info-circle me-1"></i>Status</th>
                            <th><i class="fas fa-calendar me-1"></i>Posted</th>
                        </tr>
                    </thead>
                    <tbody id="booksTableBody">
                        <?php foreach ($data["books"] as $index => $book): ?>
                            <tr class="book-row" 
                                data-status="<?= strtolower($book['STATUS'] ?? '') ?>"
                                data-has-price="<?= !empty($book['RETAILPRICE']) && (float)$book['RETAILPRICE'] > 0 ? 'yes' : 'no' ?>">
                                <td><?= htmlspecialchars($book['ID'] ?? 'N/A') ?></td>
                                <td>
                                    <div class="book-title" title="<?= htmlspecialchars($book['TITLE'] ?? 'N/A') ?>">
                                        <a href="/library/book/<?= htmlspecialchars($book['CONTENTID'] ?? '') ?>" target="_blank" class="text-decoration-none">
                                            <?= htmlspecialchars($book['TITLE'] ?? 'N/A') ?>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom bg-info text-white">
                                        <?= htmlspecialchars($book['CATEGORY'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="book-isbn"><?= htmlspecialchars($book['ISBN'] ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <span><?= htmlspecialchars($book['PUBLISHER'] ?? 'N/A') ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($book['RETAILPRICE'])): ?>
                                        <span class="price-tag">R<?= number_format((float)$book['RETAILPRICE'], 2) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($book['STATUS'])): ?>
                                        <?php 
                                        $statusLower = strtolower($book['STATUS']);
                                        $statusClass = ($statusLower === 'active') ? 'success' : 'danger';
                                        ?>
                                        <span class="badge-custom bg-<?= $statusClass ?> text-white">
                                            <i class="fas fa-<?= $statusLower === 'active' ? 'check-circle' : 'times-circle' ?>"></i>
                                            <?= htmlspecialchars($book['STATUS']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($book['DATEPOSTED'])): ?>
                                        <span><?= date('M d, Y', strtotime($book['DATEPOSTED'])) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <div class="page-info">
                    <span id="bookCount">
                        <i class="fas fa-info-circle me-1"></i>
                        Showing <strong><?= $totalBooks ?></strong> of <strong><?= $totalBooks ?></strong> books
                    </span>
                </div>
                <div class="pagination-wrapper">
                    <button class="btn btn-sm btn-outline-secondary btn-action" id="prevPage" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="page-info" id="pageInfo">Page 1</span>
                    <button class="btn btn-sm btn-outline-secondary btn-action" id="nextPage">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('bookSearch');
    const clearSearchBtn = document.getElementById('clearSearch');
    const tableBody = document.getElementById('booksTableBody');
    const bookRows = tableBody ? Array.from(tableBody.querySelectorAll('.book-row')) : [];
    const bookCountElement = document.getElementById('bookCount');
    const filterBadges = document.querySelectorAll('.filter-badge');
    const totalBooks = <?= $totalBooks ?>;
    
    let currentFilter = 'all';
    let currentPage = 1;
    const rowsPerPage = 20;
    
    function updateClearButton() {
        if (searchInput && clearSearchBtn) {
            if (searchInput.value.trim().length > 0) {
                clearSearchBtn.classList.add('show');
            } else {
                clearSearchBtn.classList.remove('show');
            }
        }
    }
    
    function filterBooks() {
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let visibleCount = 0;
        let visibleRows = [];
        
        bookRows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            const rowText = cells.map(cell => cell.textContent || cell.innerText).join(' ').toLowerCase();
            
            const matchesSearch = !searchValue || rowText.includes(searchValue);
            
            let matchesFilter = true;
            if (currentFilter === 'active') {
                matchesFilter = row.dataset.status === 'active';
            } else if (currentFilter === 'inactive') {
                matchesFilter = row.dataset.status !== 'active' && row.dataset.status !== '';
            } else if (currentFilter === 'with-price') {
                matchesFilter = row.dataset.hasPrice === 'yes';
            } else if (currentFilter === 'no-price') {
                matchesFilter = row.dataset.hasPrice === 'no';
            }
            
            if (matchesSearch && matchesFilter) {
                row.style.display = '';
                visibleRows.push(row);
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        updatePagination(visibleRows);
        updateBookCount(visibleCount);
    }
    
    function updateBookCount(visibleCount) {
        if (bookCountElement) {
            if (visibleCount === totalBooks && currentFilter === 'all' && (!searchInput || !searchInput.value.trim())) {
                bookCountElement.innerHTML = `
                    <i class="fas fa-info-circle me-1"></i>
                    Showing <strong>${totalBooks}</strong> of <strong>${totalBooks}</strong> books
                `;
            } else {
                bookCountElement.innerHTML = `
                    <i class="fas fa-filter me-1"></i>
                    Showing <strong>${visibleCount}</strong> of <strong>${totalBooks}</strong> books
                `;
            }
        }
    }
    
    function updatePagination(visibleRows) {
        const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);
        
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        
        visibleRows.forEach((row, index) => {
            if (index >= startIndex && index < endIndex) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const pageInfo = document.getElementById('pageInfo');
        
        if (prevBtn) prevBtn.disabled = currentPage === 1;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages || totalPages === 0;
        if (pageInfo) {
            pageInfo.textContent = totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : 'No results';
        }
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            updateClearButton();
            currentPage = 1;
            filterBooks();
        });
        
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                updateClearButton();
                filterBooks();
            }
        });
    }
    
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                updateClearButton();
                currentPage = 1;
                filterBooks();
                searchInput.focus();
            }
        });
    }
    
    filterBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            filterBadges.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            currentPage = 1;
            filterBooks();
        });
    });
    
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                filterBooks();
            }
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            const visibleRows = bookRows.filter(row => row.style.display !== 'none');
            const totalPages = Math.ceil(visibleRows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                filterBooks();
            }
        });
    }
    
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const visibleRows = bookRows.filter(row => row.style.display !== 'none');
            const headers = ['ID', 'Title', 'Category', 'ISBN', 'Publisher', 'Price', 'Status', 'Date Posted'];
            
            let csv = [headers.join(',')];
            
            visibleRows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                const rowData = cells.map(cell => {
                    const text = cell.textContent.trim().replace(/"/g, '""');
                    return `"${text}"`;
                });
                csv.push(rowData.join(','));
            });
            
            const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `books_posts_export_${new Date().toISOString().slice(0, 10)}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }
    
    filterBooks();
    updateClearButton();
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>
