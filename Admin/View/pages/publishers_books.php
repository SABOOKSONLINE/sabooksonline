<?php
include __DIR__ . "/../layouts/pageHeader.php";
include __DIR__ . "/../layouts/sectionHeader.php";
include __DIR__ . "/../layouts/cards/aCard.php";

require_once __DIR__ . "/../../Helpers/sessionAlerts.php";

$title = "Publishers Books";
ob_start();

renderHeading(
    "Publishers Books",
    "Manage books from the books table.",
);

renderAlerts();

$books = $data["books"];

// Calculate statistics
$totalBooks = count($books);
$inStock = count(array_filter($books, fn($b) => !empty($b['stock_status']) && strtoupper($b['stock_status']) === 'C'));
$outOfStock = count(array_filter($books, fn($b) => !empty($b['stock_status']) && strtoupper($b['stock_status']) !== 'C'));
$freeBooks = count(array_filter($books, fn($b) => empty($b['price']) || (float)$b['price'] == 0));
$paidBooks = count(array_filter($books, fn($b) => !empty($b['price']) && (float)$b['price'] > 0));

renderSectionHeader(
    "Overview Statistics",
    "Key metrics and insights about publishers books"
);
?>

<div class="row">
    <?php
    $cards = [
        [
            "title" => "Total Books",
            "value" => $totalBooks,
            "icon"  => "fas fa-book",
            "color" => "primary"
        ],
        [
            "title" => "In Stock",
            "value" => $inStock,
            "icon"  => "fas fa-check-circle",
            "color" => "success"
        ],
        [
            "title" => "Out of Stock",
            "value" => $outOfStock,
            "icon"  => "fas fa-times-circle",
            "color" => "danger"
        ],
        [
            "title" => "Paid Books",
            "value" => $paidBooks,
            "icon"  => "fas fa-dollar-sign",
            "color" => "warning"
        ],
    ];

    foreach ($cards as $card) {
        renderAnalysisCard($card["title"], $card["value"], $card["icon"], $card["color"]);
    }
    ?>
</div>

<?php
renderSectionHeader(
    "Publishers Books Catalog",
    "Search, filter, and manage publishers books"
);
?>

<div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2 flex-nowrap">
        <input
            type="text"
            id="bookSearch"
            class="form-control"
            placeholder="Search by title, ISBN, publisher..."
            style="width: 280px;">

        <select
            id="publisherFilter"
            class="form-select"
            style="width: 200px;">
            <option value="">All Publishers</option>
            <?php 
            $publishers = array_unique(array_filter(array_map(fn($b) => $b['publisher'] ?? null, $books)));
            foreach ($publishers as $publisher): 
            ?>
                <option value="<?= htmlspecialchars($publisher) ?>">
                    <?= htmlspecialchars($publisher) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select
            id="stockFilter"
            class="form-select"
            style="width: 150px;">
            <option value="">All Stock</option>
            <option value="C">In Stock</option>
            <option value="O">Out of Stock</option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button id="downloadCSV" class="btn btn-success">
            <i class="fas fa-file-csv me-1"></i> Download CSV
        </button>

        <button onclick="window.print()" class="btn btn-dark">
            <i class="fas fa-print me-1"></i> Print PDF
        </button>
    </div>
</div>

