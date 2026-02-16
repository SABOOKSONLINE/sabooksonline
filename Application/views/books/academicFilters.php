<?php
// Get filter options
$filterOptions = $controller->getFilterOptions();
$subjects = $filterOptions['subjects'] ?? [];
$levels = $filterOptions['levels'] ?? [];
$languages = $filterOptions['languages'] ?? [];
$priceRange = $filterOptions['price_range'] ?? ['min_price' => 0, 'max_price' => 1000];

// Get current filter values
$currentSearch = $_GET['search'] ?? '';
$currentSubject = $_GET['subject'] ?? '';
$currentLevel = $_GET['level'] ?? '';
$currentLanguage = $_GET['language'] ?? '';
$currentMinPrice = $_GET['min_price'] ?? '';
$currentMaxPrice = $_GET['max_price'] ?? '';
$currentSort = $_GET['sort'] ?? 'newest';
?>

<div class="academic-filters-container">
    <!-- Filter Toggle Button -->
    <div class="mb-3 position-relative">
        <button type="button" class="btn btn-primary btn-lg" id="filterToggleBtn">
            <i class="fas fa-filter me-2"></i>Filters
            <?php if ($currentSearch || $currentSubject || $currentLevel || $currentLanguage || $currentMinPrice || $currentMaxPrice): ?>
                <span class="badge bg-light text-dark ms-2">Active</span>
            <?php endif; ?>
            <i class="fas fa-chevron-down ms-2" id="filterChevron"></i>
        </button>

        <!-- Filter Dropdown Panel -->
        <div class="filter-dropdown-panel" id="filterPanel">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h5>
                    <button type="button" class="btn-close btn-close-white" id="closeFilterBtn" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <form method="GET" action="" id="filterForm">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Search</label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       id="searchInput"
                                       placeholder="Title, author, ISBN..."
                                       value="<?= htmlspecialchars($currentSearch) ?>">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Subject Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject</label>
                            <select class="form-select" name="subject" id="subjectFilter">
                                <option value="">All Subjects</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= htmlspecialchars($subject) ?>" 
                                            <?= $currentSubject === $subject ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($subject) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Level Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Level/Grade</label>
                            <select class="form-select" name="level" id="levelFilter">
                                <option value="">All Levels</option>
                                <?php foreach ($levels as $level): ?>
                                    <option value="<?= htmlspecialchars($level) ?>" 
                                            <?= $currentLevel === $level ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($level) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Language Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Language</label>
                            <select class="form-select" name="language" id="languageFilter">
                                <option value="">All Languages</option>
                                <?php foreach ($languages as $language): ?>
                                    <option value="<?= htmlspecialchars($language) ?>" 
                                            <?= $currentLanguage === $language ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($language) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Price Range (R)</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" 
                                           class="form-control" 
                                           name="min_price" 
                                           id="minPrice"
                                           placeholder="Min" 
                                           step="0.01"
                                           min="0"
                                           value="<?= htmlspecialchars($currentMinPrice) ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" 
                                           class="form-control" 
                                           name="max_price" 
                                           id="maxPrice"
                                           placeholder="Max" 
                                           step="0.01"
                                           min="0"
                                           value="<?= htmlspecialchars($currentMaxPrice) ?>">
                                </div>
                            </div>
                            <small class="text-muted">
                                Range: R<?= number_format($priceRange['min_price'] ?? 0, 2) ?> - 
                                R<?= number_format($priceRange['max_price'] ?? 1000, 2) ?>
                            </small>
                        </div>

                        <!-- Sort Order (hidden, handled separately) -->
                        <input type="hidden" name="sort" id="sortInput" value="<?= htmlspecialchars($currentSort) ?>">

                        <!-- Filter Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Apply Filters
                            </button>
                            <a href="/library/academic" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i>Clear All
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Content Area -->
    <div class="row">
        <div class="col-12">
            <!-- Sort Bar -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <strong><?= count($books) ?></strong> book<?= count($books) !== 1 ? 's' : '' ?> found
                            <?php if ($currentSearch || $currentSubject || $currentLevel || $currentLanguage || $currentMinPrice || $currentMaxPrice): ?>
                                <span class="badge bg-info ms-2">Filtered</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0"><strong>Sort by:</strong></label>
                            <select class="form-select form-select-sm" id="sortSelect" style="width: auto;">
                                <option value="newest" <?= $currentSort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                                <option value="oldest" <?= $currentSort === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                                <option value="title_asc" <?= $currentSort === 'title_asc' ? 'selected' : '' ?>>Title A-Z</option>
                                <option value="title_desc" <?= $currentSort === 'title_desc' ? 'selected' : '' ?>>Title Z-A</option>
                                <option value="price_low" <?= $currentSort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_high" <?= $currentSort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books Grid -->
            <div class="row">
                <?php
                if (!empty($books)) {
                    require_once __DIR__ . "/catalogueView.php";
                } else {
                ?>
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            No books found matching your filters. 
                            <a href="/library/academic" class="alert-link">Clear filters</a> to see all books.
                        </div>
                    </div>
                <?php 
                }
                ?>
            </div>

            <!-- Pagination -->
            <?php if (!empty($books)): ?>
                <div class="mt-4">
                    <?php require_once __DIR__ . "/pagination.php"; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.academic-filters-container .card {
    border: none;
    border-radius: 8px;
}