<div class="mb-3" style="height: calc(100vh - 455px); overflow-x: auto;">
    <table class="table table-hover table-striped align-middle border rounded shadow-sm" id="bookTable">
        <thead class="table-light">
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Stock Status</th>
                <th>Item Status</th>
                <th>Published</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></strong></td>
                    <td>
                        <strong><?= htmlspecialchars($book['title'] ?? 'N/A') ?></strong>
                        <?php if (!empty($book['substitute_isbn'])): ?>
                            <br><small class="text-muted">Substitute: <?= htmlspecialchars($book['substitute_isbn']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($book['author'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($book['publisher'] ?? 'N/A') ?></td>
                    <td>R <?= number_format((float)($book['price'] ?? 0), 2) ?></td>
                    <td><?= htmlspecialchars($book['quantity'] ?? '0') ?></td>
                    <td>
                        <?php
                        $stockStatus = strtoupper($book['stock_status'] ?? '');
                        $stockClass = $stockStatus === 'C' ? 'success' : 'danger';
                        $stockText = $stockStatus === 'C' ? 'In Stock' : ($stockStatus === 'O' ? 'Out of Stock' : $stockStatus);
                        ?>
                        <span class="badge bg-<?= $stockClass ?>">
                            <?= htmlspecialchars($stockText) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">
                            <?= htmlspecialchars($book['item_status'] ?? 'N/A') ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $rawDate = $book['pub_date'] ?? null;
                        if ($rawDate && $rawDate !== '0001-01-01' && $rawDate !== '0000-00-00') {
                            try {
                                $date = new DateTime($rawDate);
                                echo $date->format('d M Y');
                            } catch (Exception $e) {
                                echo htmlspecialchars($rawDate);
                            }
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<nav>
    <ul class="pagination justify-content-center" id="pagination"></ul>
</nav>

<script>
    const rowsPerPage = 10;
    let currentPage = 1;

    const searchInput = document.getElementById('bookSearch');
    const publisherFilter = document.getElementById('publisherFilter');
    const stockFilter = document.getElementById('stockFilter');
    const table = document.getElementById('bookTable');
    const tbody = table.querySelector('tbody');
    const pagination = document.getElementById('pagination');

    function displayTable() {
        const searchValue = searchInput.value.toLowerCase();
        const selectedPublisher = publisherFilter.value.toLowerCase();
        const selectedStock = stockFilter.value;

        const rows = Array.from(tbody.querySelectorAll('tr')).filter(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            // Publisher is at index 3 (ISBN=0, Title=1, Author=2, Publisher=3)
            const publisher = (cells[3]?.textContent || '').toLowerCase();
            // Stock status is at index 6
            const stockStatus = (cells[6]?.textContent || '').toUpperCase();

            const matchesSearch = cells.some(td =>
                td.textContent.toLowerCase().includes(searchValue)
            );

            const matchesPublisher = !selectedPublisher || publisher === selectedPublisher;
            const matchesStock = !selectedStock || stockStatus.includes(selectedStock);

            return matchesSearch && matchesPublisher && matchesStock;
        });

        const totalPages = Math.ceil(rows.length / rowsPerPage);
        if (currentPage > totalPages) currentPage = totalPages || 1;

        tbody.querySelectorAll('tr').forEach(r => r.style.display = 'none');

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.slice(start, end).forEach(r => r.style.display = '');

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        pagination.innerHTML = '';

        const createPageItem = (page, text = page, disabled = false, active = false) => {
            const li = document.createElement('li');
            li.className = `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;

            const a = document.createElement('a');
            a.className = 'page-link';
            a.href = '#';
            a.textContent = text;

            a.addEventListener('click', (e) => {
                e.preventDefault();
                if (!disabled) {
                    currentPage = page;
                    displayTable();
                }
            });

            li.appendChild(a);
            return li;
        };

        pagination.appendChild(
            createPageItem(currentPage - 1, 'Previous', currentPage === 1)
        );

        let pageList = [];

        if (totalPages <= 7) {
            for (let i = 1; i <= totalPages; i++) pageList.push(i);
        } else {
            pageList = [1];

            if (currentPage > 4) pageList.push('...');

            const start = Math.max(2, currentPage - 1);
            const end = Math.min(totalPages - 1, currentPage + 1);

            for (let i = start; i <= end; i++) pageList.push(i);

            if (currentPage + 1 < totalPages - 1) pageList.push('...');

            pageList.push(totalPages);
        }

        pageList.forEach(p => {
            if (p === '...') {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                const a = document.createElement('a');
                a.className = 'page-link';
                a.textContent = '...';
                li.appendChild(a);
                pagination.appendChild(li);
            } else {
                pagination.appendChild(
                    createPageItem(p, p, false, currentPage === p)
                );
            }
        });

        pagination.appendChild(
            createPageItem(currentPage + 1, 'Next', currentPage === totalPages)
        );
    }

    searchInput.addEventListener('keyup', () => {
        currentPage = 1;
        displayTable();
    });

    publisherFilter.addEventListener('change', () => {
        currentPage = 1;
        displayTable();
    });

    stockFilter.addEventListener('change', () => {
        currentPage = 1;
        displayTable();
    });

    document.getElementById('downloadCSV').addEventListener('click', () => {
        const rows = Array.from(tbody.querySelectorAll('tr'));

        let csv = [];
        const headers = Array.from(table.querySelectorAll('thead th'))
            .map(th => `"${th.textContent.trim()}"`);
        csv.push(headers.join(','));

        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cells = Array.from(row.querySelectorAll('td'))
                    .map(td => `"${td.textContent.trim().replace(/"/g, '""')}"`);
                csv.push(cells.join(','));
            }
        });

        const blob = new Blob([csv.join('\n')], {
            type: 'text/csv;charset=utf-8;'
        });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `publishers_books_${new Date().toISOString().slice(0, 10)}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    displayTable();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
?>