.academic-filters-container .card-header {
    border-radius: 8px 8px 0 0 !important;
}

.academic-filters-container .form-label {
    font-size: 0.9rem;
    color: #495057;
}

.academic-filters-container .form-select,
.academic-filters-container .form-control {
    font-size: 0.9rem;
}

.academic-filters-container .badge {
    font-size: 0.75rem;
}

/* Filter Dropdown Panel Styles */
.filter-dropdown-panel {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-width: 500px;
    z-index: 1000;
    margin-top: 10px;
    animation: slideDown 0.3s ease-out;
}

.filter-dropdown-panel.active {
    display: block;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filter-dropdown-panel .card {
    max-height: 70vh;
    overflow-y: auto;
}

.filter-dropdown-panel .card-body {
    max-height: calc(70vh - 60px);
    overflow-y: auto;
}

#filterChevron {
    transition: transform 0.3s ease;
}

#filterToggleBtn.active #filterChevron {
    transform: rotate(180deg);
}

@media (max-width: 768px) {
    .filter-dropdown-panel {
        max-width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterPanel = document.getElementById('filterPanel');
    const filterToggleBtn = document.getElementById('filterToggleBtn');
    const closeFilterBtn = document.getElementById('closeFilterBtn');
    
    // Toggle filter panel
    if (filterToggleBtn) {
        filterToggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            filterPanel.classList.toggle('active');
            filterToggleBtn.classList.toggle('active');
        });
    }
    
    // Close filter panel
    function closeFilterPanel() {
        filterPanel.classList.remove('active');
        filterToggleBtn.classList.remove('active');
    }
    
    if (closeFilterBtn) {
        closeFilterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            closeFilterPanel();
        });
    }
    
    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (filterPanel && filterPanel.classList.contains('active')) {
            if (!filterPanel.contains(e.target) && !filterToggleBtn.contains(e.target)) {
                closeFilterPanel();
            }
        }
    });
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && filterPanel.classList.contains('active')) {
            closeFilterPanel();
        }
    });
    
    // Sort select change
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            document.getElementById('sortInput').value = this.value;
            document.getElementById('filterForm').submit();
        });
    }
    
    // Clear search button
    const clearSearchBtn = document.getElementById('clearSearch');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterForm').submit();
        });
    }
    
    // Auto-submit on filter change (optional - uncomment if desired)
    // const filterSelects = document.querySelectorAll('#subjectFilter, #levelFilter, #languageFilter');
    // filterSelects.forEach(select => {
    //     select.addEventListener('change', function() {
    //         document.getElementById('filterForm').submit();
    //     });
    // });
});
</script>
